<?php
/**
 * フロントページ用テンプレート
 * template-parts/front-page.php
 * 
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (! defined('ABSPATH')) {
    exit;
}
?>
<main class="isTop">
    <?php
    get_template_part('template-parts/top-section', 'hero' );
    get_template_part('template-parts/top-section', 'about');
    get_template_part('template-parts/top-section', 'services');
    get_template_part('template-parts/top-section', 'join');
    get_template_part('template-parts/top-section', 'office');
    get_template_part('template-parts/top-section', 'news');
    get_template_part('template-parts/section', 'contact-cta');
    ?>
</main>


