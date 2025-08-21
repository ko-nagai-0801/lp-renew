<?php
/**
 * components/info-table.php
 * 汎用情報テーブル（会社概要／募集事項など）
 *
 * 使い方:
 * get_template_part('components/info-table', null, [
 *   // ヘッダー（任意）
 *   'sub'        => 'Join Us',          // 小見出し
 *   'title'      => '利用者募集',        // 見出し
 *   'variant'    => 'join',             // section-header の配色等に使う（任意）
 *   'header_tag' => 'h2',               // h1〜h6（省略可）
 *
 *   // テーブル
 *   'rows' => [
 *     ['label'=>'対象', 'value'=>'就労継続支援B型の…'],
 *     // …
 *   ],
 *   'caption'      => '募集事項',
 *   'label_width'  => '30%',
 *   'extra_class'  => 'info-table--brand', // 任意
 *   'compat_class' => 'company__table',    // 既存 company_* の見た目を流用
 *   'id'           => '',
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
  'rows'         => [],
  'caption'      => '',
  'label_width'  => '30%',
  'extra_class'  => '',
  'compat_class' => '',
  'id'           => '',
];

$args = wp_parse_args($args ?? [], $defaults);

/* classes */
$classes = ['info-table'];
if ($args['extra_class'])  $classes[] = $args['extra_class'];
if ($args['compat_class']) $classes[] = $args['compat_class']; // 互換用（company__table 等）
$class_attr = esc_attr(implode(' ', $classes));

/* styles */
$style = '--info-label-width:' . trim($args['label_width']);
?>

<div class="info-table__wrap">
  <?php
  // ===== Optional Header =====
  if ($args['sub'] || $args['title']) {
    // 既存の components/section-header を呼び出し
    get_template_part('components/section-header', null, [
      'sub'         => $args['sub'],
      'title'       => $args['title'],
      'tag'         => $args['header_tag'],
      'variant'     => $args['variant'],
      'extra_class' => 'info-table__header',
    ]);
  }
  ?>

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
        <tr class="info-table__row company__row">
          <th class="info-table__label company__label" scope="row">
            <?php echo esc_html($label); ?>
          </th>
          <td class="info-table__data company__data">
            <?php echo wp_kses_post($value); ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
