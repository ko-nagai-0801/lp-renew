<?php

/**
 * NEWS Index 一覧ループ（検索＆ハイライト＆安定ページャ／★投稿日優先）
 * template-parts/news-index-section-list.php
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 *
 * 更新履歴:
 * - 1.8.1 (2025-09-16): シングル復元用の state（page/q/base）を sessionStorage に保存。クリック時のみ保存。
 * - 1.8.0 (2025-09-16): 一覧→シングルの from_page / from_q 付与を廃止（純粋なパーマリンクに統一）。
 * - 1.7.0 (2025-09-08): 抜粋をサーバ側で短縮（既定80）・一致箇所リストは既定非表示。
 * - 1.6.0 (2025-09-08): ★並び順を投稿日 DESC に統一。ignore_sticky_posts を追加。
 * - 1.5.0 (2025-09-05): SPコンパクト版ページャの端数字強制表示を追加。
 * - 1.4.0 (2025-09-05): ページャ2系統（通常/コンパクト）に分割。
 * - 1.3.0 (2025-09-05): 「1」リンク強制表示のフェイルセーフを追加。
 * - 1.2.0 (2025-09-04): paginate_links() に統一。
 * - 1.1.0 (2025-09-03): from_page / from_q を一時導入（※本版で撤去）。
 * - 1.0.0: 初版
 */
if (!defined('ABSPATH')) exit;

/* ======================= クエリ準備 ======================= */
$paged     = max(1, (int) get_query_var('paged'), (int) get_query_var('page')); // 現在ページ
$post_type = post_type_exists('notice') ? 'notice' : 'post';                     // CPT 'notice' があれば優先
$q_word    = isset($_GET['q']) ? sanitize_text_field(wp_unslash($_GET['q'])) : ''; // 検索語（未入力なら空）

// --- 検索語の正規化（ひら→カナ・半→全・大/小・数字半全などのブレ吸収） ---
$q_kv   = mb_convert_kana($q_word, 'KV', 'UTF-8');
$q_hira = function_exists('lp_to_hiragana') ? lp_to_hiragana($q_kv) : $q_kv;
$q_kata = function_exists('lp_to_katakana') ? lp_to_katakana($q_hira) : $q_kv;
$q_fold = function_exists('lp_fold_for_search') ? lp_fold_for_search($q_word) : $q_word;

// --- ベース引数（存在すればビルダーへ委譲／無ければフォールバック） ---
$args = function_exists('lp_news_build_query_args')
  ? lp_news_build_query_args([
    'post_type'      => $post_type,
    'paged'          => $paged,
    'posts_per_page' => (int) get_option('posts_per_page'),
    'no_found_rows'  => false, // ページャを使うので false
  ])
  : [
    'post_type'      => $post_type,
    'orderby'        => 'date',   // Fallback でも投稿日
    'order'          => 'DESC',
    'paged'          => $paged,
    'posts_per_page' => (int) get_option('posts_per_page'),
    'no_found_rows'  => false,
  ];

// --- ★ここで“必ず”投稿日優先に統一（ビルダーが別orderbyでも上書き） ---
$args['orderby'] = 'date';     // 投稿日（post_date）
$args['order']   = 'DESC';     // 新しい順
$args['ignore_sticky_posts'] = true; // 固定表示の影響を無効化

// post のみ category/news に限定（任意仕様）
if ($post_type === 'post') {
  $news_cat = get_category_by_slug('news');
  if ($news_cat && !is_wp_error($news_cat)) $args['cat'] = (int) $news_cat->term_id;
}

// --- WP_Query の s に正規化語を同梱（OR検索に寄せる） ---
if ($q_word !== '') {
  $cands_raw = [$q_word, $q_hira, $q_kata, $q_fold];
  $seen  = [];
  $cands = [];
  foreach ($cands_raw as $v) {
    $v = function_exists('lp_normalize_spaces') ? lp_normalize_spaces($v) : trim($v);
    if ($v === '' || isset($seen[$v])) continue; // 重複排除
    $seen[$v] = true;
    $cands[]  = $v;
  }
  if ($cands) $args['s'] = implode(' ', $cands);
}

// --- 実行 ---
$q = new WP_Query($args);
$has_posts = ((int) $q->found_posts) > 0;

/**
 * paginate_links の配列を生成し整形するヘルパ
 *
 * @param int  $mid_size   現在ページ周辺の表示数
 * @param bool $is_compact SP向けコンパクト版なら true（1/最終 隣の数字を強制表示）
 * @return array           li 内に入れる a/span の HTML配列
 */
$build_links = function (int $mid_size, bool $is_compact = false) use ($q, $paged, $q_word) {
  $total = max(1, (int) $q->max_num_pages);

  if ($total === 1) {
    return ['<span class="page-numbers current" aria-current="page">1</span>'];
  }

  $links = paginate_links([
    'type'         => 'array',
    'total'        => $total,
    'current'      => $paged,
    'mid_size'     => $mid_size,
    'end_size'     => 1,
    'prev_next'    => true,
    'prev_text'    => '<span class="pg-only-wide">前へ</span><span class="pg-only-narrow" aria-hidden="true">‹</span>',
    'next_text'    => '<span class="pg-only-wide">次へ</span><span class="pg-only-narrow" aria-hidden="true">›</span>',
    'add_args'     => $q_word !== '' ? ['q' => $q_word] : false,
    'add_fragment' => '#news-index',
  ]);

  // --- フェイルセーフ: 「1」が無ければ必ず挿入 ---
  $has_page1 = false;
  foreach ($links as $html) {
    if (preg_match('/>(\s*?)1(\s*?)<\/(a|span)>/u', $html)) {
      $has_page1 = true;
      break;
    }
  }
  if (!$has_page1) {
    $p1_url  = get_pagenum_link(1);
    if ($q_word !== '') $p1_url = add_query_arg('q', $q_word, $p1_url);
    $p1_url .= '#news-index';
    $p1_html = ($paged === 1)
      ? '<span class="page-numbers current" aria-current="page">1</span>'
      : '<a class="page-numbers" href="' . esc_url($p1_url) . '">1</a>';
    $insert_pos = 0;
    foreach ($links as $i => $html) {
      if (preg_match('/class="[^"]*\bprev\b[^"]*"/', $html)) {
        $insert_pos = $i + 1;
        break;
      }
    }
    array_splice($links, $insert_pos, 0, [$p1_html]);
  }

  // --- SPコンパクト専用: 端のページでは隣の数字を必ず見せる ---
  if ($is_compact) {
    // 1ページ目では「2」を追加
    if ($paged === 1 && $total >= 2) {
      $has2 = false;
      foreach ($links as $html) {
        if (preg_match('/>(\s*?)2(\s*?)<\/(a|span)>/u', $html)) {
          $has2 = true;
          break;
        }
      }
      if (!$has2) {
        $p2_url  = get_pagenum_link(2);
        if ($q_word !== '') $p2_url = add_query_arg('q', $q_word, $p2_url);
        $p2_url .= '#news-index';
        $p2_html = '<a class="page-numbers" href="' . esc_url($p2_url) . '">2</a>';

        // 最初のドットの前に差し込む（構成: 1, …, 最終, 次へ）
        $dots_index = null;
        foreach ($links as $i => $html) {
          if (strpos($html, 'class="page-numbers dots"') !== false) {
            $dots_index = $i;
            break;
          }
        }
        $pos = ($dots_index === null) ? 1 : $dots_index;
        array_splice($links, $pos, 0, [$p2_html]);
      }
    }

    // 最終ページでは「総頁-1」を追加
    if ($paged === $total && $total >= 2) {
      $prev_num = $total - 1;
      if ($prev_num > 1) {
        $has_prev_num = false;
        foreach ($links as $html) {
          if (preg_match('/>(\s*?)' . preg_quote((string)$prev_num, '/') . '(\s*?)<\/(a|span)>/u', $html)) {
            $has_prev_num = true;
            break;
          }
        }
        if (!$has_prev_num) {
          $pn_url  = get_pagenum_link($prev_num);
          if ($q_word !== '') $pn_url = add_query_arg('q', $q_word, $pn_url);
          $pn_url .= '#news-index';
          $pn_html = '<a class="page-numbers" href="' . esc_url($pn_url) . '">' . (int)$prev_num . '</a>';

          // 最後のドットの直後に差し込む（構成: 前へ, 1, …, 最終）
          $dots_index = null;
          for ($i = count($links) - 1; $i >= 0; $i--) {
            if (strpos($links[$i], 'class="page-numbers dots"') !== false) {
              $dots_index = $i;
              break;
            }
          }
          $pos = ($dots_index === null) ? max(0, array_key_last($links) - 1) : $dots_index + 1;
          array_splice($links, $pos, 0, [$pn_html]);
        }
      }
    }
  }

  return $links;
};
?>

<section id="news-index" class="news section container" tabindex="-1">
  <div class="news__inner">

    <?php
    // ===== セクション見出し =====
    get_template_part('template-parts/section-header', null, [
      'id'          => '',
      'sub'         => 'NEWS',
      'title'       => 'お知らせ',
      'tag'         => 'h2',
      'extra_class' => 'news__header',
    ]);
    ?>

    <!-- 検索フォーム -->
    <form class="news__filter" method="get" action="<?php echo esc_url(get_permalink()); ?>">
      <label class="news__filter-label" for="news-index-search">キーワード検索</label>
      <div class="news__filter-row">
        <input class="news__filter-input" id="news-index-search" type="search" name="q"
          value="<?php echo esc_attr($q_word); ?>" placeholder="キーワードを入力" inputmode="search">
        <button class="news__filter-submit" type="submit">検索</button>
      </div>
    </form>

    <div class="news__body">
      <ul class="news__list">
        <?php if ($q->have_posts()) : while ($q->have_posts()) : $q->the_post();
            $post_id = get_the_ID();

            // 一覧→シングル：純粋なパーマリンクのみ（余計なクエリは付けない）
            $permalink = get_permalink();

            // メタ（種別ラベル・NEW/更新バッジなど）
            $label = function_exists('lp_news_get_label') ? lp_news_get_label($post_id, $post_type, 'お知らせ') : 'お知らせ';
            $flags = function_exists('lp_news_get_flags') ? lp_news_get_flags($post_id) : ['new' => false, 'updated' => false, 'significant' => false];

            // ★ 表示する日付は「投稿日」を採用（並びと統一）
            $date_machine = get_the_date('c');
            $date_human   = get_the_date('Y.m.d');

            // タイトル（空対策）
            $title_raw  = get_the_title();
            if (trim(wp_strip_all_tags((string)$title_raw)) === '') $title_raw = 'No Title';
            $title_html = ($q_word !== '' && function_exists('lp_news_highlight_html'))
              ? lp_news_highlight_html($title_raw, $q_word)
              : esc_html($title_raw);

            /**
             * 抜粋・一致箇所
             * - モバイルで伸びないよう、サーバ側で短めに切る
             * - 追加一致は既定では出さない（$EXTRA_MAX=0）
             */
            $MAIN_LEN  = (int) apply_filters('lp_news_index_main_len', 80); // 既定80文字
            $EXTRA_MAX = (int) apply_filters('lp_news_index_extra_max', 0); // 既定0=出さない
            $EXTRA_LEN = (int) apply_filters('lp_news_index_extra_len', 60);

            $plain = function_exists('lp_news_plain_content')
              ? lp_news_plain_content($post_id)
              : wp_strip_all_tags(get_the_content(''));

            if (function_exists('lp_build_main_and_extra_snippets')) {
              [$main_snip, $extra_snips] = lp_build_main_and_extra_snippets($plain, $q_word, $MAIN_LEN, $EXTRA_LEN, $EXTRA_MAX);
            } else {
              $main_snip   = mb_substr($plain, 0, $MAIN_LEN);
              $extra_snips = [];
            }

            $excerpt_html = ($q_word !== '' && function_exists('lp_news_highlight_html'))
              ? lp_news_highlight_html($main_snip, $q_word)
              : esc_html($main_snip);

            $extra_htmls = [];
            foreach ($extra_snips as $sn) {
              $extra_htmls[] = ($q_word !== '' && function_exists('lp_news_highlight_html'))
                ? lp_news_highlight_html($sn, $q_word)
                : esc_html($sn);
            }
        ?>
            <li class="news__item">
              <a href="<?php echo esc_url($permalink); ?>" class="news__link" rel="bookmark">
                <img class="news__icon" src="<?php echo esc_url(get_theme_file_uri('assets/img/neonlogo-02.webp')); ?>" alt="">
                <div class="news__content">
                  <time datetime="<?php echo esc_attr($date_machine); ?>" class="news__date"><?php echo esc_html($date_human); ?></time>
                  <span class="news__label"><?php echo esc_html($label); ?></span>
                  <?php if (!empty($flags['new'])): ?>
                    <span class="news__badge news__badge--new" aria-label="新着">NEW</span>
                  <?php elseif (!empty($flags['updated'])): ?>
                    <span class="news__badge news__badge--updated" aria-label="更新">更新</span>
                  <?php endif; ?>
                  <p class="news__text"><?php echo $title_html; ?></p>
                  <?php if ($excerpt_html !== ''): ?><p class="news__excerpt"><?php echo $excerpt_html; ?></p><?php endif; ?>

                  <?php if ($extra_htmls): // 既定では出ない（EXTRA_MAX=0） 
                  ?>
                    <ul class="news__matches" aria-label="本文の他の一致箇所">
                      <?php foreach ($extra_htmls as $eh): ?><li class="news__match"><?php echo $eh; ?></li><?php endforeach; ?>
                    </ul>
                  <?php endif; ?>
                </div>
              </a>
            </li>
          <?php endwhile;
          wp_reset_postdata();
        else: ?>
          <li class="news__empty" role="status" aria-live="polite">
            <p class="news__empty-text">
              <?php echo ($q_word !== '') ? '「' . esc_html($q_word) . '」を含むお知らせは見つかりませんでした。' : '現在、掲載中のお知らせはありません。'; ?>
            </p>
          </li>
        <?php endif; ?>
      </ul>
    </div>

    <?php if ($q_word !== ''): ?>
      <p class="news__return"><a href="<?php echo esc_url(get_permalink()); ?>">検索をクリアして一覧へ戻る</a></p>
    <?php endif; ?>

    <?php if ($has_posts): ?>
      <?php
      // 通常: mid_size=2 / コンパクト: mid_size=0 + 端の隣数字を強制表示
      $links_full    = $build_links(2, false);
      $links_compact = $build_links(0, true);
      ?>
      <nav class="news__pager" aria-label="ニュースのページ送り">
        <ul class="page-numbers page-numbers--full">
          <?php foreach ($links_full as $html): ?>
            <li><?php echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                ?></li>
          <?php endforeach; ?>
        </ul>
        <ul class="page-numbers page-numbers--compact">
          <?php foreach ($links_compact as $html): ?>
            <li><?php echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                ?></li>
          <?php endforeach; ?>
        </ul>
      </nav>
    <?php endif; ?>

  </div><!-- /.news__inner -->
</section>

<script>
  /**
   * 一覧→シングルの“戻る状態”を sessionStorage に保存
   * - クリックした時だけ {page, q, base} を保存（他ページで汚れにくい）
   */
  (function() {
    var state = {
      page: <?php echo (int) $paged; ?>,
      q: <?php echo json_encode($q_word); ?>,
      base: <?php echo json_encode(get_permalink()); ?>
    };
    document.addEventListener('click', function(e) {
      var a = e.target.closest('.news__link');
      if (!a) return;
      try {
        sessionStorage.setItem('lpNewsBack', JSON.stringify(state));
      } catch (e) {}
    }, {
      passive: true
    });
  })();
</script>