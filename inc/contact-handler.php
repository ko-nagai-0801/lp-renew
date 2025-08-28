<?php
/**
 * inc/contact-handler.php
 * 
 * Contact 送信ハンドラ（admin-post）
 * File: contact-handler.php
 *
 * - /wp-admin/admin-post.php?action=lp_contact_submit にPOSTされる
 * - 成否・入力値・エラーは Transient 経由でフォームへ戻す
 */
if (!defined('ABSPATH')) exit;

/** アクションフック登録 */
add_action('admin_post_nopriv_lp_contact_submit', 'lp_handle_contact_submit');
add_action('admin_post_lp_contact_submit',        'lp_handle_contact_submit');

function lp_handle_contact_submit() {

  // =========================
  // 設定（関数内でローカル定義）
  // =========================
  $CONTACT_TO         = apply_filters('lp_contact_to', 'info@linepark.co.jp');
  $AUTOREPLY_SUBJECT  = apply_filters('lp_contact_autoreply_subject', 'お問い合わせありがとうございます（自動返信）');
  $RATE_LIMIT_SECONDS = (int) apply_filters('lp_contact_rate_limit', 60);
  $CONTACT_SUBJECT    = apply_filters('lp_contact_subject', '【LPサイト】お問い合わせ');

  // リダイレクト先が無ければTOPへ
  if (!isset($_POST['_redirect'])) {
    wp_safe_redirect(home_url('/'));
    exit;
  }
  $redirect = esc_url_raw(wp_unslash($_POST['_redirect']));

  // Transient キー
  $ip   = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
  $salt = defined('NONCE_SALT') ? NONCE_SALT : 'lp_contact_salt';
  $ok_key  = 'lp_contact_ok_'  . md5($ip . $salt);
  $err_key = 'lp_contact_err_' . md5($ip . $salt);
  $old_key = 'lp_contact_old_' . md5($ip . $salt);

  $errors = [];
  $old = [
    'type'          => '',
    'name'          => '',
    'name_kana'     => '',
    'company'       => '',
    'email'         => '',
    'email_confirm' => '',
    'phone'         => '',
    'message'       => '',
    'agree'         => '',
  ];

  // CSRF
  if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'lp_contact_send')) {
    $errors['nonce'] = '不正なリクエストです。';
  }

  // ハニーポット
  $hp = isset($_POST['hp_company']) ? trim((string)$_POST['hp_company']) : '';
  if ($hp !== '') {
    $errors['spam'] = '不正な送信が検出されました。';
  }

  // レート制限
  $rate_key = 'lp_contact_rate_' . md5($ip);
  if (get_transient($rate_key)) {
    $errors['rate'] = '短時間に複数回の送信はできません。しばらくしてからお試しください。';
  }

  // 入力取得・サニタイズ
  $old['type']          = isset($_POST['type'])          ? sanitize_text_field(wp_unslash($_POST['type']))     : '';
  $old['name']          = isset($_POST['name'])          ? sanitize_text_field(wp_unslash($_POST['name']))     : '';
  $old['name_kana']     = isset($_POST['name_kana'])     ? sanitize_text_field(wp_unslash($_POST['name_kana'])): '';
  $old['company']       = isset($_POST['company'])       ? sanitize_text_field(wp_unslash($_POST['company']))  : '';
  $old['email']         = isset($_POST['email'])         ? sanitize_email(wp_unslash($_POST['email']))         : '';
  $old['email_confirm'] = isset($_POST['email_confirm']) ? sanitize_email(wp_unslash($_POST['email_confirm'])) : '';
  $old['phone']         = isset($_POST['phone'])         ? sanitize_text_field(wp_unslash($_POST['phone']))    : '';
  $old['message']       = isset($_POST['message'])       ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';
  $old['agree']         = isset($_POST['agree'])         ? '1' : '';

  // バリデーション
  $valid_types = ['お仕事のご相談','ご利用/ご見学のご相談','協賛/ご支援','その他'];
  if (!in_array($old['type'], $valid_types, true)) {
    $errors['type'] = 'お問い合わせ種別を選択してください。';
  }
  if ($old['name'] === '' || mb_strlen($old['name']) < 2) {
    $errors['name'] = 'お名前を正しく入力してください。';
  }
  if ($old['name_kana'] === '' || !preg_match('/^[ぁ-ゟ゠ー\s　]+$/u', $old['name_kana'])) {
    $errors['name_kana'] = 'ふりがなは「ひらがな」で入力してください。';
  }
  if ($old['email'] === '' || !is_email($old['email'])) {
    $errors['email'] = 'メールアドレスを正しく入力してください。';
  }
  if ($old['email_confirm'] === '' || strtolower($old['email_confirm']) !== strtolower($old['email'])) {
    $errors['email_confirm'] = '確認用メールアドレスが一致しません。';
  }
  if ($old['phone'] !== '' && !preg_match('/^[0-9+\-\s()]{6,20}$/u', $old['phone'])) {
    $errors['phone'] = '電話番号の形式が正しくありません。';
  }
  if ($old['message'] === '' || mb_strlen($old['message']) < 10 || mb_strlen($old['message']) > 500) {
    $errors['message'] = 'お問い合わせ内容は10〜500文字で入力してください。';
  }
  if ($old['agree'] !== '1') {
    $errors['agree'] = 'プライバシーポリシーへの同意が必要です。';
  }

  // 失敗時：戻す
  if ($errors) {
    set_transient($err_key, $errors, 60);
    set_transient($old_key, $old,    60);
    wp_safe_redirect($redirect);
    exit;
  }

  // 送信準備
  $site_name = get_bloginfo('name');
  $host      = wp_parse_url(home_url(), PHP_URL_HOST);
  $from_addr = defined('SMTP_FROM') ? SMTP_FROM : ('noreply@' . $host);

  // 管理者向け本文
  $body_admin = <<<TXT
以下の内容でお問い合わせが届きました。

■ 種別
{$old['type']}

■ お名前
{$old['name']}

■ ふりがな
{$old['name_kana']}

■ メールアドレス
{$old['email']}

■ 電話番号
{$old['phone']}

■ 会社名
{$old['company']}

■ お問い合わせ内容
{$old['message']}

送信元IP: {$ip}
URL: {$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}
送信日時: {current_time('Y-m-d H:i:s')}
TXT;

  $headers_admin = [
    'Content-Type: text/plain; charset=UTF-8',
    'From: ' . $site_name . ' <' . $from_addr . '>',
    'Reply-To: ' . $old['name'] . ' <' . $old['email'] . '>',
  ];

  // 件名に種別を付与
  $subject_admin = $CONTACT_SUBJECT . ' [' . $old['type'] . ']';

  // 管理者送信
  $ok_admin = wp_mail($CONTACT_TO, $subject_admin, $body_admin, $headers_admin);

  // 自動返信（ユーザー）
  $body_user = <<<TXT
{$old['name']} 様

この度はお問い合わせありがとうございます。
以下の内容で受け付けいたしました。担当より折り返しご連絡いたします。

――――――――――――――――――――
種別：{$old['type']}
お名前：{$old['name']}
ふりがな：{$old['name_kana']}
メール：{$old['email']}
電話：{$old['phone']}
会社名：{$old['company']}
内容：
{$old['message']}
――――――――――――――――――――

※本メールは送信専用アドレスから送信しています。
{$site_name}
TXT;

  $headers_user = [
    'Content-Type: text/plain; charset=UTF-8',
    'From: ' . $site_name . ' <' . $from_addr . '>',
    'Reply-To: ' . $site_name . ' <' . $from_addr . '>',
  ];
  $ok_user = wp_mail($old['email'], $AUTOREPLY_SUBJECT, $body_user, $headers_user);

  if ($ok_admin) {
    // レート制限セット
    set_transient($rate_key, 1, $RATE_LIMIT_SECONDS);
    // 成功フラグ
    set_transient($ok_key, 1, 60);
  } else {
    set_transient($err_key, ['send' => '送信に失敗しました。時間を置いて再度お試しください。'], 60);
    set_transient($old_key, $old, 60);
  }

  wp_safe_redirect($redirect);
  exit;
}
