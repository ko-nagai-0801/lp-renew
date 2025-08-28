<?php
if (! defined('ABSPATH')) exit;

// inc フォルダーに置いたファイルを順に読み込む
foreach (['setup', 'assets', 'contact-mailer', 'contact-handler', 'contact-routes', 'recaptcha'] as $file) {
  $file_path = get_theme_file_path("inc/{$file}.php");
  if (file_exists($file_path)) require_once $file_path;
}
