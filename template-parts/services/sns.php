<?php

/**
 * template-parts/services/sns.php
 */
if (!defined('ABSPATH')) exit;
?>

<main class="page page--services-sns" role="main">
    <?php
    get_template_part('template-parts/services/sns-section', 'hero');
    get_template_part('template-parts/services/sns-section', 'points');
    get_template_part('template-parts/section', 'contact-cta');
    get_template_part('components/to-top');
    ?>
</main>