<?php

/**
 * components/info-table.php
 * 汎用情報テーブル（会社概要／募集事項など）
 * 役割：テーブル描画のみ（見出しは外側の section-header で管理）
 *
 * 使い方（例）：
 * get_template_part('components/section-header', null, [
 *   'id'    => 'company-outline',
 *   'sub'   => 'Company',
 *   'title' => '会社概要',
 *   'tag'   => 'h2',
 * ]);
 * get_template_part('components/info-table', null, [
 *   'rows'          => [
 *     ['label' => '社名',     'value' => '株式会社ＬｉＮＥ ＰＡＲＫ'],
 *     ['label' => '所在地',   'value' => '東京都足立区綾瀬…'],
 *   ],
 *   'caption'       => '会社概要',   // SR向けの見出し（視覚的には非表示推奨）
 *   'label_width'   => '30%',       // 左カラム幅（CSS変数）
 *   'extra_class'   => 'about__table',
 *   'id'            => 'company-outline-table',
 *   'aria_labelledby' => 'company-outline', // 外側見出しのidを参照（推奨）
 * ]);
 */

if (!defined('ABSPATH')) exit;

$defaults = [
  'rows'           => [],
  'caption'        => '',
  'label_width'    => '30%',
  'extra_class'    => '',
  'compat_class'   => '', // 将来削除予定：互換でそのまま class に追加
  'id'             => '',
  'aria_labelledby' => '', // 外側見出しの id をここで紐付け
];
$args = wp_parse_args($args ?? [], $defaults);

/* classes */
$classes = ['info-table'];
if ($args['extra_class'])  $classes[] = $args['extra_class'];
if ($args['compat_class']) $classes[] = $args['compat_class']; // 互換維持
$class_attr = esc_attr(implode(' ', $classes));

/* styles */
$style = '--info-label-width:' . trim((string)$args['label_width']);

/* 行配列の安全化 */
$rows = is_array($args['rows']) ? $args['rows'] : [];

/* アクセシビリティ連携属性 */
$aria = '';
if (!empty($args['aria_labelledby'])) {
  $aria = ' aria-labelledby="' . esc_attr($args['aria_labelledby']) . '"';
}
?>
<div class="info-table__wrap">
  <table<?php
        if ($args['id']) echo ' id="' . esc_attr($args['id']) . '"';
        echo ' class="' . $class_attr . '"';
        echo ' style="' . esc_attr($style) . '"';
        echo $aria;
        ?>>
    <?php if ($args['caption']) : ?>
      <caption class="screen-reader-text"><?php echo esc_html($args['caption']); ?></caption>
    <?php endif; ?>

    <tbody>
      <?php foreach ($rows as $row) :
        $label = isset($row['label']) ? (string)$row['label'] : '';
        $value = isset($row['value']) ? $row['value'] : '';
        // 完全空行はスキップ
        if ($label === '' && (is_string($value) ? trim($value) === '' : empty($value))) continue;
      ?>
        <tr class="info-table__row">
          <th class="info-table__label" scope="row">
            <?php echo esc_html($label); ?>
          </th>
          <td class="info-table__data">
            <?php echo wp_kses_post(is_string($value) ? $value : ''); ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    </table>
</div>