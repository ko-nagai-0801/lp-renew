<?php if (!defined('ABSPATH')) exit;

$hero_bg = get_the_post_thumbnail_url(null, 'full'); ?>
<section class="subhero subhero--about"<?php if ($hero_bg) echo ' style="background-image:url(' . esc_url($hero_bg) . ');"'; ?>>
  <div class="subhero__inner">
    <div class="subhero__content">
      <p class="subhero__sub">About Us</p>
      <h1 class="subhero__title">
        <span id="LiNE">ＬｉＮＥ</span>&nbsp;<span id="PARK">ＰＡＲＫ</span>について
      </h1>
    </div>
  </div>
</section>
