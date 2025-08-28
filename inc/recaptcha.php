<?php
/**
 * reCAPTCHA v3 の設定（フィルタで注入）
 */
if (!defined('ABSPATH')) exit;

/* サイトキー／シークレット：
   wp-config.php で定義済みならその値を使い、未定義なら空文字（無効） */
add_filter('lp_recaptcha_site_key', function ($key = '') {
  if (defined('RECAPTCHA_SITE_KEY') && RECAPTCHA_SITE_KEY) return RECAPTCHA_SITE_KEY;
  return $key ?: '';
}, 10, 1);

add_filter('lp_recaptcha_secret', function ($secret = '') {
  if (defined('RECAPTCHA_SECRET_KEY') && RECAPTCHA_SECRET_KEY) return RECAPTCHA_SECRET_KEY;
  return $secret ?: '';
}, 10, 1);

/* スコア（0.0〜1.0）— 既定0.5 */
add_filter('lp_recaptcha_score', function () { return 0.5; });
