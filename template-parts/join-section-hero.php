<?php

/**
 * Join Us サブヒーロー
 * template-parts/join-section-hero.php
 */
if (!defined('ABSPATH')) exit;

$hero_bg = get_the_post_thumbnail_url(null, 'full'); ?>
<section class="subhero subhero--join"<?php if ($hero_bg) echo ' style="background-image:url(' . esc_url($hero_bg) . ');"'; ?>>
  <div class="subhero__inner">
    <div class="subhero__content">
      <p class="subhero__sub">Join Us</p>
      <h1 class="subhero__title">
        利用者募集
      </h1>
    </div>
  </div>
</section>