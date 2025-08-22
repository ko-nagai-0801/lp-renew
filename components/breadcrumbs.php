<?php

/**
 * components/breadcrumbs.php
 * TOP 以外で表示するパンくず。schema.org BreadcrumbList 対応。
 * BEM: .breadcrumb / .breadcrumb__list / .breadcrumb__item / .breadcrumb__link / .breadcrumb__current
 */
if (!defined('ABSPATH')) exit;

$args = wp_parse_args($args ?? [], [
    'show_on_front'   => false, // true で TOP でも表示
    'home_label'      => 'ホーム',
    'blog_label'      => null, // 未指定なら投稿ページタイトル or "ブログ"
    'class'           => 'breadcrumb',
    'container_class' => 'container', // ラッパー（不要なら空文字）
]);

// TOP では表示しない
if (is_front_page() && !$args['show_on_front']) {
    return;
}

/** ユーティリティ */
$push = function (&$items, $label, $url = null) {
    $items[] = ['label' => $label, 'url' => $url];
};

$get_posts_page = function () use ($args) {
    $id = (int) get_option('page_for_posts');
    if ($id) return ['url' => get_permalink($id), 'label' => get_the_title($id)];
    return ['url' => get_post_type_archive_link('post'), 'label' => ($args['blog_label'] ?? 'ブログ')];
};

$get_primary_category = function ($post_id) {
    // 1) Yoast（_yoast_wpseo_primary_category）
    $primary_id = (int) get_post_meta($post_id, '_yoast_wpseo_primary_category', true);
    if ($primary_id) {
        $term = get_term($primary_id, 'category');
        if ($term && !is_wp_error($term)) return $term;
    }

    // 2) Rank Math（rank_math_primary_category）
    $rank_math_id = (int) get_post_meta($post_id, 'rank_math_primary_category', true);
    if ($rank_math_id) {
        $term = get_term($rank_math_id, 'category');
        if ($term && !is_wp_error($term)) return $term;
    }

    // 3) フォールバック：最初のカテゴリ
    $cats = get_the_category($post_id);
    return $cats ? $cats[0] : null;
};


// ------------------------------------------------------------
// パンくず要素の構築
// ------------------------------------------------------------
$items = [];

// 1) ホーム
$push($items, $args['home_label'], home_url('/'));

if (is_home() && !is_front_page()) {
    // 投稿一覧（固定ページ割当 or ブログ）
    $blog = $get_posts_page();
    $push($items, $blog['label']); // 現在地
} elseif (is_singular('page')) {
    $post_id   = get_queried_object_id();
    $ancestors = array_reverse(get_post_ancestors($post_id));
    foreach ($ancestors as $aid) {
        $push($items, get_the_title($aid), get_permalink($aid));
    }
    $push($items, get_the_title($post_id)); // 現在地
} elseif (is_singular()) {
    $post_id   = get_queried_object_id();
    $post_type = get_post_type($post_id);
    if ($post_type === 'post') {
        $blog = $get_posts_page();
        $push($items, $blog['label'], $blog['url']);
        if ($term = $get_primary_category($post_id)) {
            // カテゴリ親を辿る
            $parents = array_reverse(get_ancestors($term->term_id, 'category'));
            foreach ($parents as $pid) {
                $t = get_term($pid, 'category');
                if ($t && !is_wp_error($t)) $push($items, $t->name, get_term_link($t));
            }
            $push($items, $term->name, get_term_link($term));
        }
    } else {
        // カスタム投稿タイプ
        $obj = get_post_type_object($post_type);
        if ($obj && !empty($obj->has_archive)) {
            $push($items, $obj->labels->name, get_post_type_archive_link($post_type));
        }
    }
    $push($items, get_the_title($post_id)); // 現在地
} elseif (is_category() || is_tag() || is_tax()) {
    $term = get_queried_object();
    // タクソノミーの親を辿る
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

// ページ送り（2ページ目以降）
$paged = max((int) get_query_var('paged'), (int) get_query_var('page'));
if ($paged > 1) {
    $push($items, 'ページ ' . $paged);
}

// ------------------------------------------------------------
// 出力
// ------------------------------------------------------------
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
    echo '<meta itemprop="position" content="' . $pos . '">';
    echo '</li>';
}
echo '</ol>';
echo $wrapper_close;
echo '</nav>';
