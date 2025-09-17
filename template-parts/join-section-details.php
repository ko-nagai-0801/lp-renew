<?php

/**
 * 利用者募集（カード型 + SVG/サムネ）
 * template-parts/join-section-details.php
 *
 * @package LP_WP_Theme
 * @since 1.2.0
 *
 * 更新履歴:
 * - 1.2.0: 各カードへ media と accent を追加
 * - 1.1.0: カードUI版
 */
if (!defined('ABSPATH')) exit;

// 検討中の方 画像
$img_subject  = get_theme_file_uri('assets/img/join/subject.webp');
// 作業内容 画像
$img_work  = get_theme_file_uri('assets/img/join/work.webp');
// 開所時間 画像
$img_datetime  = get_theme_file_uri('assets/img/join/datetime.webp');
// 所在地 画像
$img_access  = get_theme_file_uri('assets/img/join/access.webp');
// 見学・体験 画像
$img_visit = get_theme_file_uri('assets/img/join/visit.webp');

/* アクセスページの内部URLを取得（slug が 'access' の固定ページ想定）
   - あれば get_permalink() を使用
   - 見つからない場合は home_url('/access/') をフォールバック  */
$access_url = '';
if (function_exists('get_page_by_path')) {
  $access_page = get_page_by_path('access'); // ← アクセスページのスラッグ
  if ($access_page) {
    $access_url = get_permalink($access_page);
  }
}
if (!$access_url) {
  $access_url = home_url('/access/'); // 念のためのフォールバック
}
/* 問い合わせページの内部URLを取得（slug が 'contact' の固定ページ想定）
   - 上記の処理のcontact版  */
$contact_url = '';
if (function_exists('get_page_by_path')) {
  $contact_page = get_page_by_path('contact');
  if ($contact_page) {
    $contact_url = get_permalink($contact_page);
  }
}
if (!$contact_url) {
  $contact_url = home_url('/contact/'); // 念のためのフォールバック
}


$items = [
  [
    'media'  => [
      'type'   => 'img',
      'src'    => $img_subject,
      'alt'    => '対象の方',
      'width'  => 1200,
      'height' => 800,
    ],
    'icon'   => 'people-fill',
    'label'  => '対象',
    'title'  => 'B型の利用を検討中の方',
    'desc'   => '体調や生活リズムに不安がある場合もOK！<br>お気軽にご相談ください。',
    'accent' => '#4e8cff',
  ],
  [
    'media'  => [
      'type'   => 'img',
      'src'    => $img_work,
      'alt'    => '作業の様子',
      'width'  => 1200,
      'height' => 800,
    ],
    'icon'   => 'pc-display',
    'label'  => '作業内容',
    'title'  => '軽作業・PC作業が中心',
    'desc'   => 'ご希望やペースに合わせてスタッフがサポート。無理なく続けられます。',
    'accent' => '#00a5c9',
  ],
  [
    'media'  => [
      'type'   => 'img',
      'src'    => $img_datetime,
      'alt'    => '開所日・時間',
      'width'  => 1200,
      'height' => 800,
    ],
    'icon'   => 'clock',
    'label'  => '開所日・時間',
    'title'  => '平日 9:00–14:30',
    'desc'   => 'ご希望に合わせた通所も可能です。<br>まずは相談ください！',

    'accent' => '#3b6ea5',
  ],
  [
    'media'  => [
      'type'   => 'img',
      'src'    => $img_access,
      'alt'    => 'アクセス',
      'width'  => 1200,
      'height' => 800,
    ],
    'icon'   => 'geo-alt-fill',
    'label'  => '所在地',
    'title'  => 'JR綾瀬駅 東口 徒歩3分',
    'desc'   => '〒120-0005<br>東京都足立区綾瀬2-27-4<br>D1 AYASE 2F',
    'link'   => [
      'url'  => $access_url,
      'text' => 'アクセスページを見る',
    ],
    'accent' => '#ff7a4a',
  ],
  [
    'media'  => [
      'type'   => 'img',
      'src'    => $img_visit,
      'alt'    => '見学の様子',
      'width'  => 1200,
      'height' => 800,
    ],
    'icon'   => 'emoji-smile',
    'label'  => '見学・体験',
    'title'  => '絶賛募集中！',
    'desc'   => '施設の雰囲気や作業内容を実際にご確認いただけます。<br>お気軽にお問い合わせください。',
    'link'   => [
      'url'  => $contact_url,
      'text' => 'お問い合わせページへ',
    ],
    // 'badge' => 'おすすめ',
    'accent' => '#55cb7b',
  ],
];
?>

<section class="join section container">
  <div class="join__inner">

    <?php
    // 見出し
    get_template_part('components/section-header', null, [
      'id'          => 'recruit-spec',
      'sub'         => 'Join Us',
      'title'       => '利用者募集',
      'tag'         => 'h2',
      'variant'     => 'join',
      'extra_class' => 'join__header',
    ]);

    // カード描画コンポーネント
    get_template_part('components/info-cards', null, [
      'items'       => $items,
      'columns_md'  => 2,   // mdで2列
      'columns_lg'  => 3,   // lgで3列
      'extra_class' => 'join__cards',
      'aria_label'  => '募集事項の概要',
    ]);

    ?>
  </div>
</section>