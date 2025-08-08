<?php

/**
 * components/section-header.php
 * 汎用セクション見出しコンポーネント。
 *
 * 使用例:
 * --------------------------------------------------
 * get_template_part( 'components/section-header', null, [
 *  'id' => 'about-heading', // 見出しID（任意）
 *  'sub' => 'About us', // 小見出し <p>
 *  'title' => 'ＬｉＮＥ ＰＡＲＫ について', // メイン見出し
 *  'tag' => 'h2', // h1〜h6 いずれか（デフォルト h2）
 *  'extra_class' => 'about__header' // <header> に追加したいクラス
 * ] );
 * ---------------------------------------------------
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$defaults = [
  'id'          => '',
  'sub'         => '',
  'title'       => '',
  'tag'         => 'h2',
  'variant'     => '',          // ★ 追加
  'extra_class' => '',
];
$args = wp_parse_args( $args ?? [], $defaults );

/* ① hタグを決定 */
$tag = in_array( strtolower( $args['tag'] ), [ 'h1','h2','h3','h4','h5','h6' ], true )
        ? strtolower( $args['tag'] ) : 'h2';

/* ② Modifier Class を組み立て */
$variant   = $args['variant'] ? ' section__header--' . esc_attr( $args['variant'] ) : '';
$header_cl = trim( 'section__header' . $variant . ' ' . $args['extra_class'] );
$sub_cl    = 'section__sub'   . ( $args['variant'] ? ' section__sub--'   . esc_attr( $args['variant'] ) : '' );
$title_cl  = 'section__title' . ( $args['variant'] ? ' section__title--' . esc_attr( $args['variant'] ) : '' );
?>
<header class="<?php echo $header_cl; ?>">
  <?php if ( $args['sub'] ) : ?>
    <p class="<?php echo $sub_cl; ?>"><?php echo esc_html( $args['sub'] ); ?></p>
  <?php endif; ?>

  <<?php echo $tag; ?><?php echo $args['id'] ? ' id="'.esc_attr($args['id']).'"' : ''; ?>
      class="<?php echo $title_cl; ?>">
      <?php
      /* 1 文字ずつ <span class="char"> */
      $chars = preg_split('//u', $args['title'], -1, PREG_SPLIT_NO_EMPTY);
      foreach ( $chars as $i => $c ) {
        printf( '<span class="char" style="--i:%d;">%s</span>', $i, esc_html( $c ) );
      }
      ?>
  </<?php echo $tag; ?>>
</header>