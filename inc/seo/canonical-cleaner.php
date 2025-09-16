<?php
/**
 * Yoastのcanonicalに不要クエリが入らないよう除去
 * inc/seo/canonical-cleaner.php
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 * 更新履歴:
 * - 1.0.0 (2025-09-16): 初版
 */
if (!defined('ABSPATH')) exit;

add_filter('wpseo_canonical', function ($canonical) {
    if (!$canonical) return $canonical;
    $strip = ['from_page','from_q','consent','gclid','fbclid','utm_source','utm_medium','utm_campaign','utm_id','_ga'];
    foreach ($strip as $key) {
        if (isset($_GET[$key])) {
            $canonical = remove_query_arg($strip, $canonical);
            break;
        }
    }
    return $canonical;
});
