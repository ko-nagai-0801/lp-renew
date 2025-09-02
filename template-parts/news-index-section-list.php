<?php

/**
 * NEWS Index 一覧ループ
 *  - 抜粋スニペット + ヒット強調（ひら/カナ・半/全・大/小・数字半全 無視で一致）
 *  - 「スペースを含む単一フレーズ」/「スペース区切りの複数語句」両対応
 *  - 本文後半ヒットもタイトル下にヒット周辺を表示
 *  - ページネーションは検索語を保持しつつ #news-index へ
 *  - 総ページ数が 1 でもページャ表示
 */
if (!defined('ABSPATH')) exit;

/* ==========================================================
 * かな/英数 正規化（数字の半全も無視）＋ 対応マップ生成
 * ========================================================== */

if (!function_exists('lp_to_hiragana')) {
  function lp_to_hiragana(string $s): string
  {
    return preg_replace_callback('/[\x{30A1}-\x{30FA}]/u', function ($m) {
      $cp = function_exists('mb_ord') ? mb_ord($m[0], 'UTF-8') : (class_exists('IntlChar') ? IntlChar::ord($m[0]) : null);
      if ($cp === null) return $m[0];
      return function_exists('mb_chr') ? mb_chr($cp - 0x60, 'UTF-8') : (class_exists('IntlChar') ? IntlChar::chr($cp - 0x60) : $m[0]);
    }, $s);
  }
}
if (!function_exists('lp_to_katakana')) {
  function lp_to_katakana(string $s): string
  {
    return preg_replace_callback('/[\x{3041}-\x{3096}]/u', function ($m) {
      $cp = function_exists('mb_ord') ? mb_ord($m[0], 'UTF-8') : (class_exists('IntlChar') ? IntlChar::ord($m[0]) : null);
      if ($cp === null) return $m[0];
      return function_exists('mb_chr') ? mb_chr($cp + 0x60, 'UTF-8') : (class_exists('IntlChar') ? IntlChar::chr($cp + 0x60) : $m[0]);
    }, $s);
  }
}

/** 検索用折り畳み：半角ｶﾅ→全角(濁点結合), カナ→ひら, 全角英数→半角, 小文字化 */
if (!function_exists('lp_fold_for_search')) {
  function lp_fold_for_search(string $s): string
  {
    $s = mb_convert_kana($s, 'KV', 'UTF-8'); // ﾞ/ﾟを結合
    $s = lp_to_hiragana($s);
    $s = preg_replace_callback('/[\x{FF10}-\x{FF19}\x{FF21}-\x{FF3A}\x{FF41}-\x{FF5A}]/u', function ($m) {
      $cp = function_exists('mb_ord') ? mb_ord($m[0], 'UTF-8') : IntlChar::ord($m[0]);
      return function_exists('mb_chr') ? mb_chr($cp - 0xFEE0, 'UTF-8') : IntlChar::chr($cp - 0xFEE0);
    }, $s);
    return mb_strtolower($s, 'UTF-8');
  }
}

/** ★ Unicode空白→半角スペース正規化（WP_Query用） */
if (!function_exists('lp_normalize_spaces')) {
  function lp_normalize_spaces(string $s): string
  {
    $s = preg_replace('/[\p{Z}\s]+/u', ' ', $s);
    return trim($s ?? '');
  }
}

/** 正規化文字列と「正規化→元」対応マップを作成 */
if (!function_exists('lp_make_norm_and_map')) {
  function lp_make_norm_and_map(string $text): array
  {
    $norm = '';
    $map  = [];
    $len  = mb_strlen($text, 'UTF-8');
    $i = 0;

    while ($i < $len) {
      $start = $i;
      $ch = mb_substr($text, $i, 1, 'UTF-8');
      $i++;

      // 半角カナ + 濁点/半濁点
      if (preg_match('/[\x{FF66}-\x{FF9D}]/u', $ch) && $i < $len) {
        $next = mb_substr($text, $i, 1, 'UTF-8');
        if (preg_match('/[\x{FF9E}-\x{FF9F}]/u', $next)) {
          $ch .= $next;
          $i++;
        }
      }
      // 全角ひら/カナ + 結合濁点/半濁点
      if ($i <= $len && preg_match('/[\x{3041}-\x{30FA}]/u', mb_substr($text, $i - 1, 1, 'UTF-8')) && $i < $len) {
        $next = mb_substr($text, $i, 1, 'UTF-8');
        if (preg_match('/[\x{3099}\x{309A}]/u', $next)) {
          $ch .= $next;
          $i++;
        }
      }

      $n = lp_fold_for_search($ch);

      $nlen = mb_strlen($n, 'UTF-8');
      for ($k = 0; $k < $nlen; $k++) $map[] = $start;
      $norm .= $n;
    }
    return [$norm, $map];
  }
}

/** クエリをトークン化（語＋フレーズ両対応） */
if (!function_exists('lp_query_tokens')) {
  function lp_query_tokens(string $query): array
  {
    $query = trim($query);
    if ($query === '') return [];

    $split = preg_split('/[\p{Z}\s、,]+/u', $query, -1, PREG_SPLIT_NO_EMPTY);
    $tokens = $split ?: [];
    if (preg_match('/[\p{Z}\s]/u', $query)) $tokens[] = $query; // フレーズ

    // 正規化キーでユニーク化
    $seen = [];
    $out = [];
    foreach ($tokens as $t) {
      $key = lp_fold_for_search($t);
      if ($key === '' || isset($seen[$key])) continue;
      $seen[$key] = true;
      $out[] = $t;
    }
    return $out;
  }
}

/* ==========================================================
 * テキスト整形・一致区間抽出・スニペット生成・ハイライト
 * ========================================================== */

/** ★ 本文（post_content）を常に使用してプレーン化 */
if (!function_exists('lp_news_plain_content')) {
  function lp_news_plain_content($post_id): string
  {
    $raw  = get_post_field('post_content', $post_id) ?: '';
    $text = wp_strip_all_tags((string)$raw, true);
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');

    // […], […], [...] のような省略表記をスペース付き三点リーダに正規化
    // ※ \x{2026} は “…”（U+2026）の明示表記にしてエディタ依存を回避
    $text = preg_replace('/\s*\[\s*(?:&hellip;|\x{2026}|\.{3})\s*\]\s*/u', ' \xE2\x80\xA6 ', $text);

    // 連続空白を1つに
    $text = preg_replace('/\s+/u', ' ', $text);

    return trim($text);
  }
}

/** 正規化一致で合致区間を（元文字インデックスで）収集＆マージ */
if (!function_exists('lp_news_collect_ranges')) {
  function lp_news_collect_ranges(string $text, string $query): array
  {
    [$norm, $map] = lp_make_norm_and_map($text);
    $tokens = lp_query_tokens($query);
    if (!$tokens) return [];

    $ol = mb_strlen($text, 'UTF-8');
    $ranges = [];
    foreach ($tokens as $tok) {
      $t = lp_fold_for_search($tok);
      if ($t === '') continue;
      $off = 0;
      $tlen = mb_strlen($t, 'UTF-8');
      while (($pos = mb_strpos($norm, $t, $off, 'UTF-8')) !== false) {
        $origStart = $map[$pos] ?? 0;
        $lastNorm  = $pos + max(0, $tlen - 1);
        $origEnd   = ($map[$lastNorm] ?? ($ol - 1)) + 1;
        $ranges[]  = [$origStart, $origEnd];
        $off = $pos + max(1, $tlen);
      }
    }
    if (!$ranges) return [];

    // マージ
    usort($ranges, fn($a, $b) => $a[0] <=> $b[0]);
    $merged = [];
    foreach ($ranges as $r) {
      if (!$merged || $merged[count($merged) - 1][1] < $r[0]) {
        $merged[] = $r;
      } else {
        $merged[count($merged) - 1][1] = max($merged[count($merged) - 1][1], $r[1]);
      }
    }
    return $merged;
  }
}

/** 1つの範囲に対するスニペット文字列（…前後＋抜粋） */
if (!function_exists('lp_slice_snippet')) {
  function lp_slice_snippet(string $text, int $hitStart, int $maxLen = 140, int $context = 60): string
  {
    $len   = mb_strlen($text, 'UTF-8');
    $start = max(0, $hitStart - $context);
    $snippet = mb_substr($text, $start, $maxLen, 'UTF-8');
    $prefix  = $start > 0 ? '…' : '';
    $suffix  = ($start + $maxLen) < $len ? '…' : '';
    return $prefix . $snippet . $suffix;
  }
}

/** 複数一致→メイン＋追加スニペット */
if (!function_exists('lp_build_main_and_extra_snippets')) {
  function lp_build_main_and_extra_snippets(string $text, string $query, int $maxLen = 140, int $context = 60, int $maxExtras = 3): array
  {
    $ranges = lp_news_collect_ranges($text, $query);
    $len = mb_strlen($text, 'UTF-8');

    if (!$ranges) {
      $main = $len > $maxLen ? (mb_substr($text, 0, $maxLen, 'UTF-8') . '…') : $text;
      return [$main, []];
    }

    [$s0, $e0] = $ranges[0];
    $main      = lp_slice_snippet($text, $s0, $maxLen, $context);
    $mainStart = max(0, $s0 - $context);
    $mainEnd   = $mainStart + $maxLen;

    $extras = [];
    foreach (array_slice($ranges, 1) as [$s, $e]) {
      $mid = (int) floor(($s + $e) / 2);
      if ($mid >= $mainEnd || $mid <= $mainStart) {
        $extras[] = lp_slice_snippet($text, $s, $maxLen, $context);
        if (count($extras) >= $maxExtras) break;
      }
    }
    if (!$extras && $len > ($mainEnd + 80)) {
      $tailStart = max(0, $len - ($maxLen + 10));
      $extras[]  = '…' . mb_substr($text, $tailStart, $maxLen, 'UTF-8');
    }

    return [$main, $extras];
  }
}

/** 正規化一致に基づく <mark> ハイライト */
if (!function_exists('lp_news_highlight_html')) {
  function lp_news_highlight_html(string $text, string $query): string
  {
    if ($text === '' || trim($query) === '') return esc_html($text);
    $ranges = lp_news_collect_ranges($text, $query);
    if (!$ranges) return esc_html($text);

    $out = '';
    $cur = 0;
    $ol = mb_strlen($text, 'UTF-8');
    foreach ($ranges as [$s, $e]) {
      if ($cur < $s) $out .= esc_html(mb_substr($text, $cur, $s - $cur, 'UTF-8'));
      $hit = mb_substr($text, $s, $e - $s, 'UTF-8');
      $out .= '<mark class="news__hit">' . esc_html($hit) . '</mark>';
      $cur = $e;
    }
    if ($cur < $ol) $out .= esc_html(mb_substr($text, $cur, $ol - $cur, 'UTF-8'));
    return $out;
  }
}

/* ==========================================================
 * クエリ準備 & WP_Query
 * ========================================================== */

// 現在ページ
$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));

// 投稿タイプ判定
$post_type = post_type_exists('notice') ? 'notice' : 'post';

// 検索語（?q=）
$q_word = isset($_GET['q']) ? sanitize_text_field(wp_unslash($_GET['q'])) : '';

// WP_Query 用の OR 候補
$q_kv   = mb_convert_kana($q_word, 'KV', 'UTF-8');
$q_hira = lp_to_hiragana($q_kv);
$q_kata = lp_to_katakana($q_hira);
$q_fold = lp_fold_for_search($q_word);

$args = [
  'post_type'           => $post_type,
  'post_status'         => 'publish',
  'paged'               => $paged,
  'posts_per_page'      => get_option('posts_per_page'),
  'ignore_sticky_posts' => true,
  'no_found_rows'       => false,
  'orderby'             => 'date',
  'order'               => 'DESC',
];

if ($post_type === 'post') {
  $news_cat = get_category_by_slug('news');
  if ($news_cat && !is_wp_error($news_cat)) $args['cat'] = (int)$news_cat->term_id;
}

if ($q_word !== '') {
  // 全角スペースなどを半角に揃えて 's' を構築
  $cands_raw = [$q_word, $q_hira, $q_kata, $q_fold];
  $seen = [];
  $cands = [];
  foreach ($cands_raw as $v) {
    $v = lp_normalize_spaces($v);
    if ($v === '') continue;
    if (isset($seen[$v])) continue;
    $seen[$v] = true;
    $cands[] = $v;
  }
  $args['s'] = implode(' ', $cands);
}

$q = new WP_Query($args);
$has_posts = ((int) $q->found_posts) > 0; // ← 追加
?>

<section id="news-index" class="news section container" tabindex="-1">
  <div class="news__inner">

    <?php
    get_template_part('components/section-header', null, [
      'id' => '',
      'sub' => 'NEWS',
      'title' => 'お知らせ',
      'tag' => 'h2',
      'extra_class' => 'news__header'
    ]);
    ?>

    <!-- 検索フォーム（?q= に送信） -->
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
            $post_id      = get_the_ID();
            $permalink    = get_permalink();
            $date_machine = get_the_date('Y-m-d');
            $date_human   = get_the_date('Y.m.d');

            // タイトル（ハイライト）
            $title_raw  = get_the_title();
            $title_html = $q_word !== '' ? lp_news_highlight_html($title_raw, $q_word) : esc_html($title_raw);

            // バッジ
            $label = 'お知らせ';
            if ($post_type === 'post') {
              $cats = get_the_category();
              if (!empty($cats)) $label = $cats[0]->name;
            } else {
              $taxes = get_object_taxonomies('notice', 'names');
              if (!empty($taxes)) {
                foreach ($taxes as $tax) {
                  $terms = get_the_terms($post_id, $tax);
                  if ($terms && !is_wp_error($terms)) {
                    $label = $terms[0]->name;
                    break;
                  }
                }
              }
            }

            // 本文（全文）からヒット周辺スニペットを作る
            $plain = lp_news_plain_content($post_id);
            [$main_snip, $extra_snips] = lp_build_main_and_extra_snippets($plain, $q_word, 140, 60, 3);

            // ハイライト化
            $excerpt_html = $q_word !== '' ? lp_news_highlight_html($main_snip, $q_word) : esc_html($main_snip);
            $extra_htmls  = [];
            foreach ($extra_snips as $sn) {
              $extra_htmls[] = $q_word !== '' ? lp_news_highlight_html($sn, $q_word) : esc_html($sn);
            }
        ?>
            <li class="news__item">
              <a href="<?php echo esc_url($permalink); ?>" class="news__link">
                <img class="news__icon" src="<?php echo esc_url(get_theme_file_uri('assets/img/neonlogo-02.webp')); ?>" alt="">
                <div class="news__content">
                  <time datetime="<?php echo esc_attr($date_machine); ?>" class="news__date"><?php echo esc_html($date_human); ?></time>
                  <span class="news__label"><?php echo esc_html($label); ?></span>

                  <!-- タイトルの下に、ヒット箇所周辺の本文抜粋（常にこの順） -->
                  <p class="news__text"><?php echo $title_html; ?></p>

                  <?php if ($excerpt_html !== '') : ?>
                    <p class="news__excerpt"><?php echo $excerpt_html; ?></p>
                  <?php endif; ?>

                  <?php if (!empty($extra_htmls)) : ?>
                    <ul class="news__matches" aria-label="本文の他の一致箇所">
                      <?php foreach ($extra_htmls as $eh) : ?>
                        <li class="news__match"><?php echo $eh; ?></li>
                      <?php endforeach; ?>
                    </ul>
                  <?php endif; ?>
                </div>
              </a>
            </li>
          <?php endwhile;
          wp_reset_postdata();
        else : ?>
          <li class="news__empty" role="status" aria-live="polite">
            <p class="news__empty-text">
              <?php echo ($q_word !== '')
                ? '「' . esc_html($q_word) . '」を含むお知らせは見つかりませんでした。'
                : '現在、掲載中のお知らせはありません。'; ?>
            </p>
          </li>
        <?php endif; ?>
      </ul>
    </div>
    <?php if ($q_word !== '') : ?>
      <p class="news__return">
        <a href="<?php echo esc_url(get_permalink()); ?>">検索をクリアして一覧へ戻る</a>
      </p>
    <?php endif; ?>

    <?php if ($has_posts) :  ?>
      <?php
      $query_args = [];
      if ($q_word !== '') $query_args['q'] = $q_word;

      $big   = 999999999;
      $total = max(1, (int)$q->max_num_pages);

      $links = paginate_links([
        'base'         => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format'       => '?paged=%#%',
        'current'      => $paged,
        'total'        => $total,
        'mid_size'     => 2,
        'prev_text'    => '前へ',
        'next_text'    => '次へ',
        'type'         => 'list',
        'add_args'     => $query_args,
        'add_fragment' => '#news-index',
      ]);
      // ★ 総ページ数が1のときも「1」を表示（prev/nextは出ない）
      if (empty($links) && $total === 1) {
        $links = '<ul class="page-numbers"><li><span class="page-numbers current" aria-current="page">1</span></li></ul>';
      }
      ?>
      <?php if ($links) : ?>
        <nav class="news__pager" aria-label="ニュースのページ送り">
          <?php echo $links; ?>
        </nav>
      <?php endif; ?>
    <?php endif; // has_posts 
    ?>




  </div><!-- /.news__inner -->
</section>