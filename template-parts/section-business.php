<?php

/**
 * Business section – BEM
 * template-parts/section-business.php
 */
if (! defined('ABSPATH')) {
  exit;
}
?>

<section id="business" class="business" aria-labelledby="business__heading">

  <header class="business__header">
    <p class="business__sub">Business</p>
    <h2 id="business__heading" class="business__title">事業内容</h2>
    <p class="business__lead">
      Web 制作から軽作業まで——多様な業務をワンストップで請け負います。
    </p>
  </header>

  <ul class="business__cards">
    <?php
    $services = [
      [
        'slug' => 'web',
        'icon' => 'img/icon-web.png',
        'title' => 'Web制作',
        'text' => 'UX 設計から公開後の保守まで、成果につながるサイトを構築。'
      ],
      [
        'slug' => 'design',
        'icon' => 'img/icon-design.svg',
        'title' => 'デザイン制作',
        'text' => 'ポスター・バナー・名刺など、ブランドを彩るビジュアルをご提案。'
      ],
      [
        'slug' => 'movie',
        'icon' => 'img/icon-movie.svg',
        'title' => '動画編集',
        'text' => 'SNS／YouTube 向け動画を企画〜編集までワンストップ対応。'
      ],
      [
        'slug' => 'lightwork',
        'icon' => 'img/icon-box.svg',
        'title' => '軽作業',
        'text' => '封入・梱包・検品などバックオフィス業務を正確・迅速に代行。'
      ],
    ];

    foreach ($services as $s) : ?>
      <li class="business__card">
        <a href="<?php echo esc_url(home_url("/business/{$s['slug']}/")); ?>"
          class="business__link">
          <h3 class="business__card-title">
            <img src="<?php echo esc_url(get_theme_file_uri($s['icon'])); ?>"
              alt="" aria-hidden="true"> <?php echo esc_html($s['title']); ?>
          </h3>
          <p class="business__card-text"><?php echo esc_html($s['text']); ?></p>
          <span class="business__card-cta">詳しく見る</span>
        </a>
      </li>
    <?php endforeach; ?>

    <li class="business__card business__card--more">
      <a href="<?php echo esc_url(home_url('/business/#others')); ?>" class="business__link">
        <h3 class="business__card-title">
          <img src="<?php echo esc_url(get_theme_file_uri('img/icon-more.svg')); ?>"
            alt="" aria-hidden="true"> その他の業務
        </h3>
        <p class="business__card-text">
          リスト作成・データ収集・システム検証など<br>「こんなこと頼める？」もお気軽にご相談ください。
        </p>
        <span class="business__card-cta">事例を見る</span>
      </a>
    </li>
  </ul>

  <div class="business__cta">
    <a href="<?php echo esc_url(home_url('/business/')); ?>" class="button button--primary">
      すべてのサービスを見る <i class="fa-solid fa-angle-right" aria-hidden="true"></i>
    </a>
  </div>

</section>