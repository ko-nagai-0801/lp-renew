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
    get_template_part( 'template-parts/section', 'hero' );
    get_template_part('template-parts/section', 'about');
    get_template_part('template-parts/section', 'business');
    get_template_part('template-parts/section', 'recruit');
    get_template_part('template-parts/section', 'office');
    get_template_part('template-parts/section', 'news');
    get_template_part('template-parts/section', 'contact-cta');
    ?>
</main>


