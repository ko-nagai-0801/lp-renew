<?php

/**
 * inc/news-functions.php
 * NEWS 用の共通ヘルパー一式（検索/ハイライト + NEW/UPDATED + 表示日付 + 既定クエリ）
 * 
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;

/* ============================================================================
 * 検索・正規化（かな/英数折り畳み 等）
 * ========================================================================== */

if (!function_exists('lp_to_hiragana')) {
  function lp_to_hiragana(string $s): string
  {
    return preg_replace_callback('/[\x{30A1}-\x{30FA}]/u', function ($m) {
      $cp = function_exists('mb_ord') ? mb_ord($m[0], 'UTF-8') : (class_exists('IntlChar') ? IntlChar::ord($m[0]) : null);
      if ($cp === null) return $m[0];
      return function_exists('mb_chr') ? mb_chr($cp - 0x60, 'UTF-8') : (class_exists('IntlChar') ? IntlChar::chr($cp - 0x60) : $m[0]);
    }, $s);
  }
}
if (!function_exists('lp_to_katakana')) {
  function lp_to_katakana(string $s): string
  {
    return preg_replace_callback('/[\x{3041}-\x{3096}]/u', function ($m) {
      $cp = function_exists('mb_ord') ? mb_ord($m[0], 'UTF-8') : (class_exists('IntlChar') ? IntlChar::ord($m[0]) : null);
      if ($cp === null) return $m[0];
      return function_exists('mb_chr') ? mb_chr($cp + 0x60, 'UTF-8') : (class_exists('IntlChar') ? IntlChar::chr($cp + 0x60) : $m[0]);
    }, $s);
  }
}

/** 検索用折り畳み：半角ｶﾅ→全角(濁点結合), カナ→ひら, 全角英数→半角, 小文字化 */
if (!function_exists('lp_fold_for_search')) {
  function lp_fold_for_search(string $s): string
  {
    $s = mb_convert_kana($s, 'KV', 'UTF-8'); // ﾞ/ﾟを結合
    $s = lp_to_hiragana($s);
    $s = preg_replace_callback('/[\x{FF10}-\x{FF19}\x{FF21}-\x{FF3A}\x{FF41}-\x{FF5A}]/u', function ($m) {
      $cp = function_exists('mb_ord') ? mb_ord($m[0], 'UTF-8') : (class_exists('IntlChar') ? IntlChar::ord($m[0]) : null);
      $cp = $cp ?? 0;
      return function_exists('mb_chr') ? mb_chr($cp - 0xFEE0, 'UTF-8') : (class_exists('IntlChar') ? IntlChar::chr($cp - 0xFEE0) : $m[0]);
    }, $s);
    return mb_strtolower($s, 'UTF-8');
  }
}

/** Unicode空白→半角スペース正規化（WP_Query用） */
if (!function_exists('lp_normalize_spaces')) {
  function lp_normalize_spaces(string $s): string
  {
    $s = preg_replace('/[\p{Z}\s]+/u', ' ', $s);
    return trim($s ?? '');
  }
}

/** 正規化文字列と「正規化→元」対応マップを作成 */
if (!function_exists('lp_make_norm_and_map')) {
  function lp_make_norm_and_map(string $text): array
  {
    $norm = '';
    $map  = [];
    $len  = mb_strlen($text, 'UTF-8');
    $i = 0;

    while ($i < $len) {
      $start = $i;
      $ch = mb_substr($text, $i, 1, 'UTF-8');
      $i++;

      // 半角カナ + 濁点/半濁点
      if (preg_match('/[\x{FF66}-\x{FF9D}]/u', $ch) && $i < $len) {
        $next = mb_substr($text, $i, 1, 'UTF-8');
        if (preg_match('/[\x{FF9E}-\x{FF9F}]/u', $next)) {
          $ch .= $next;
          $i++;
        }
      }
      // 全角ひら/カナ + 結合濁点/半濁点
      if ($i <= $len && preg_match('/[\x{3041}-\x{30FA}]/u', mb_substr($text, $i - 1, 1, 'UTF-8')) && $i < $len) {
        $next = mb_substr($text, $i, 1, 'UTF-8');
        if (preg_match('/[\x{3099}\x{309A}]/u', $next)) {
          $ch .= $next;
          $i++;
        }
      }

      $n = lp_fold_for_search($ch);
      $nlen = mb_strlen($n, 'UTF-8');
      for ($k = 0; $k < $nlen; $k++) $map[] = $start;
      $norm .= $n;
    }
    return [$norm, $map];
  }
}

/** クエリをトークン化（語＋フレーズ両対応） */
if (!function_exists('lp_query_tokens')) {
  function lp_query_tokens(string $query): array
  {
    $query = trim($query);
    if ($query === '') return [];
    $split  = preg_split('/[\p{Z}\s、,]+/u', $query, -1, PREG_SPLIT_NO_EMPTY);
    $tokens = $split ?: [];
    if (preg_match('/[\p{Z}\s]/u', $query)) $tokens[] = $query; // フレーズ
    $seen = [];
    $out = [];
    foreach ($tokens as $t) {
      $key = lp_fold_for_search($t);
      if ($key === '' || isset($seen[$key])) continue;
      $seen[$key] = true;
      $out[] = $t;
    }
    return $out;
  }
}

/** 本文（post_content）を常に使用してプレーン化 */
if (!function_exists('lp_news_plain_content')) {
  function lp_news_plain_content($post_id): string
  {
    $raw  = get_post_field('post_content', $post_id) ?: '';
    $text = wp_strip_all_tags((string)$raw, true);
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    $text = preg_replace('/\s*\[\s*(?:&hellip;|\x{2026}|\.{3})\s*\]\s*/u', ' … ', $text);
    $text = preg_replace('/\s+/u', ' ', $text);
    return trim($text);
  }
}

/** 正規化一致で合致区間を（元文字インデックスで）収集＆マージ */
if (!function_exists('lp_news_collect_ranges')) {
  function lp_news_collect_ranges(string $text, string $query): array
  {
    [$norm, $map] = lp_make_norm_and_map($text);
    $tokens = lp_query_tokens($query);
    if (!$tokens) return [];

    $ol = mb_strlen($text, 'UTF-8');
    $ranges = [];
    foreach ($tokens as $tok) {
      $t = lp_fold_for_search($tok);
      if ($t === '') continue;
      $off = 0;
      $tlen = mb_strlen($t, 'UTF-8');
      while (($pos = mb_strpos($norm, $t, $off, 'UTF-8')) !== false) {
        $origStart = $map[$pos] ?? 0;
        $lastNorm  = $pos + max(0, $tlen - 1);
        $origEnd   = ($map[$lastNorm] ?? ($ol - 1)) + 1;
        $ranges[]  = [$origStart, $origEnd];
        $off       = $pos + max(1, $tlen);
      }
    }
    if (!$ranges) return [];

    usort($ranges, fn($a, $b) => $a[0] <=> $b[0]);
    $merged = [];
    foreach ($ranges as $r) {
      if (!$merged || $merged[count($merged) - 1][1] < $r[0]) $merged[] = $r;
      else $merged[count($merged) - 1][1] = max($merged[count($merged) - 1][1], $r[1]);
    }
    return $merged;
  }
}

/** スニペット生成 */
if (!function_exists('lp_slice_snippet')) {
  function lp_slice_snippet(string $text, int $hitStart, int $maxLen = 140, int $context = 60): string
  {
    $len   = mb_strlen($text, 'UTF-8');
    $start = max(0, $hitStart - $context);
    $snippet = mb_substr($text, $start, $maxLen, 'UTF-8');
    $prefix  = $start > 0 ? '…' : '';
    $suffix  = ($start + $maxLen) < $len ? '…' : '';
    return $prefix . $snippet . $suffix;
  }
}

/** 複数一致→メイン＋追加スニペット */
if (!function_exists('lp_build_main_and_extra_snippets')) {
  function lp_build_main_and_extra_snippets(string $text, string $query, int $maxLen = 140, int $context = 60, int $maxExtras = 3): array
  {
    $ranges = lp_news_collect_ranges($text, $query);
    $len = mb_strlen($text, 'UTF-8');

    if (!$ranges) {
      $main = $len > $maxLen ? (mb_substr($text, 0, $maxLen, 'UTF-8') . '…') : $text;
      return [$main, []];
    }

    [$s0, $e0] = $ranges[0];
    $main      = lp_slice_snippet($text, $s0, $maxLen, $context);
    $mainStart = max(0, $s0 - $context);
    $mainEnd   = $mainStart + $maxLen;

    $extras = [];
    foreach (array_slice($ranges, 1) as [$s, $e]) {
      $mid = (int) floor(($s + $e) / 2);
      if ($mid >= $mainEnd || $mid <= $mainStart) {
        $extras[] = lp_slice_snippet($text, $s, $maxLen, $context);
        if (count($extras) >= $maxExtras) break;
      }
    }
    if (!$extras && $len > ($mainEnd + 80)) {
      $tailStart = max(0, $len - ($maxLen + 10));
      $extras[]  = '…' . mb_substr($text, $tailStart, $maxLen, 'UTF-8');
    }

    return [$main, $extras];
  }
}

/** 正規化一致に基づく <mark> ハイライト */
if (!function_exists('lp_news_highlight_html')) {
  function lp_news_highlight_html(string $text, string $query): string
  {
    if ($text === '' || trim($query) === '') return esc_html($text);
    $ranges = lp_news_collect_ranges($text, $query);
    if (!$ranges) return esc_html($text);

    $out = '';
    $cur = 0;
    $ol = mb_strlen($text, 'UTF-8');
    foreach ($ranges as [$s, $e]) {
      if ($cur < $s) $out .= esc_html(mb_substr($text, $cur, $s - $cur, 'UTF-8'));
      $hit = mb_substr($text, $s, $e - $s, 'UTF-8');
      $out .= '<mark class="news__hit">' . esc_html($hit) . '</mark>';
      $cur = $e;
    }
    if ($cur < $ol) $out .= esc_html(mb_substr($text, $cur, $ol - $cur, 'UTF-8'));
    return $out;
  }
}

/* ============================================================================
 * NEW / UPDATED 判定 & 表示日付
 * ========================================================================== */

// 既定（サイト要望に合わせて14日）
// NEW バッジ
add_filter('lp_news_new_badge_days',      static function ($days) {
  return 14; // 14日以内
}, 10, 1);
// 更新 バッジ
add_filter('lp_news_updated_badge_days',  static function ($days) {
  return 14; // 14日以内
}, 10, 1);

// 公開時刻と更新時刻の差が60秒を超えたときだけ「有意な更新」と判定 “公開直後の誤字修正”のような軽微更新を避けるためのガード
add_filter('lp_news_updated_epsilon',     static function ($sec) {
  return 60;
}, 10, 1);

/** NEW 判定：投稿日からN日以内 */
if (!function_exists('lp_news_is_new')) {
  function lp_news_is_new(int $post_id = 0): bool
  {
    $post_id  = $post_id ?: get_the_ID();
    $days     = (int) apply_filters('lp_news_new_badge_days', 7);
    $post_gmt = get_post_time('U', true, $post_id);
    $now_gmt  = current_time('timestamp', true);
    if (!$post_gmt) return false;
    return (($now_gmt - (int)$post_gmt) <= ($days * DAY_IN_SECONDS));
  }
}

/** 有意な更新があったか（公開と更新の差が eps 秒超） */
if (!function_exists('lp_news_has_significant_update')) {
  function lp_news_has_significant_update(int $post_id = 0): bool
  {
    $post_id = $post_id ?: get_the_ID();
    $eps     = (int) apply_filters('lp_news_updated_epsilon', 60);
    $pub_gmt = get_post_time('U', true, $post_id);
    $mod_gmt = get_post_modified_time('U', true, $post_id);
    if (!$pub_gmt || !$mod_gmt) return false;
    return (((int)$mod_gmt - (int)$pub_gmt) > $eps);
  }
}

/** UPDATED 判定：直近更新がN日以内 かつ 有意な更新 */
if (!function_exists('lp_news_is_updated')) {
  function lp_news_is_updated(int $post_id = 0): bool
  {
    $post_id  = $post_id ?: get_the_ID();
    $days     = (int) apply_filters('lp_news_updated_badge_days', 7);
    $now_gmt  = current_time('timestamp', true);
    $mod_gmt  = get_post_modified_time('U', true, $post_id);
    if (!$mod_gmt) return false;
    if (!lp_news_has_significant_update($post_id)) return false;
    return (($now_gmt - (int)$mod_gmt) <= ($days * DAY_IN_SECONDS));
  }
}

/** NEW優先でフラグをまとめて取得 */
if (!function_exists('lp_news_get_flags')) {
  function lp_news_get_flags(int $post_id = 0): array
  {
    $post_id    = $post_id ?: get_the_ID();
    $is_new     = lp_news_is_new($post_id);
    $is_updated = (!$is_new) && lp_news_is_updated($post_id);
    return [
      'new'         => (bool)$is_new,
      'updated'     => (bool)$is_updated,
      'significant' => (bool)lp_news_has_significant_update($post_id),
    ];
  }
}

/** 表示日付：有意な更新があれば更新日、なければ公開日 */
if (!function_exists('lp_news_get_display_date')) {
  function lp_news_get_display_date(int $post_id = 0): array
  {
    $post_id = $post_id ?: get_the_ID();
    if (lp_news_has_significant_update($post_id)) {
      return [
        'machine' => get_the_modified_date('Y-m-d', $post_id),
        'human'   => get_the_modified_date('Y.m.d', $post_id),
        'source'  => 'modified',
      ];
    }
    return [
      'machine' => get_the_date('Y-m-d', $post_id),
      'human'   => get_the_date('Y.m.d', $post_id),
      'source'  => 'date',
    ];
  }
}

/** ラベル（カテゴリ or タクソノミの先頭） */
if (!function_exists('lp_news_get_label')) {
  function lp_news_get_label(int $post_id = 0, string $post_type = 'post', string $fallback = 'お知らせ'): string
  {
    $post_id = $post_id ?: get_the_ID();
    if ($post_type === 'post') {
      $cats = get_the_category($post_id);
      return (!empty($cats)) ? $cats[0]->name : $fallback;
    }
    $taxes = get_object_taxonomies($post_type, 'names');
    if (!empty($taxes)) {
      foreach ($taxes as $tax) {
        $terms = get_the_terms($post_id, $tax);
        if ($terms && !is_wp_error($terms)) return $terms[0]->name;
      }
    }
    return $fallback;
  }
}

/* ============================================================================
 * 既定クエリ（modified DESC）
 * ========================================================================== */

/** modified DESC などの既定を含む引数を生成 */
if (!function_exists('lp_news_build_query_args')) {
  function lp_news_build_query_args(array $overrides = []): array
  {
    $base = [
      'post_status'         => 'publish',
      'orderby'             => 'modified',
      'order'               => 'DESC',
      'ignore_sticky_posts' => true,
    ];
    return array_replace($base, $overrides);
  }
}
