<?php
/**
 * テーマ主要機能の読み込み
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// inc フォルダーに置いたファイルを順に読み込む
foreach ( [ 'setup', 'assets' ] as $file ) {
	$file_path = get_theme_file_path( "inc/{$file}.php" );
	if ( file_exists( $file_path ) ) {
		require_once $file_path;
	}
}
