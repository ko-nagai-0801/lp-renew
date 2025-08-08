<?php

/**
 * services section – BEM
 * template-parts/section-services.php
 */
if (! defined('ABSPATH')) {
  exit;
}
?>

<section id="services" class="services section section__card container" aria-labelledby="services__heading">

  <?php
  get_template_part(
    'components/section-header',
    null,
    [
      'id' => '', // 見出しID（任意）
      'sub' => 'Services', // 小見出し
      'title' => '事業内容', // メイン見出し
      'tag' => 'h2', // h1〜h6（省略可）
      'extra_class' => 'services__header' // 追加クラス（任意）
    ]
  );
  ?>

  <p class="services__catch section__catch">
    Web制作から軽作業まで——多様な業務をワンストップで請け負います。
  </p>

  <ul class="services__cards">
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
      <li class="services__card">
        <a href="<?php echo esc_url(home_url("/services/{$s['slug']}/")); ?>"
          class="services__link">
          <h3 class="services__card-title">
            <img src="<?php echo esc_url(get_theme_file_uri($s['icon'])); ?>"
              alt="" aria-hidden="true"> <?php echo esc_html($s['title']); ?>
          </h3>
          <p class="services__card-text"><?php echo esc_html($s['text']); ?></p>
          <span class="services__card-cta">詳しく見る</span>
        </a>
      </li>
    <?php endforeach; ?>

    <li class="services__card services__card--more">
      <a href="<?php echo esc_url(home_url('/services/#others')); ?>" class="services__link">
        <h3 class="services__card-title">
          <img src="<?php echo esc_url(get_theme_file_uri('img/icon-more.svg')); ?>"
            alt="" aria-hidden="true"> その他の業務
        </h3>
        <p class="services__card-text">
          リスト作成・データ収集・システム検証など<br>「こんなこと頼める？」もお気軽にご相談ください。
        </p>
        <span class="services__card-cta">事例を見る</span>
      </a>
    </li>
  </ul>

  <?php
  get_template_part(
    'components/cta',
    null,
    [
      'url' => home_url('/services/'),
      'label' => 'Services',
      'variant' => 'primary', // 'primary' or 'white'
      'extra_class' => 'services__cta' // 必要に応じて追加クラス
    ]
  );
  ?>

</section>