<?php
/**
 * アクセスページ本体（セクション分割）
 * template-parts/page-access.php
 * 
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;
?>

<main class="page page--access" role="main">
  <?php
    // サブヒーロー
    get_template_part('template-parts/access-section', 'hero');

    // 本文（地図・住所・道順・連絡先）
    get_template_part('template-parts/access-section', 'body');

    // 共通CTA・ページトップ
    get_template_part('template-parts/section', 'contact-cta');
    get_template_part('includes/to-top');
  ?>
</main>
