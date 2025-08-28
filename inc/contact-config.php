<?php
/**
 * inc/contact-config.php
 * お問い合わせ種別の単一ソース化
 */
if (!defined('ABSPATH')) exit;

/**
 * 種別リスト（ここを書き換えるだけで全体に反映）
 */
add_filter('lp_contact_types', function ($types) {
  return [
    'お仕事のご相談',
    'ご利用/ご見学のご相談',
    '協賛/ご支援など',
    'その他',
  ];
}, 10, 1);
