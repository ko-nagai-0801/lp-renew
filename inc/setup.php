<?php
/**
 * テーマ初期化
 * - HTML5 マークアップ
 * - アイキャッチ
 * - ナビメニュー
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function lp_setup() {

	// HTML5 マークアップ
	add_theme_support(
		'html5',
		[
			'search-form',
			'comment-form',
			'gallery',
		]
	);

	// アイキャッチ
	add_theme_support( 'post-thumbnails' );

	// ナビメニュー
	register_nav_menus(
		[
			'primary' => 'プライマリナビゲーション',
		]
	);
}
add_action( 'after_setup_theme', 'lp_setup' );
