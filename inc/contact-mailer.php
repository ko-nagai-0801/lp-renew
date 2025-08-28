<?php
/**
 * inc/contact-mailer.php
 * 
 * SMTP 設定（PHPMailer）
 * wp-config.php の定数を優先し、未定義時はロリポップ推奨値（smtp.lolipop.jp / 465 / ssl）を既定に。
 */
if (!defined('ABSPATH')) exit;

add_action('phpmailer_init', function ($phpmailer) {
  // 最低限：ユーザー/パスが無ければ何もしない（=サーバのmail()）
  if (!defined('SMTP_USER') || !defined('SMTP_PASS')) return;

  // 既定はロリポップの設定
  $host   = defined('SMTP_HOST')   ? SMTP_HOST   : 'smtp.lolipop.jp';
  $secure = defined('SMTP_SECURE') ? strtolower(SMTP_SECURE) : 'ssl'; // 'ssl' or 'tls'
  $port   = defined('SMTP_PORT')   ? (int) SMTP_PORT : ($secure === 'ssl' ? 465 : 587);

  $phpmailer->isSMTP();
  $phpmailer->Host       = $host;
  $phpmailer->Port       = $port;
  $phpmailer->SMTPAuth   = true;
  $phpmailer->Username   = SMTP_USER;
  $phpmailer->Password   = SMTP_PASS;
  $phpmailer->SMTPSecure = $secure;                 // 'ssl'（465）/ 'tls'（587）
  $phpmailer->SMTPAutoTLS = ($secure === 'tls');    // STARTTLSのみ自動昇格

  // 日本語メールの相性改善
  $phpmailer->CharSet    = 'UTF-8';
  $phpmailer->Encoding   = 'base64';

  // 送信元（未指定時は SMTP_USER を使用）※FROM不一致で弾かれないように
  $from_addr = defined('SMTP_FROM') ? SMTP_FROM : SMTP_USER;
  $from_name = defined('SMTP_FROM_NAME') ? SMTP_FROM_NAME : get_bloginfo('name');

  $phpmailer->setFrom($from_addr, $from_name, false);
  $phpmailer->Sender = $from_addr; // Return-Path（バウンス先）

  // タイムアウトやKeepAliveは環境に応じて
  $phpmailer->Timeout = 15;
  $phpmailer->SMTPKeepAlive = false;
}, 10);

// 念のため、wp_mail() 側のFromにも揃える（他のプラグインで上書きされにくくする）
add_filter('wp_mail_from', function ($from) {
  if (defined('SMTP_FROM')) return SMTP_FROM;
  if (defined('SMTP_USER')) return SMTP_USER;
  return $from;
}, 20);
add_filter('wp_mail_from_name', function ($name) {
  if (defined('SMTP_FROM_NAME')) return SMTP_FROM_NAME;
  return $name ?: get_bloginfo('name');
}, 20);
