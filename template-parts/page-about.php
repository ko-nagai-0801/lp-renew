<?php
/**
 * Aboutページ本体
 * template-parts/page-about.php
 * 
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;
?>

<main class="page page--about" role="main">
    <?php
    get_template_part('template-parts/about-section', 'hero');
    get_template_part('template-parts/about-section', 'message');
    get_template_part('template-parts/about-section', 'vision');
    get_template_part('template-parts/about-section', 'company');
    get_template_part('template-parts/section', 'contact-cta'); // 共通CTA
    get_template_part('includes/to-top');      // ページトップへ
    ?>
</main>
