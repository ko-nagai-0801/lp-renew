<?php

/**
 * components/breadcrumbs.php
 * パンくず（schema.org BreadcrumbList 対応 / BEMクラス）
 *
 * 使い方例：
 * get_template_part('components/breadcrumbs', null, [
 *   'blog_label'         => 'ニュース一覧',
 *   'posts_index_slug'   => 'news',   // /news/ を投稿一覧として扱う
 *   'show_post_category' => false,    // 投稿詳細でカテゴリを出さない
 * ]);
 *
 * @package LP_WP_Theme
 */
if (!defined('ABSPATH')) exit;

/** -----------------------------------------
 * 引数（必要に応じて上書き可能）
 * -------------------------------------- */
$args = wp_parse_args($args ?? [], [
    'show_on_front'      => false,     // true なら TOP でも表示
    'home_label'         => 'ホーム',
    'blog_label'         => null,      // 未指定なら投稿一覧ページのタイトル or "ブログ"
    'class'              => 'breadcrumb',
    'container_class'    => 'container', // ラッパー（不要なら空文字）
    'posts_index_id'     => 0,         // 投稿一覧として扱いたい固定ページID（任意）
    'posts_index_slug'   => '',        // 同：スラッグ（例: 'news'）
    'show_post_category' => true,      // 投稿詳細でカテゴリを出す/出さない
    'skip_uncategorized' => true,      // “未分類” は省く
]);

// TOP では表示しない設定なら何も出さない
if (is_front_page() && !$args['show_on_front']) {
    return;
}

/** -----------------------------------------
 * 小さなユーティリティ
 * -------------------------------------- */
$push = function (&$items, $label, $url = null) {
    $items[] = ['label' => $label, 'url' => $url];
};

// 投稿一覧（「ニュース一覧」など）のURL/ラベルを決定
$get_posts_page = function () use ($args) {
    // 1) 明示ID
    if (!empty($args['posts_index_id'])) {
        $p = get_post((int) $args['posts_index_id']);
        if ($p && $p->post_status === 'publish') {
            return [
                'url'   => get_permalink($p),
                'label' => ($args['blog_label'] ?? get_the_title($p)),
            ];
        }
    }
    // 2) 明示スラッグ
    if (!empty($args['posts_index_slug'])) {
        $p = get_page_by_path(sanitize_title($args['posts_index_slug']));
        if ($p && $p->post_status === 'publish') {
            return [
                'url'   => get_permalink($p),
                'label' => ($args['blog_label'] ?? get_the_title($p)),
            ];
        }
    }
    // 3) 設定 > 表示設定 の「投稿ページ」
    $id = (int) get_option('page_for_posts');
    if ($id) {
        return [
            'url'   => get_permalink($id),
            'label' => ($args['blog_label'] ?? get_the_title($id)),
        ];
    }
    // 4) フォールバック：投稿タイプのアーカイブ or ホーム
    return [
        'url'   => (get_post_type_archive_link('post') ?: home_url('/')),
        'label' => ($args['blog_label'] ?? 'ブログ'),
    ];
};

// プライマリカテゴリ（Yoast/RankMath対応）→ なければ先頭カテゴリ
$get_primary_category = function ($post_id) {
    // Yoast
    $yoast_id = (int) get_post_meta($post_id, '_yoast_wpseo_primary_category', true);
    if ($yoast_id) {
        $t = get_term($yoast_id, 'category');
        if ($t && !is_wp_error($t)) return $t;
    }
    // Rank Math
    $rm_id = (int) get_post_meta($post_id, 'rank_math_primary_category', true);
    if ($rm_id) {
        $t = get_term($rm_id, 'category');
        if ($t && !is_wp_error($t)) return $t;
    }
    // フォールバック
    $cats = get_the_category($post_id);
    return $cats ? $cats[0] : null;
};

/** -----------------------------------------
 * パンくず配列の構築
 * -------------------------------------- */
$items = [];

// 1) HOME
$push($items, $args['home_label'], home_url('/'));

// 2) 各条件分岐で積む
if (is_home() && !is_front_page()) {
    // 投稿一覧（固定ページ割当 or 任意スラッグ）
    $blog = $get_posts_page();
    $push($items, $blog['label']); // 現在地
} elseif (is_singular('page')) {
    // 固定ページ：親を遡る
    $post_id   = get_queried_object_id();
    $ancestors = array_reverse(get_post_ancestors($post_id));
    foreach ($ancestors as $aid) {
        $push($items, get_the_title($aid), get_permalink($aid));
    }
    $push($items, get_the_title($post_id)); // 現在地
} elseif (is_singular()) {
    // 投稿 or カスタム投稿
    $post_id   = get_queried_object_id();
    $post_type = get_post_type($post_id);

    if ($post_type === 'post') {
        // 投稿詳細：投稿一覧を挟む
        $blog = $get_posts_page();
        $push($items, $blog['label'], $blog['url']);

        // カテゴリ（必要なら）
        if ($args['show_post_category']) {
            if ($term = $get_primary_category($post_id)) {
                // “未分類” を省く設定
                if (!($args['skip_uncategorized'] && $term->slug === 'uncategorized')) {
                    // 親カテゴリを上から順に
                    $parents = array_reverse(get_ancestors($term->term_id, 'category'));
                    foreach ($parents as $pid) {
                        $t = get_term($pid, 'category');
                        if ($t && !is_wp_error($t)) $push($items, $t->name, get_term_link($t));
                    }
                    // 当該カテゴリ
                    $push($items, $term->name, get_term_link($term));
                }
            }
        }
    } else {
        // CPT：アーカイブを挟む（ある場合）
        $obj = get_post_type_object($post_type);
        if ($obj && !empty($obj->has_archive)) {
            $push($items, $obj->labels->name, get_post_type_archive_link($post_type));
        }
    }
    // 現在の記事タイトル
    $push($items, get_the_title($post_id));
} elseif (is_category() || is_tag() || is_tax()) {
    // タームアーカイブ：親を遡る
    $term = get_queried_object();
    if ($term && $term->parent) {
        $parents = array_reverse(get_ancestors($term->term_id, $term->taxonomy));
        foreach ($parents as $pid) {
            $t = get_term($pid, $term->taxonomy);
            if ($t && !is_wp_error($t)) $push($items, $t->name, get_term_link($t));
        }
    }
    $push($items, single_term_title('', false)); // 現在地
} elseif (is_post_type_archive()) {
    $obj = get_post_type_object(get_query_var('post_type'));
    $push($items, $obj ? $obj->labels->name : post_type_archive_title('', false));
} elseif (is_search()) {
    $push($items, '「' . esc_html(get_search_query()) . '」の検索結果');
} elseif (is_author()) {
    $push($items, '投稿者: ' . esc_html(get_the_author_meta('display_name', get_query_var('author'))));
} elseif (is_date()) {
    if (is_day()) {
        $push($items, get_the_time('Y'), get_year_link(get_the_time('Y')));
        $push($items, get_the_time('F'), get_month_link(get_the_time('Y'), get_the_time('m')));
        $push($items, get_the_time('j') . '日');
    } elseif (is_month()) {
        $push($items, get_the_time('Y'), get_year_link(get_the_time('Y')));
        $push($items, get_the_time('F'));
    } else {
        $push($items, get_the_time('Y'));
    }
} elseif (is_404()) {
    $push($items, 'ページが見つかりません');
}

// 3) ページ送り（2ページ目以降）
$paged = max((int) get_query_var('paged'), (int) get_query_var('page'));
if ($paged > 1) {
    $push($items, 'ページ ' . $paged);
}

/** -----------------------------------------
 * 出力（BEM + microdata）
 * -------------------------------------- */
$wrapper_open  = $args['container_class'] ? '<div class="' . esc_attr($args['container_class']) . '">' : '';
$wrapper_close = $args['container_class'] ? '</div>' : '';

echo '<nav class="' . esc_attr($args['class']) . '" aria-label="breadcrumb">';
echo $wrapper_open;
echo '<ol class="breadcrumb__list" itemscope itemtype="https://schema.org/BreadcrumbList">';

$total = count($items);
foreach ($items as $i => $item) {
    $pos    = $i + 1;
    $isLast = ($i === $total - 1);

    echo '<li class="breadcrumb__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';

    if (!$isLast && !empty($item['url'])) {
        echo '<a class="breadcrumb__link" href="' . esc_url($item['url']) . '" itemprop="item"><span itemprop="name">' . esc_html($item['label']) . '</span></a>';
    } else {
        echo '<span class="breadcrumb__current" aria-current="page" itemprop="name">' . esc_html($item['label']) . '</span>';
    }

    echo '<meta itemprop="position" content="' . esc_attr($pos) . '">';
    echo '</li>';
}

echo '</ol>';
echo $wrapper_close;
echo '</nav>';
