<?php
/**
 * TOPページ利用者募集セクション
 * template-parts/top-section-join.php
 * 
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (! defined('ABSPATH')) { exit; }
?>

<section id="join" class="parallax join section container-fluid" data-parallax-speed="0.4">

  <div class="join__inner">
    <div class="join__content">

      <?php
      get_template_part('components/section-header', null, [
        'sub'     => 'Join Us',
        'title'   => '利用者募集',
        'variant' => 'join', // 既存スタイル流用
      ]);
      ?>

      <p class="join__catch section__catch">“できる”を少しずつ、確実に。</p>

      <p class="join__text section__text">
        当事業所では本人の希望に合わせ、負担の少ない作業からスタートできます。スタッフと一緒に目標を決め、軽作業・PC作業などでスキルと生活リズムを整えていきます。
      </p>
      <p class="join__text section__text">
        見学・体験はいつでもOK。気になる点はお気軽にご相談ください。
      </p>

      <?php
      get_template_part(
        'components/cta-ghost',
        null,
        [
          'url' => home_url('/join/'), // 必要に応じて案内ページのURLへ変更
          'label' => 'Join Us',
          'variant' => 'white',
          'extra_class' => 'join__cta'
        ]
      );
      ?>
    </div>
  </div>
</section>
