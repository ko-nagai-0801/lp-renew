<?php

/**
 * ページトップへ戻る（固定丸ボタン + スムーススクロール）
 * components/to-top.php
 *
 * @package LP_WP_Theme
 * @since 1.0.0
 *
 * 更新履歴:
 * - 1.0.0 (2025-09-05): 初版。Bootstrap Icons使用、reduce-motion対応、スムーズスクロール、表示切替実装。
 */
if (!defined('ABSPATH')) exit;

/**
 * body直下に #top アンカーを一度だけ出力
 * - a[href="#top"] のジャンプ先を確実にする
 * - 既に他所で #top がある場合でも害はない（重複IDだけ避けたい人は適宜調整）
 */
if (!function_exists('lp_output_top_anchor')) {
    function lp_output_top_anchor()
    {
        echo '<span id="top" class="visually-hidden" aria-hidden="true"></span>' . PHP_EOL;
    }
    add_action('wp_body_open', 'lp_output_top_anchor', 0);
}
?>

<!-- Page Top Button（初期は非表示。JSで .is-hidden をトグル） -->
<a href="#top"
    id="js-pageTop"
    class="page-top-link is-hidden"
    aria-label="ページトップへ">
    <i class="bi bi-chevron-up" aria-hidden="true"></i>
</a>

<script>
    (function() {
        'use strict';

        // ===== Reduce Motion を尊重してスクロール挙動を決める =====
        const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const behavior = prefersReduced ? 'auto' : 'smooth';

        // ===== 要素取得 =====
        const btn = document.getElementById('js-pageTop');
        if (!btn) return;

        // ===== 何pxスクロールしたら出すか =====
        const THRESHOLD = 200;

        // ===== スクロールで表示/非表示を切替 =====
        const onScroll = () => {
            const y = window.pageYOffset || document.documentElement.scrollTop;
            if (y > THRESHOLD) {
                btn.classList.remove('is-hidden');
            } else {
                btn.classList.add('is-hidden');
            }
        };

        // 初期判定
        onScroll();

        // 監視（passive でパフォーマンス配慮）
        window.addEventListener('scroll', onScroll, {
            passive: true
        });
        window.addEventListener('resize', onScroll);

        // ===== クリックでトップへ戻る（URLハッシュを書き換えない） =====
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                left: 0,
                behavior: behavior
            });
        }, {
            passive: false
        });
    })();
</script>