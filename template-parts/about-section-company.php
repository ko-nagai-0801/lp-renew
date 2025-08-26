<?php

/**
 * About 会社概要セクション
 * template-parts/about-section-company.php
 * 
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;
?>

<section class="section company">
  <div class="container">
    <div class="company__content">
      <?php
      get_template_part('components/section-header', null, [
        'id'          => 'company-outline',
        'sub'         => 'Company',
        'title'       => '会社概要',
        'tag'         => 'h2',
        'variant'     => 'about',
        'extra_class' => 'about__header',
      ]);

      get_template_part('components/info-table', null, [
        'rows' => [
          ['label' => '会社名',     'value' => '株式会社ＬｉＮＥ ＰＡＲＫ'],
          ['label' => '所在地',     'value' => '東京都足立区綾瀬2-27-4 D1 AYASE 2F'],
          ['label' => '代表取締役', 'value' => '大久保　和樹'],
          ['label' => '設立',       'value' => '2021年3月3日'],
          ['label' => '電話番号',   'value' => '<a href="tel:0344005584">03-4400-5584</a>'],
          ['label' => 'お問い合わせ', 'value' => '<a href="mailto:info@linepark.co.jp">info@linepark.co.jp</a>'],
          [
            'label' => '事業内容',
            'value' => 'Webサイト、Webデザインの制作。<br>データ収集・リスト化。<br>その他、上記以外のお仕事や、福祉に関するコンサルティングやディレクション（お悩み相談やお手伝い）。'
          ],
        ],
        'caption'         => '会社概要',
        'label_width'     => '30%',
        'extra_class'     => 'about__table',
        'id'              => 'company-outline-table',
        'aria_labelledby' => 'company-outline',
      ]);

      ?>
    </div>
  </div>
</section>