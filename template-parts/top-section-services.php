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

  <?php
  $services = [
    [
      'slug'  => 'web',
      'icon'  => 'bi-code-slash', // ← ここをクラス名に
      'title' => 'Web制作',
      'text'  => 'UX 設計から公開後の保守まで、成果につながるサイトを構築。',
      'bg'    => 'assets/img/top-services/bg-web.webp',
      'tone'  => 'light',
    ],
    [
      'slug'  => 'design',
      'icon'  => 'bi-brush',
      'title' => 'デザイン制作',
      'text'  => 'ポスター・バナー・名刺など、ブランドを彩るビジュアルをご提案。',
      'bg'    => 'assets/img/top-services/bg-design.webp',
      'tone'  => 'light',
    ],
    [
      'slug'  => 'sns',
      'icon'  => 'bi-share',  // または 'bi-chat-dots', 'bi-megaphone', 'bi-people' など
      'title' => 'SNS運用',
      'text'  => 'X・Instagram・TikTokなど、投稿から分析まで、フォロワー増加をお手伝い。',
      'bg'    => 'assets/img/top-services/bg-sns.webp',
      'tone'  => 'light',
    ],
    [
      'slug'  => 'lightwork',
      'icon'  => 'bi-box-seam',
      'title' => '軽作業',
      'text'  => '封入・梱包・検品などバックオフィス業務を正確・迅速に代行。',
      'bg'    => 'assets/img/top-services/bg-lightwork.webp',
      'tone'  => 'light',
    ],
    [
      'slug'  => 'others',
      'icon'  => 'bi-stars',
      'title' => 'その他の業務',
      'text'  => 'リスト作成やデータ収集、検証など、「こんなことも？」にお応えします。',
      'bg'    => 'assets/img/top-services/bg-others.webp',
      'tone'  => 'light',
    ],
  ];
  ?>


  <ul class="services__cards">
    <?php foreach ($services as $s) :
      $bg_url = get_theme_file_uri($s['bg'] ?? '');
      $tone   = $s['tone'] ?? 'dark';
      $icon   = $s['icon'] ?? '';
      $is_bi  = (0 === strpos($icon, 'bi-')); // PHP7互換
    ?>
      <li class="services__card">
        <a
          href="<?php echo esc_url(home_url("/services/{$s['slug']}/")); ?>"
          class="services__link services__link--<?php echo esc_attr($tone); ?>"
          style="--card-bg:url('<?php echo esc_url($bg_url); ?>')">
          <h3 class="services__card-title">
            <span class="services__icon" aria-hidden="true">
              <?php if ($is_bi): ?>
                <i class="bi <?php echo esc_attr($icon); ?>"></i>
              <?php else: ?>
                <img src="<?php echo esc_url(get_theme_file_uri($icon)); ?>" alt="">
              <?php endif; ?>
            </span>
            <?php echo esc_html($s['title']); ?>
          </h3>
          <p class="services__card-text"><?php echo esc_html($s['text']); ?></p>
          <!-- <span class="services__card-cta">詳しく見る</span> -->
        </a>
      </li>
    <?php endforeach; ?>
  </ul>


  <?php
  get_template_part(
    'components/cta-gradient',
    null,
    [
      'url' => home_url('/services/'),
      'label' => 'Services',
      'variant' => 'primary', // 'primary' or 'white'
      'extra_class' => 'services__cta' // 追加クラス
    ]
  );
  ?>

</section>