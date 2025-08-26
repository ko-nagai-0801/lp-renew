<?php
/**
 * index.php（最終的なフォールバックテンプレート） – BEM
 * theme root /index.php
 * 
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;

get_header();
?>


<main class="site-main container">
	<?php
	if (have_posts()) :
		// 投稿ループ部分
		get_template_part('template-parts/content', 'loop');

	else :
		// 投稿がないときのテンプレート（必要ならこちらもサブフォルダ化可）
		echo '<p>' . esc_html__('投稿が見つかりません。', 'sanai-textdomain') . '</p>';
	endif;
	?>
</main>

<?php
// フッター部分を読み込む
get_footer();
