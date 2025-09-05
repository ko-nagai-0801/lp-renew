<?php

/**
 * Contact Page 本体
 * Template Post Type: page
 * @package LP_WP_Theme
 * @since 1.0.0
 * 
 * template-parts/page-contact.php
 */
if (!defined('ABSPATH')) exit;
?>

<main class="page page--contact" role="main">
    <?php
    // サブヒーロー
    get_template_part('template-parts/contact-section', 'hero');

    // フォーム
    get_template_part('template-parts/contact-section', 'form');
    ?>
</main>