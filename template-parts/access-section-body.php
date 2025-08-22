<?php

/**
 * template-parts/access-section-body.php
 * 地図・住所・道順・連絡先
 */
if (!defined('ABSPATH')) exit;

$map_iframe = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3237.5434440367358!2d139.82555541574231!3d35.762024880175765!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188fb969846fdb%3A0x6f188e2284ab4525!2z44CSMTIwLTAwMDUg5p2x5Lqs6YO96Laz56uL5Yy657a-54Cs77yS5LiB55uu77yS77yX4oiS77yUIDJm!5e0!3m2!1sja!2sjp!4v1675477050236!5m2!1sja!2sjp';

$address_lines = [
    '〒120-0005',
    '東京都足立区綾瀬2-27-4 D1 AYASE 2F',
];

$tel_display = '03-4400-5584';
$tel_link    = '0344005584';
$hours       = '受付時間：月〜金 9:00-17:00（土・日・祝日は閉所）';
?>

<section class="access section container" aria-labelledby="access-heading">
    <div class="access__inner">
        <header class="section__header">
            <p class="section__sub">Access</p>
            <h2 id="access-heading" class="section__title">アクセス</h2>
        </header>

        <div class="access__body">
            <!-- Map -->
            <div class="access__map">
                <div class="access__map-frame">
                    <iframe
                        class="access__map-iframe"
                        src="<?php echo esc_url($map_iframe); ?>"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        aria-label="株式会社ＬｉＮＥ ＰＡＲＫ の所在地地図"></iframe>
                </div>
            </div>

            <!-- Desc -->
            <div class="access__desc">
                <section class="access__block">
                    <h3 class="access__block-title">住所</h3>
                    <address class="access__address section__text">
                        <?php foreach ($address_lines as $line) : ?>
                            <div><?php echo esc_html($line); ?></div>
                        <?php endforeach; ?>
                    </address>
                </section>

                <section class="access__block">
                    <h3 class="access__block-title">道順</h3>
                    <p class="access__note section__text"><strong>JR綾瀬駅 東口改札より徒歩3分。</strong></p>
                    <ol class="access__wayto section__text">
                        <li>改札を出て直進し、数段の階段を下ります。</li>
                        <li>目の前の建物に入らず<strong>右折</strong>。</li>
                        <li>先の道路を<strong>左折</strong>し、高架線沿いに直進。</li>
                        <li>右手にスーパー<strong>Big-A</strong>が見えたら角を<strong>右折</strong>。</li>
                        <li>すぐ左手のコンクリートビル<strong>2F</strong>（エレベーターをご利用ください）。</li>
                    </ol>
                    <p class="access__note section__text">※階段からは入れません。エレベーターをご利用ください。</p>
                </section>

                <section class="access__block">
                    <h3 class="access__block-title">お問い合わせ</h3>
                    <p class="access__tel section__text">
                        <span class="access__tel-icon" aria-hidden="true">📞</span>
                        <a href="tel:<?php echo esc_attr($tel_link); ?>"><?php echo esc_html($tel_display); ?></a>
                    </p>
                    <p class="access__hours section__text"><?php echo esc_html($hours); ?></p>
                </section>
            </div>
        </div>
    </div>

    <!-- 構造化データ（任意） -->
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "LocalBusiness",
            "name": "株式会社ＬｉＮＥ ＰＡＲＫ",
            "telephone": "+81-<?php echo substr($tel_link, 0, 2); ?>-<?php echo substr($tel_link, 2, 4); ?>-<?php echo substr($tel_link, 6); ?>",
            "address": {
                "@type": "PostalAddress",
                "postalCode": "120-0005",
                "addressRegion": "東京都",
                "addressLocality": "足立区綾瀬",
                "streetAddress": "2-27-4 D1 AYASE 2F"
            },
            "url": "<?php echo esc_url(home_url('/access/')); ?>",
            "image": "<?php echo esc_url($hero_img ?? get_theme_file_uri('assets/img/access-hero.webp')); ?>"
        }
    </script>
</section>