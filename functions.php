<?php

/**
 * テーマ機能の読み込み元
 * theme root /functions.php
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 *
 * 更新履歴:
 * - 1.1.0 (2025-09-16): SEO補助モジュール（feed停止/クエリ除去/canonical整形）を読み込み対象に追加
 * - 1.0.0: 初版
 */

if (!defined('ABSPATH')) exit;

// inc フォルダーに置いたファイルを順に読み込む
$modules = [
  'setup',
  'assets',
  'contact-routes',
  'contact-mailer',
  'contact-handler',
  'contact-config',
  'news-functions',
  // 'theme-filters',
  'analytics',
  'shortcodes',

  // ===============================
  // SEO補助
  // ===============================
  'seo/disable-feeds',        // すべての /feed/ 系を 410 Gone にしてインデックス外へ
  'seo/strip-query-redirect', // privacy等の余計なクエリ (?consent, ?from_page など) を正規URLへ301
  'seo/canonical-cleaner',    // Yoastのcanonicalから内部用/トラッキングクエリを除去
  'seo/document-title', // ドキュメントタイトル整形（区切り変更＆空タイトルの簡易フォールバック）
];

foreach ($modules as $file) {
  $file_path = get_theme_file_path("inc/{$file}.php");
  if (file_exists($file_path)) {
    require_once $file_path;  // ファイルがあれば読み込み
  }
  // 無ければスキップ（開発中でも致命的にしない）
}
