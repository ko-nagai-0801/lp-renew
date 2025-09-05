<?php

/**
 * 協力企業ページ用テンプレート本体
 * template-parts/page-associate.php
 * 
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;
?>

<main class="page page--associates" role="main">
  <?php
  // サブヒーロー
  get_template_part('template-parts/associates-section', 'hero');

  // 協力企業一覧
  get_template_part('template-parts/associates-section', 'list');


  // 共通CTA・ページトップ
  get_template_part('template-parts/section', 'contact-cta');
  ?>
</main>