<?php
/**
 * components/info-table.php
 * 汎用情報テーブル（会社概要／募集事項など）
 *
 * 使い方:
 * get_template_part('components/info-table', null, [
 *   'sub'        => 'Join Us',          // 任意
 *   'title'      => '利用者募集',        // 任意
 *   'variant'    => 'join',             // 任意（section-headerの配色用）
 *   'header_tag' => 'h2',               // 任意（h1〜h6）
 *   'rows' => [
 *     ['label'=>'対象','value'=>'…'],
 *     // …
 *   ],
 *   'caption'     => '募集事項',
 *   'label_width' => '30%',             // 左カラム幅をCSS変数で
 *   'extra_class' => 'join__table',     // 追加クラス（任意）
 *   // 'compat_class' は将来廃止予定。必要なら extra_class を使ってください。
 * ]);
 */
if (!defined('ABSPATH')) exit;

$defaults = [
  // header
  'sub'        => '',
  'title'      => '',
  'variant'    => '',
  'header_tag' => 'h2',
  // table
  'rows'        => [],
  'caption'     => '',
  'label_width' => '30%',
  'extra_class' => '',   // 推奨
  'compat_class'=> '',   // 互換のため残すが内部ではそのままclassに追加するだけ
  'id'          => '',
];
$args = wp_parse_args($args ?? [], $defaults);

/* classes */
$classes = ['info-table'];
if ($args['extra_class'])  $classes[] = $args['extra_class'];
if ($args['compat_class']) $classes[] = $args['compat_class']; // ※将来削除予定
$class_attr = esc_attr(implode(' ', $classes));

/* styles */
$style = '--info-label-width:' . trim($args['label_width']);
?>

<div class="info-table__wrap">
  <?php if ($args['sub'] || $args['title']) :
    get_template_part('components/section-header', null, [
      'sub'         => $args['sub'],
      'title'       => $args['title'],
      'tag'         => $args['header_tag'],
      'variant'     => $args['variant'],
      'extra_class' => 'info-table__header',
    ]);
  endif; ?>

  <table<?php if ($args['id']) echo ' id="' . esc_attr($args['id']) . '"'; ?>
         class="<?php echo $class_attr; ?>"
         style="<?php echo esc_attr($style); ?>">
    <?php if ($args['caption']) : ?>
      <caption class="screen-reader-text"><?php echo esc_html($args['caption']); ?></caption>
    <?php endif; ?>
    <tbody>
      <?php foreach ($args['rows'] as $row) :
        $label = $row['label'] ?? '';
        $value = $row['value'] ?? '';
      ?>
        <tr class="info-table__row">
          <th class="info-table__label" scope="row"><?php echo esc_html($label); ?></th>
          <td class="info-table__data"><?php echo wp_kses_post($value); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
