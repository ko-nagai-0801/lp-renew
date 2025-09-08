<?php

/**
 * お問い合わせハンドラ（確認→送信／編集復帰・reCAPTCHA・レート制限）
 * inc/contact-handler.php
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 *
 * 更新履歴:
 * - 1.1.0 (2025-09-08): 送信日時が `{current_time(...)}` のまま表示される不具合を修正（wp_date()で生成→変数展開に変更）。
 * - 1.0.0: 初版
 */

if (!defined('ABSPATH')) exit;

/* =======================
   設定（フィルタで上書き可能）
   ======================= */
function lp_contact_config()
{
    return [
        'to'               => apply_filters('lp_contact_to', 'info@linepark.co.jp'),
        'subject_admin'    => apply_filters('lp_contact_subject', '【ＬｉＮＥ ＰＡＲＫ】お問い合わせを受け付けました'),
        'subject_user'     => apply_filters('lp_contact_autoreply_subject', '【ＬｉＮＥ ＰＡＲＫ】お問い合わせ受付完了のお知らせ'),
        'rate_seconds'     => (int) apply_filters('lp_contact_rate_limit', 60),
        'pending_ttl'      => (int) apply_filters('lp_contact_pending_ttl', 15 * MINUTE_IN_SECONDS),
        'recaptcha_site'   => apply_filters('lp_recaptcha_site_key', defined('RECAPTCHA_SITE_KEY') ? RECAPTCHA_SITE_KEY : ''),
        'recaptcha_secret' => apply_filters('lp_recaptcha_secret', defined('RECAPTCHA_SECRET_KEY') ? RECAPTCHA_SECRET_KEY : ''),
        'recaptcha_score'  => (float) apply_filters('lp_recaptcha_score', 0.5),
    ];
}

/* 共通：キー作成 */
function lp_contact_keys()
{
    $ip   = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $salt = defined('NONCE_SALT') ? NONCE_SALT : 'lp_contact_salt';
    return [
        'ok'   => 'lp_contact_ok_'  . md5($ip . $salt),
        'err'  => 'lp_contact_err_' . md5($ip . $salt),
        'old'  => 'lp_contact_old_' . md5($ip . $salt),
        'rate' => 'lp_contact_rate_' . md5($ip),
    ];
}

/* 共通：収集・検証・整形 */
function lp_contact_collect_and_validate(array $src, array &$errors)
{
    // ▼固定配列→フィルタで差し替え可能に
    $valid_types = apply_filters('lp_contact_types', [
        'お仕事のご相談',
        'ご利用/ご見学のご相談',
        '協賛/ご支援など',
        'その他',
    ]);

    $old = [
        'type'          => isset($src['type'])          ? sanitize_text_field(wp_unslash($src['type']))       : '',
        'name'          => isset($src['name'])          ? sanitize_text_field(wp_unslash($src['name']))       : '',
        'name_kana'     => isset($src['name_kana'])     ? sanitize_text_field(wp_unslash($src['name_kana']))  : '',
        'company'       => isset($src['company'])       ? sanitize_text_field(wp_unslash($src['company']))    : '',
        'email'         => isset($src['email'])         ? sanitize_email(wp_unslash($src['email']))           : '',
        'email_confirm' => isset($src['email_confirm']) ? sanitize_email(wp_unslash($src['email_confirm']))   : '',
        'phone'         => isset($src['phone'])         ? sanitize_text_field(wp_unslash($src['phone']))      : '',
        'message'       => isset($src['message'])       ? sanitize_textarea_field(wp_unslash($src['message'])) : '',
        'agree'         => isset($src['agree'])         ? '1' : '',
    ];

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
    return $old;
}

/* =========================
   Step1: 確認へ（検証 → pending保存）
   ========================= */
add_action('admin_post_nopriv_lp_contact_confirm', 'lp_handle_contact_confirm');
add_action('admin_post_lp_contact_confirm',        'lp_handle_contact_confirm');

function lp_handle_contact_confirm()
{
    $cfg  = lp_contact_config();
    $keys = lp_contact_keys();

    // リダイレクト先（フォーム） ※無ければTOP
    $redirect = isset($_POST['_redirect']) ? esc_url_raw(wp_unslash($_POST['_redirect'])) : home_url('/');

    // CSRF
    if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'lp_contact_step1')) {
        set_transient($keys['err'], ['nonce' => '不正なリクエストです。'], 60);
        wp_safe_redirect($redirect);
        exit;
    }

    // ハニーポット
    $hp = isset($_POST['hp_company']) ? trim((string)$_POST['hp_company']) : '';
    if ($hp !== '') {
        set_transient($keys['err'], ['spam' => '不正な送信が検出されました。'], 60);
        wp_safe_redirect($redirect);
        exit;
    }

    // レート制限（短時間繰り返しの “確認” 抑止）
    if (get_transient($keys['rate'])) {
        set_transient($keys['err'], ['rate' => '短時間に複数回の操作はできません。しばらくしてからお試しください。'], 60);
        wp_safe_redirect($redirect);
        exit;
    }

    // 収集＋検証
    $errors = [];
    $old    = lp_contact_collect_and_validate($_POST, $errors);

    if ($errors) {
        set_transient($keys['err'], $errors, 60);
        set_transient($keys['old'], $old,    60);
        wp_safe_redirect($redirect);
        exit;
    }

    // pending 保存（UUID チケット）
    $ticket  = wp_generate_uuid4();
    $pending = [
        'data'     => $old,
        'ip'       => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'created'  => time(),
        'redirect' => $redirect, // 修正用・Thanks戻り先
    ];
    set_transient('lp_contact_pending_' . $ticket, $pending, $cfg['pending_ttl']);

    // 軽いレート制限セット
    set_transient($keys['rate'], 1, 10);

    // 確認画面へ
    $confirm_url = home_url('/confirm/') . '?t=' . rawurlencode($ticket);
    wp_safe_redirect($confirm_url);
    exit;
}

/* =========================
   Edit: 修正（フォームへデータ復元）
   ========================= */
add_action('admin_post_nopriv_lp_contact_edit', 'lp_handle_contact_edit');
add_action('admin_post_lp_contact_edit',        'lp_handle_contact_edit');

function lp_handle_contact_edit()
{
    $keys   = lp_contact_keys();
    $ticket = isset($_POST['ticket']) ? sanitize_text_field(wp_unslash($_POST['ticket'])) : '';
    $trans  = $ticket ? get_transient('lp_contact_pending_' . $ticket) : false;

    $redirect = ($trans && !empty($trans['redirect'])) ? $trans['redirect'] : home_url('/');

    if (!$trans || empty($trans['data'])) {
        set_transient($keys['err'], ['expired' => '確認データの有効期限が切れました。最初からやり直してください。'], 60);
        wp_safe_redirect($redirect);
        exit;
    }

    set_transient($keys['old'], $trans['data'], 60);
    wp_safe_redirect($redirect);
    exit;
}

/* =========================
   Step2: 送信（reCAPTCHA→メール）
   ========================= */
add_action('admin_post_nopriv_lp_contact_send', 'lp_handle_contact_send');
add_action('admin_post_lp_contact_send',        'lp_handle_contact_send');

function lp_handle_contact_send()
{
    $cfg  = lp_contact_config();
    $keys = lp_contact_keys();

    // Nonce
    if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'lp_contact_send')) {
        wp_safe_redirect(home_url('/'));
        exit;
    }

    // 必須: ticket / back
    $ticket = isset($_POST['ticket']) ? sanitize_text_field(wp_unslash($_POST['ticket'])) : '';
    $back   = isset($_POST['_back'])  ? esc_url_raw(wp_unslash($_POST['_back'])) : home_url('/contact/');

    $pending_key = 'lp_contact_pending_' . $ticket;
    $trans       = $ticket ? get_transient($pending_key) : false;

    if (!$trans || empty($trans['data'])) {
        set_transient($keys['err'], ['expired' => '確認データの有効期限が切れました。最初からやり直してください。'], 90);
        wp_safe_redirect($back);
        exit;
    }

    $data = $trans['data'];

    // reCAPTCHA v3 検証（有効時のみ）
    if ($cfg['recaptcha_site'] && $cfg['recaptcha_secret']) {
        $token = isset($_POST['g-recaptcha-response']) ? sanitize_text_field(wp_unslash($_POST['g-recaptcha-response'])) : '';
        if (!$token) {
            set_transient($keys['err'], ['recaptcha' => '認証に失敗しました（トークンなし）。時間を置いてお試しください。'], 90);
            wp_safe_redirect($back);
            exit;
        }
        $resp = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
            'timeout' => 8,
            'body' => [
                'secret'   => $cfg['recaptcha_secret'],
                'response' => $token,
                'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
            ],
        ]);
        $ok = false;
        if (!is_wp_error($resp) && isset($resp['body'])) {
            $json = json_decode($resp['body'], true);
            $ok   = !empty($json['success']) && (float)($json['score'] ?? 0) >= $cfg['recaptcha_score'];
        }
        if (!$ok) {
            set_transient($keys['err'], ['recaptcha' => 'スパム判定の可能性があります。別の回線やブラウザでお試しください。'], 120);
            wp_safe_redirect($back);
            exit;
        }
    }

    // 送信日時（WPのタイムゾーンに従う）
    // heredoc では関数呼び出しが展開されないため、先に文字列化して変数を埋め込む
    $sent_at = function_exists('wp_date') ? wp_date('Y-m-d H:i:s') : date_i18n('Y-m-d H:i:s');

    // メールヘッダ共通
    $site_name = get_bloginfo('name');
    $host      = wp_parse_url(home_url(), PHP_URL_HOST);
    $from_addr = defined('SMTP_FROM') ? SMTP_FROM : ('noreply@' . $host);
    $ip        = $trans['ip'] ?? ($_SERVER['REMOTE_ADDR'] ?? 'unknown');

    /* 運営向け本文（プレーンテキスト） */
    $body_admin = <<<TXT
ＬｉＮＥ ＰＡＲＫホームページより、
以下の内容でお問い合わせを受け付けました。

■ お問い合わせ種別
{$data['type']}

■ お名前
{$data['name']}

■ ふりがな
{$data['name_kana']}

■ 会社名
{$data['company']}

■ 電話番号
{$data['phone']}

■ メールアドレス
{$data['email']}

■ お問い合わせ内容
{$data['message']}

送信元IP: {$ip}
送信日時: {$sent_at}
TXT;

    $headers_admin = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <' . $from_addr . '>',
        'Reply-To: ' . $data['name'] . ' <' . $data['email'] . '>',
    ];
    $subject_admin = $cfg['subject_admin'] . ' [' . $data['type'] . ']';
    $ok_admin      = wp_mail($cfg['to'], $subject_admin, $body_admin, $headers_admin);

    /* ユーザー向け自動返信（プレーンテキスト） */
    $body_user = <<<TXT
※このメールはシステムからの自動返信です

{$data['name']} 様

お世話になっております。
この度はＬｉＮＥ ＰＡＲＫへお問い合わせいただき、
ありがとうございました。

以下の内容でお問い合わせを受け付けました。
改めて、担当よりご連絡をさせていただきますので、
今しばらくお待ちくださいませ。

━━━━━━□■□　お問い合わせ内容　□■□━━━━━━
お問い合わせ種別：{$data['type']}
お名前：{$data['name']}
ふりがな：{$data['name_kana']}
E-Mail：{$data['email']}
電話番号：{$data['phone']}
会社名：{$data['company']}
お問い合わせ内容：
{$data['message']}
━━━━━━━━━━━━━━━━━━━━━━━━━━

—————————————————————————
株式会社ＬｉＮＥ ＰＡＲＫ
【会社情報】
住所：〒120-0005　東京都足立区綾瀬 2-27-4 D1 AYASE 2F
電話番号：03-4400-5584
営業時間：平日 9時～17時
メール：info@linepark.co.jp
—————————————————————————
TXT;

    $headers_user = [
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <' . $from_addr . '>',
        'Reply-To: ' . $site_name . ' <' . $from_addr . '>',
    ];
    $ok_user = wp_mail($data['email'], $cfg['subject_user'], $body_user, $headers_user);

    if ($ok_admin) {
        // 成功フラグ（フォーム側の完了表示に備え：任意）
        set_transient($keys['ok'], 1, 60);
        // pending 破棄
        delete_transient($pending_key);
        // Thanks へ（戻り先を付与）
        $thanks = add_query_arg(['back' => rawurlencode($back)], home_url('/thanks/'));
        wp_safe_redirect($thanks);
        exit;
    }

    set_transient($keys['err'], ['send' => '送信に失敗しました。時間を置いて再度お試しください。'], 90);
    wp_safe_redirect($back);
    exit;
}
