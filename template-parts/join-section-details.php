<?php

/**
 * 利用者募集（カード型 + SVG/サムネ）
 * template-parts/join-section-details.php
 *
 * @package LP_WP_Theme
 * @since 1.3.3
 *
 * 更新履歴:
 * - 1.3.3: 申し込みカードの3ボタンを縦並びに変更。電話/メールは2行表示（アイコン + 見出し + 連絡先）。色分け（電話=グリーン／メール=ブルー／フォーム=ライトブルー）。
 * - 1.3.2: 電話番号を固定（03-4400-5584）。準備中ボタン廃止。
 * - 1.3.1: 3ボタン化、単体リンク削除。
 * - 1.3.0: 「見学の申し込み」カードを追加。
 * - 1.2.0: 各カードへ media と accent を追加。
 * - 1.1.0: カードUI版。
 */
if (!defined('ABSPATH')) exit;

/* ================================
 * 1) 画像パス
 * ============================== */
$img_subject  = get_theme_file_uri('assets/img/join/subject.webp');
$img_work     = get_theme_file_uri('assets/img/join/work.webp');
$img_datetime = get_theme_file_uri('assets/img/join/datetime.webp');
$img_access   = get_theme_file_uri('assets/img/join/access.webp');
$img_visit    = get_theme_file_uri('assets/img/join/visit.webp');
$img_apply    = get_theme_file_uri('assets/img/join/apply.webp');

/* ================================
 * 2) 内部リンク（/access, /contact）
 * ============================== */
$access_url = '';
if (function_exists('get_page_by_path')) {
  $p = get_page_by_path('access');
  if ($p) $access_url = get_permalink($p);
}
if (!$access_url) $access_url = home_url('/access/');

$contact_url = '';
if (function_exists('get_page_by_path')) {
  $p = get_page_by_path('contact');
  if ($p) $contact_url = get_permalink($p);
}
if (!$contact_url) $contact_url = home_url('/contact/');

/* ================================
 * 3) 連絡先（固定値指定）
 * ============================== */
$phone_display = '03-4400-5584';
$phone_href    = 'tel:0344005584';
$email_display = 'info@linepark.co.jp';
$email_href    = 'mailto:info@linepark.co.jp';

/* ================================
 * 4) 申し込みボタン
 * ============================== */
ob_start(); ?>
<div class="join__actions join__actions--stack" role="group" aria-label="見学申し込みの方法">
  <!-- 電話 -->
  <a class="c-btn c-btn--stack c-btn--phone join__action" href="<?php echo esc_url($phone_href); ?>">
    <i class="bi bi-telephone-fill" aria-hidden="true"></i>
    <span class="c-btn__text">
      <span class="c-btn__line1">電話で申し込む</span>
      <span class="c-btn__line2"><?php echo esc_html($phone_display); ?></span>
    </span>
  </a>
  <!-- メール -->
  <a class="c-btn c-btn--stack c-btn--email join__action" href="<?php echo esc_url($email_href); ?>">
    <i class="bi bi-envelope-fill" aria-hidden="true"></i>
    <span class="c-btn__text">
      <span class="c-btn__line1">メールで申し込む</span>
      <span class="c-btn__line2"><?php echo esc_html($email_display); ?></span>
    </span>
  </a>
  <!-- フォーム -->
  <a class="c-btn c-btn--stack c-btn--form join__action" href="<?php echo esc_url($contact_url); ?>">
    <i class="bi bi-chat-dots-fill" aria-hidden="true"></i>
    <span class="c-btn__text">
      <span class="c-btn__line1">フォームから申し込む</span>
      <span class="c-btn__line2">24時間受付中</span>
    </span>
  </a>
</div>
<?php
$apply_actions = ob_get_clean();

/* ================================
 * 5) カード配列
 * ============================== */
$items = [
  [
    'media'  => ['type' => 'img', 'src' => $img_subject, 'alt' => '対象の方', 'width' => 1200, 'height' => 800],
    'icon'   => 'people-fill',
    'label'  => '対象',
    'title'  => 'B型の利用を検討中の方',
    'desc'   => '体調や生活リズムに不安がある場合もOK！<br>お気軽にご相談ください。',
    'accent' => '#4e8cff',
  ],
  [
    'media'  => ['type' => 'img', 'src' => $img_work, 'alt' => '作業の様子', 'width' => 1200, 'height' => 800],
    'icon'   => 'pc-display',
    'label'  => '作業内容',
    'title'  => '軽作業・PC作業が中心',
    'desc'   => 'ご希望やペースに合わせてスタッフがサポート。無理なく続けられます。',
    'accent' => '#00a5c9',
  ],
  [
    'media'  => ['type' => 'img', 'src' => $img_datetime, 'alt' => '開所日・時間', 'width' => 1200, 'height' => 800],
    'icon'   => 'clock',
    'label'  => '開所日・時間',
    'title'  => '平日 9:00–14:30',
    'desc'   => 'ご希望に合わせた通所も可能です。<br>まずは相談ください！',
    'accent' => '#3b6ea5',
  ],
  [
    'media'  => ['type' => 'img', 'src' => $img_access, 'alt' => 'アクセス', 'width' => 1200, 'height' => 800],
    'icon'   => 'geo-alt-fill',
    'label'  => '所在地',
    'title'  => 'JR綾瀬駅 東口 徒歩3分',
    'desc'   => '〒120-0005<br>東京都足立区綾瀬2-27-4<br>D1 AYASE 2F',
    'link'   => ['url' => $access_url, 'text' => 'アクセスページを見る'],
    'accent' => '#ff7a4a',
  ],
  [
    'media'  => ['type' => 'img', 'src' => $img_visit, 'alt' => '見学の様子', 'width' => 1200, 'height' => 800],
    'icon'   => 'emoji-smile',
    'label'  => '見学・体験',
    'title'  => '絶賛募集中！',
    'desc'   => '施設の雰囲気や作業内容を実際にご確認いただけます。<br>お気軽にお問い合わせください。',
    'link'   => ['url' => $contact_url, 'text' => 'お問い合わせページへ'],
    'accent' => '#55cb7b',
  ],
  // 見学の申し込み（縦3ボタン／単体リンクなし）
  [
    'media'  => ['type' => 'img', 'src' => $img_apply, 'alt' => '見学の申し込み', 'width' => 1200, 'height' => 800],
    'icon'   => 'calendar-check',
    'label'  => '見学の申し込み',
    'title'  => '電話・メール・フォームから受付中',
    'desc'   => wp_kses_post('ご希望の方法でお申し込みください。' . $apply_actions),
    'accent' => '#9b59b6',
  ],
];
?>

<section class="join section container">
  <div class="join__inner">
    <?php
    get_template_part('components/section-header', null, [
      'id'          => 'recruit-spec',
      'sub'         => 'Join Us',
      'title'       => '利用者募集',
      'tag'         => 'h2',
      'variant'     => 'join',
      'extra_class' => 'join__header',
    ]);

    get_template_part('components/info-cards', null, [
      'items'       => $items,
      'columns_md'  => 2,
      'columns_lg'  => 3,
      'extra_class' => 'join__cards',
      'aria_label'  => '募集事項の概要',
    ]);
    ?>
  </div>
</section>