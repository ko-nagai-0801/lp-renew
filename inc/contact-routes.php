<?php
/**
 * confirm / thanks のルーティング＆テンプレ切替
 */
if (!defined('ABSPATH')) exit;

/* ルール追加 */
add_action('init', function () {
  add_rewrite_rule('^confirm/?$', 'index.php?lp_confirm=1', 'top');
  add_rewrite_rule('^thanks/?$',  'index.php?lp_thanks=1',  'top');
}, 10);

/* クエリ変数 */
add_filter('query_vars', function ($vars) {
  $vars[] = 'lp_confirm';
  $vars[] = 'lp_thanks';
  return $vars;
}, 10, 1);

/* テンプレ差し替え（子→親の順でフォールバック） */
add_filter('template_include', function ($template) {
  if (get_query_var('lp_confirm')) {
    $t = get_stylesheet_directory() . '/confirm.php';
    if (!file_exists($t)) $t = get_template_directory() . '/confirm.php';
    if (file_exists($t)) return $t;
  }
  if (get_query_var('lp_thanks')) {
    $t = get_stylesheet_directory() . '/thanks.php';
    if (!file_exists($t)) $t = get_template_directory() . '/thanks.php';
    if (file_exists($t)) return $t;
  }
  return $template;
}, 99);

/* テーマ有効化時にリライトを反映 */
add_action('after_switch_theme', function () {
  flush_rewrite_rules();
});
