<?php

/**
 * Services Index ページ用テンプレート本体（任意順＝menu_orderで並べる）
 * template-parts/page-services-index.php
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 *
 * 更新履歴:
 * - 1.1.0 (2025-09-08): 一覧のソートを menu_order ASC に固定（任意並び運用に最適化）。
 * - 1.0.0: 初版
 */
if (!defined('ABSPATH')) exit;
?>

<main class="page page--services-index" role="main">
    <?php
    // サブヒーロー
    get_template_part('template-parts/services-index-section', 'hero');

    // サービス一覧（★並び＝menu_orderの昇順に固定）
    // 子ページの「クイック編集 → 順序」または Simple Page Ordering（プラグイン）でドラッグ並べ替え
    get_template_part('template-parts/services-index-section', 'list', [
        'parent_id'      => get_the_ID(),
        'sub'            => 'Services',
        'title'          => get_the_title(),
        'catch'          => has_excerpt() ? get_the_excerpt() : '',
        'title_tag'      => 'h2', // 見出しタグの変更可
        'posts_per_page' => -1, // 表示件数
        'empty_text'     => '公開中のサービスはありません。', // 空時メッセージ
        'orderby'        => ['menu_order' => 'ASC'], // ★任意順（menu_order）に統一
        // 'manual_ids'   => [123,456,789], // ←必要ならpost__in順にする拡張を使う時だけ
    ]);

    // 共通CTA
    get_template_part('template-parts/section', 'contact-cta');
    ?>
</main>