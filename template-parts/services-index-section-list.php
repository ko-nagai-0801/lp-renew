<?php
/**
 * Services Index – List Section
 * File: template-parts/services-index-section-list.php
 * @package LP_WP_Theme
 * @since 1.0.0
 *
 * 呼び出し例:
 * get_template_part('template-parts/services-index-section', 'list', [
 *   'parent_id' => get_the_ID(),
 *   'sub'       => 'Services',
 *   'title'     => get_the_title(),
 *   'catch'     => has_excerpt() ? get_the_excerpt() : '',
 *   // 'title_tag' => 'h2',
 *   // 'posts_per_page' => -1,
 * ]);
 */
if (!defined('ABSPATH')) exit;

$defaults = [
  'parent_id'       => get_the_ID(),
  'sub'             => 'Services',
  'title'           => get_the_title(),
  'title_tag'       => 'h2',
  'catch'           => '',
  'orderby'         => ['menu_order' => 'ASC', 'date' => 'DESC'],
  'posts_per_page'  => -1,
  'post_status'     => 'publish',
  'section_id'      => 'services-index',
  'container_class' => 'section container services-index',
  'list_class'      => 'services-index__cards',
  'empty_text'      => '現在、公開中のサービスはありません。',
  'image_size'      => 'large',
];
$args = wp_parse_args($args ?? [], $defaults);

$tag = in_array($args['title_tag'], ['h1','h2','h3','h4','h5','h6'], true) ? $args['title_tag'] : 'h2';

$q = new WP_Query([
  'post_type'      => 'page',
  'post_parent'    => (int) $args['parent_id'],
  'orderby'        => $args['orderby'],
  'posts_per_page' => (int) $args['posts_per_page'],
  'post_status'    => $args['post_status'],
]);
?>

<section id="<?php echo esc_attr($args['section_id']); ?>" class="<?php echo esc_attr($args['container_class']); ?>" aria-labelledby="services-index-heading">
  <header class="section__header services-index__header">
    <?php if (!empty($args['sub'])): ?>
      <p class="section__sub"><?php echo esc_html($args['sub']); ?></p>
    <?php endif; ?>
    <<?php echo $tag; ?> id="services-index-heading" class="section__title">
      <?php echo esc_html($args['title']); ?>
    </<?php echo $tag; ?>>
    <?php if (!empty($args['catch'])): ?>
      <p class="section__catch"><?php echo esc_html($args['catch']); ?></p>
    <?php endif; ?>
  </header>

  <ul class="<?php echo esc_attr($args['list_class']); ?>" role="list">
    <?php if ($q->have_posts()): while ($q->have_posts()): $q->the_post();
      $thumb_id  = get_post_thumbnail_id();
      $thumb_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, $args['image_size']) : '';
      $thumb_alt = $thumb_id ? get_post_meta($thumb_id, '_wp_attachment_image_alt', true) : '';
      $thumb_alt = $thumb_alt ?: get_the_title();
      $permalink = get_permalink();
      $excerpt   = get_the_excerpt() ?: wp_trim_words(wp_strip_all_tags(get_the_content()), 42, '…');
    ?>
      <li class="services-index__card">
        <a class="services-index__link" href="<?php echo esc_url($permalink); ?>">
          <figure class="services-index__media" aria-hidden="true">
            <?php if ($thumb_url): ?>
              <img class="services-index__img" src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($thumb_alt); ?>" loading="lazy" decoding="async">
            <?php else: ?>
              <div class="services-index__ph">No Image</div>
            <?php endif; ?>
          </figure>
          <div class="services-index__body">
            <h3 class="services-index__title"><?php the_title(); ?></h3>
            <p class="services-index__text"><?php echo esc_html($excerpt); ?></p>
            <span class="services-index__cta" aria-hidden="true">詳しく見る <span class="services-index__arrow">→</span></span>
          </div>
        </a>
      </li>
    <?php endwhile; wp_reset_postdata(); else: ?>
      <?php if ($args['empty_text'] !== ''): ?>
        <li class="services-index__empty"><?php echo esc_html($args['empty_text']); ?></li>
      <?php endif; ?>
    <?php endif; ?>
  </ul>
</section>
