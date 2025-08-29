<?php
/**
 * Single Post (個別投稿)
 * theme root / single.php
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;

get_header();
get_template_part('template-parts/single', 'post');
get_footer();
