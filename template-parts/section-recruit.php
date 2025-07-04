<?php

/**
 * Recruit section – BEM
 * template-parts/section-recruit.php
 */
if (! defined('ABSPATH')) {
  exit;
}
?>

<section id="recruit" class="recruit">
  <div class="recruit__bg"></div>

  <div class="recruit__inner">
    <div class="recruit__content">

      <header class="recruit__header">
        <h2 class="recruit__title">
          <span class="recruit__sub">Recruit</span>採用情報
        </h2>
      </header>

      <p class="recruit__message">私たちと一緒に働きませんか？</p>

      <p class="recruit__text">
        株式会社ＬｉＮＥ&nbsp;ＰＡＲＫでは、共により良い障害福祉の確立、発信、貢献を行っていける
        メンバーを募集しております。<br>ご見学もお気軽にお越しください。
      </p>

      <div class="recruit__link">
        <a href="<?php echo esc_url(home_url('/recruit/')); ?>" class="button button--white">
          View&nbsp;More
        </a>
      </div>
    </div>

    <div class="recruit__visual">
      <img src="<?php echo esc_url(get_theme_file_uri('img/recruit-big-text.svg')); ?>"
        alt="採用情報">
    </div>
  </div>
</section>