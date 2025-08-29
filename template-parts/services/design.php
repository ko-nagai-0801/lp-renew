<?php
/**
 * template-parts/services/design.php
 * （ヘッダー/フッターは親テンプレートで呼ぶ）
 */
if (!defined('ABSPATH')) exit;
?>
<main class="page page--services-design" role="main">
  <?php
  get_template_part('template-parts/services/design-section', 'hero');
  get_template_part('template-parts/services/design-section', 'points');
  get_template_part('template-parts/section', 'contact-cta');
  get_template_part('includes/to-top');
  ?>
</main>
