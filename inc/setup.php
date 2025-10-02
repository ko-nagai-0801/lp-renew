<?php
/**
 * テーマ初期化（title-tag対応 / HTML5 / アイキャッチ / メニュー登録）
 * inc/setup.php
 *
 * @package LP_WP_Theme
 * @since 1.2.0
 *
 * 更新履歴:
 * - 1.2.0 (2025-10-02): title-tag を追加。HTML5サポートを拡充（comment-list/caption/style/script）。
 * - 1.1.0: 初版（HTML5/アイキャッチ/ナビメニュー）
 */
if (!defined('ABSPATH')) exit;

/**
 * テーマ機能のセットアップ
 * - WPコアの document <title> 生成を有効化（Yoast 等もここにフック）
 * - HTML5 マークアップ
 * - アイキャッチ（サムネイル）
 * - ナビメニュー登録
 */
function lp_setup() {

  // -------------------------------
  // 1) <title> をWPコアに任せる（必須）
  //    これが無いと<head>に<title>が出ず、ブラウザがURLパスをタブに表示してしまう
  // -------------------------------
  add_theme_support('title-tag');

  // -------------------------------
  // 2) HTML5 マークアップ（必要な要素を拡張）
  // -------------------------------
  add_theme_support('html5', [
    'search-form',
    'comment-form',
    'comment-list',
    'gallery',
    'caption',
    'style',
    'script',
  ]);

  // -------------------------------
  // 3) アイキャッチ（投稿/固定ページでサムネ使用）
  // -------------------------------
  add_theme_support('post-thumbnails');

  // -------------------------------
  // 4) ナビメニュー登録（外観→メニューで編集可能）
  // -------------------------------
  register_nav_menus([
    'primary' => 'プライマリナビゲーション',
  ]);
}
add_action('after_setup_theme', 'lp_setup');
