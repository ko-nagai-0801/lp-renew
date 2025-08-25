<?php

/**
 * Join Us 利用者募集（components 使用）
 * template-parts/join-section-details.php
 */
if (!defined('ABSPATH')) exit;
?>

<section class="join section container">
  <div class="join__inner">

    <?php
    // 1) 見出し（外側で出力）
    get_template_part('components/section-header', null, [
      'id'          => 'recruit-spec', // ← このIDをテーブルへ渡す
      'sub'         => 'Join Us',
      'title'       => '利用者募集',
      'tag'         => 'h2',
      'variant'     => 'join',
      'extra_class' => 'join__header',  // 任意
    ]);

    // 2) テーブル（見出し引数は一切不要）
    get_template_part('components/info-table', null, [
      'rows' => [
        [
          'label' => '対象',
          'value' => '就労継続支援B型のご利用を検討中の方。<br>体調や生活リズムに不安がある場合もご相談ください。'
        ],
        [
          'label' => '作業内容',
          'value' => '軽作業、PC作業 ほか。ご希望やペースに合わせ、スタッフが個別にサポートします。'
        ],
        [
          'label' => '開所日・時間',
          'value' => '平日 9:00〜14:30 ※ご希望に合わせた通所も可能'
        ],
        [
          'label' => '所在地',
          'value' => '〒120-0005<br>東京都足立区綾瀬 2-27-4　D1 AYASE 2F<br>最寄り駅：JR綾瀬駅 東口から徒歩3分'
        ],
        [
          'label' => '見学・体験',
          'value' => '絶賛募集中です。施設の雰囲気や作業内容を実際にご確認いただけます。'
        ],
      ],
      'caption'         => '募集事項',
      'label_width'     => '30%',
      'extra_class'     => 'join__table',
      'id'              => 'recruit-spec-table', // 任意
      'aria_labelledby' => 'recruit-spec', // ← 見出しIDと関連付け（推奨）
    ]);

    ?>

  </div>
</section>