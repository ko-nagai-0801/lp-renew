<?php

/**
 * パンくず（schema.org BreadcrumbList 対応 / BEMクラス）
 * components/breadcrumbs.php
 *
 * @package LP_WP_Theme
 * 更新履歴:
 * - 1.3.1 (2025-09-03): タイトルが空のとき "No Title" を使用。
 * - 1.3.0: from_page / from_q を解釈して「ページ N」リンクを挿入。
 */
if (!defined('ABSPATH')) exit;

$args = wp_parse_args($args ?? [], [
    'show_on_front'               => false,
    'home_label'                  => 'ホーム',
    'blog_label'                  => null,
    'class'                       => 'breadcrumb',
    'container_class'             => 'container',
    'posts_index_id'              => 0,
    'posts_index_slug'            => '',
    'show_post_category'          => true,
    'skip_uncategorized'          => true,
    'paged_index_label_clickable' => true,
    'show_paged_suffix'           => true,
]);

if (is_front_page() && !$args['show_on_front']) return;

// util
$push = function (&$items, $label, $url = null) {
    $items[] = ['label' => $label, 'url' => $url];
};

// ▼ 空タイトルに "No Title" を返すヘルパ
$title_or = function ($post_id = null, $fallback = 'No Title') {
    $t = trim(wp_strip_all_tags(get_the_title($post_id)));
    return ($t === '') ? $fallback : $t;
};

// 一覧ページ情報
$get_posts_page = function () use ($args) {
    if (!empty($args['posts_index_id'])) {
        $p = get_post((int)$args['posts_index_id']);
        if ($p && $p->post_status === 'publish') {
            return ['id' => (int)$p->ID, 'url' => get_permalink($p), 'label' => ($args['blog_label'] ?? get_the_title($p))];
        }
    }
    if (!empty($args['posts_index_slug'])) {
        $p = get_page_by_path(sanitize_title($args['posts_index_slug']));
        if ($p && $p->post_status === 'publish') {
            return ['id' => (int)$p->ID, 'url' => get_permalink($p), 'label' => ($args['blog_label'] ?? get_the_title($p))];
        }
    }
    $id = (int)get_option('page_for_posts');
    if ($id) return ['id' => $id, 'url' => get_permalink($id), 'label' => ($args['blog_label'] ?? get_the_title($id))];
    return ['id' => 0, 'url' => (get_post_type_archive_link('post') ?: home_url('/')), 'label' => ($args['blog_label'] ?? 'ブログ')];
};

// 指定ページ番号の一覧URL（#news-index付き）
$build_blog_paged_url = function (int $n, string $base, string $q = ''): string {
    $u = $base;
    if ($n >= 2) {
        $u = get_option('permalink_structure')
            ? trailingslashit($base) . user_trailingslashit('page/' . $n, 'paged')
            : add_query_arg('paged', $n, $base);
    }
    if ($q !== '') $u = add_query_arg('q', $q, $u);
    return $u . '#news-index';
};

// プライマリカテゴリ
$get_primary_category = function ($post_id) {
    $yoast_id = (int)get_post_meta($post_id, '_yoast_wpseo_primary_category', true);
    if ($yoast_id) {
        $t = get_term($yoast_id, 'category');
        if ($t && !is_wp_error($t)) return $t;
    }
    $rm_id = (int)get_post_meta($post_id, 'rank_math_primary_category', true);
    if ($rm_id) {
        $t = get_term($rm_id, 'category');
        if ($t && !is_wp_error($t)) return $t;
    }
    $cats = get_the_category($post_id);
    return $cats ? $cats[0] : null;
};

// 状態判定
$items         = [];
$blog          = $get_posts_page();
$q_param       = isset($_GET['q']) ? trim((string)wp_unslash($_GET['q'])) : '';
$is_posts_home = (is_home() && !is_front_page());
$is_posts_page = (is_singular('page') && !empty($blog['id']) && (int)get_queried_object_id() === (int)$blog['id']);
$is_index_q_search = ($q_param !== '') && ($is_posts_home || $is_posts_page);
$paged = max((int)get_query_var('paged'), (int)get_query_var('page'));

// シングルからの戻り情報
$from_page = isset($_GET['from_page']) ? max(0, (int)$_GET['from_page']) : 0;
$from_q    = isset($_GET['from_q'])    ? sanitize_text_field(wp_unslash($_GET['from_q'])) : '';

// パンくず構築
$push($items, $args['home_label'], home_url('/'));

if ($is_index_q_search) {
    $push($items, ($blog['label'] ?? '一覧'), ($blog['url'] ?? home_url('/')));
    $push($items, '検索結果');
} elseif ($is_posts_home) {
    if ($paged > 1) {
        $push($items, ($blog['label'] ?? '一覧'), !empty($args['paged_index_label_clickable']) ? ($blog['url'] ?? home_url('/')) : null);
        if (!empty($args['show_paged_suffix'])) $push($items, 'ページ ' . $paged);
    } else {
        $push($items, ($blog['label'] ?? '一覧'));
    }
} elseif ($is_posts_page) {
    if ($paged > 1) {
        $push($items, ($blog['label'] ?? '一覧'), !empty($args['paged_index_label_clickable']) ? ($blog['url'] ?? home_url('/')) : null);
        if (!empty($args['show_paged_suffix'])) $push($items, 'ページ ' . $paged);
    } else {
        $push($items, ($blog['label'] ?? '一覧'));
    }
} elseif (is_singular('page')) {
    $post_id   = get_queried_object_id();
    $ancestors = array_reverse(get_post_ancestors($post_id));
    foreach ($ancestors as $aid)  $push($items, $title_or($aid), get_permalink($aid));  // ← フォールバック使用
    $push($items, $title_or($post_id));                                                 // ← フォールバック使用
} elseif (is_singular()) {
    $post_id   = get_queried_object_id();
    $post_type = get_post_type($post_id);

    if ($post_type === 'post') {
        $push($items, ($blog['label'] ?? '一覧'), ($blog['url'] ?? home_url('/')));
        if ($from_page >= 2) {
            $page_url = $build_blog_paged_url($from_page, ($blog['url'] ?? home_url('/')), $from_q);
            $push($items, 'ページ ' . $from_page, $page_url);
        }
        if (!empty($args['show_post_category'])) {
            if ($term = $get_primary_category($post_id)) {
                if (!($args['skip_uncategorized'] && $term->slug === 'uncategorized')) {
                    $parents = array_reverse(get_ancestors($term->term_id, 'category'));
                    foreach ($parents as $pid) {
                        $t = get_term($pid, 'category');
                        if ($t && !is_wp_error($t)) $push($items, $t->name, get_term_link($t));
                    }
                    $push($items, $term->name, get_term_link($term));
                }
            }
        }
    } else {
        $obj = get_post_type_object($post_type);
        if ($obj && !empty($obj->has_archive)) $push($items, $obj->labels->name, get_post_type_archive_link($post_type));
    }

    // 現在地（タイトルはフォールバック）
    $push($items, $title_or($post_id));
} elseif (is_category() || is_tag() || is_tax()) {
    $term = get_queried_object();
    if ($term && $term->parent) {
        $parents = array_reverse(get_ancestors($term->term_id, $term->taxonomy));
        foreach ($parents as $pid) {
            $t = get_term($pid, $term->taxonomy);
            if ($t && !is_wp_error($t)) $push($items, $t->name, get_term_link($t));
        }
    }
    $push($items, single_term_title('', false));
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

// 末尾「ページ n」付与（一覧ページは各分岐で処理済み）
if ($paged > 1 && !$is_index_q_search && !$is_posts_home && !$is_posts_page && !empty($args['show_paged_suffix'])) {
    $push($items, 'ページ ' . $paged);
}

// 出力
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
