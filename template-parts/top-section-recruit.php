<?php

/**
 * Recruit section – BEM
 * template-parts/section-recruit.php
 */
if (! defined('ABSPATH')) {
  exit;
}
?>

<section id="recruit" class="parallax recruit section container-fluid" data-parallax-speed="0.4">

  <div class="recruit__inner">
    <div class="recruit__content">

      <?php
      get_template_part('components/section-header', null, [
        'sub'     => 'Recruit',
        'title'   => '採用情報',
        'variant' => 'recruit', //追加クラス
      ]);
      ?>

      <p class="recruit__catch section__catch">私たちと一緒に働きませんか？</p>

      <p class="recruit__text section__text">
        株式会社ＬｉＮＥ&nbsp;ＰＡＲＫでは、共により良い障害福祉の確立、発信、貢献を行っていけるメンバーを募集しております。
      </p>
      <p class="recruit__text section__text">
        ご見学もお気軽にお越しください。
      </p>

      <?php
      get_template_part(
        'components/cta-gradient',
        null,
        [
          'url' => home_url('/recruit/'),
          'label' => 'Recruit',
          'variant' => 'white', // 'primary' or 'white'
          'extra_class' => 'recruit__cta' // 必要に応じて追加クラス
        ]
      );
      ?>
    </div>

    <!-- <div class="recruit__visual">
      <img src="<?php echo esc_url(get_theme_file_uri('img/recruit-big-text.svg')); ?>"
        alt="採用情報"> -->
  </div>
  </div>
</section>