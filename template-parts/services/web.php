<?php

/**
 * Web制作（簡素版）
 * template-parts/services/web.php
 *
 * - HP、LPのご制作。
 * - デザインからコーディング、公開までお客様のニーズに沿ってご制作。
 * - サーバーの契約代行、ドメイン取得代行も承っております。
 * - 公開後の保守・運用もお任せください。
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;
?>

<main class="page page--services-web" role="main">
  <?php
  get_template_part('template-parts/services/web-section', 'hero');
  get_template_part('template-parts/services/web-section', 'points');
  get_template_part('template-parts/section', 'contact-cta'); // 共通CTA
  get_template_part('includes/to-top');  // ページトップへ
  ?>
</main>

