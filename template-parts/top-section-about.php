<?php

/**
 * template-parts/top-section-about.php
 */
if (! defined('ABSPATH')) {
    exit;
}
?>
<section id="about" class="about section section__card container-fluid" aria-labelledby="about__heading">
    <p class="about__big-text" data-text="ABOUT US" aria-hidden="true"></p>

    <div class="about__inner ">


        <!-- ===== About Text ===== -->
        <div class="about__content">
            <?php
            get_template_part(
                'components/section-header',
                null,
                [
                    'id' => '', // 見出しID（任意）
                    'sub' => 'About Us', // 小見出し
                    'title' => 'ＬｉＮＥ ＰＡＲＫについて', // メイン見出し
                    'tag' => 'h2', // h1〜h6（省略可）
                    'extra_class' => 'about__header' // 追加クラス（任意）
                ]
            );
            ?>
            <h3 class="about__catch section__catch">誰もが当たり前のことを、当たり前にできる世界へ</h3>

            <div class="about__text section__text">
                <p>できること、できないことには必ず環境要因があると私たちは考えます。</p>
                <p>「自分が正しい」ではなく、多様な人々が共存していることを理解し、<br>
                    一人ひとりの気持ちを共有しながら共に進んでいく――。</p>
                <p>私たちはそんな世界の実現を理想とし、<br>
                    その歯車の一つとなることを目指しています。</p>
            </div>
            <?php
            get_template_part(
                'components/cta',
                null,
                [
                    'url' => home_url('/about/'),
                    'label' => 'About Us',
                    'variant' => 'primary', // 'primary' or 'white'
                    'extra_class' => 'about__cta' // 追加クラス
                ]
            );
            ?>
        </div><!-- /.about__content -->


        <!-- ===== About images ===== -->
        <div class="about__visual parallax" data-parallax-speed="0.45">
            <figure class="about__image">
                <img src="<?php echo esc_url(get_theme_file_uri('assets/img/about-img-main.webp')); ?>"
                    alt="ＬｉＮＥ ＰＡＲＫのワークスペース" loading="lazy">
            </figure>
        </div><!-- /.about__visual -->






    </div><!-- /.about__inner -->
</section>



<style>
    /* ====== About CTA（ゴースト → 左から塗り）====== */
    #about .about__cta .button {
        --g1: var(--about-grad-1, #92d0f0);
        --g2: var(--about-grad-5, #5c98ff);
        --g3: var(--about-grad-6, #fb8484);
        --grad: linear-gradient(90deg, var(--g1), var(--g2), var(--g3));

        /* 既存の青背景を打ち消し */
        background: none !important;
        background-color: transparent !important;

        /* 枠は常にグラデ */
        border: 2px solid transparent;
        border-image: var(--grad) 1;

        /* 重ね順の基準にする（::before を下層、文字は上層） */
        position: relative;
        overflow: hidden;
        isolation: isolate;

        color: var(--c-navy);
        /* 初期は読める色 */
        box-shadow: 0 10px 22px rgba(0, 0, 0, .12);
        transition: color .35s, box-shadow .35s, transform .25s;
    }

    /* ← グラデ塗り。初期は横幅 0%（見えない） */
    #about .about__cta .button::before {
        content: "" !important;
        /* ← ここで再有効化 */
        position: absolute;
        inset: 0;
        border-radius: inherit;
        background: var(--grad);
        transform-origin: left center;
        transform: scaleX(0);
        transition: transform .55s cubic-bezier(.22, 1, .36, 1);
        z-index: 0;
        /* テキストより下 */
        pointer-events: none;
    }

    /* 矢印は常にテキストと同色・前面 */
    #about .about__cta .button::after {
        position: relative;
        z-index: 1;
        color: currentColor !important;
    }

    /* hover / focus：塗りを全幅にして白文字に */
    #about .about__cta .button:hover::before,
    #about .about__cta .button:focus-visible::before {
        transform: scaleX(1);
    }

    #about .about__cta .button:hover,
    #about .about__cta .button:focus-visible {
        color: #fff;
        box-shadow: 0 12px 26px rgba(0, 0, 0, .18);
        outline: none;
        border-radius: 0;
    }

    #about .about__cta .button:hover .btn-text {
        color: var(--f-white);
        z-index: 100;
    }

    /* 既存の .button:hover の背景指定を無効化（安全策） */
    #about .about__cta .button:hover {
        background: none !important;
    }

    /* reduce-motion 環境ではフェードに切替 */
    @media (prefers-reduced-motion: reduce) {
        #about .about__cta .button::before {
            transition: opacity .3s;
            transform: none;
            opacity: 0;
        }

        #about .about__cta .button:hover::before,
        #about .about__cta .button:focus-visible::before {
            opacity: 1;
        }
    }

    /* ===== 初期：文字＆矢印をグラデーションで描画 ===== */
    #about .about__cta .button .btn-text {
        background-image: var(--grad);
        /* 同じグラデを使用 */
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        /* 文字色は背景で塗る */
        -webkit-text-fill-color: transparent;
        position: relative;
        z-index: 1;
        /* ::before の上 */
    }

    /* 矢印（bootstrap-icons の疑似要素）もグラデ塗り */
    #about .about__cta .button::after {
        background-image: var(--grad) !important;
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        /* iOS/Safari */
        color: transparent !important;
        display: inline-block;
        /* クリップ安定 */
        position: relative;
        z-index: 1;
    }

    /* ===== ホバー／フォーカス：白文字へ ===== */
    #about .about__cta .button:hover .btn-text,
    #about .about__cta .button:focus-visible .btn-text {
        background-image: none;
        -webkit-text-fill-color: #fff;
        color: #fff;
    }

    #about .about__cta .button:hover::after,
    #about .about__cta .button:focus-visible::after {
        background-image: none !important;
        -webkit-text-fill-color: #fff;
        color: #fff !important;
    }

    /* モバイル（hover なし）では常に塗り＋白文字 */
    @media (hover: none) {
        #about .about__cta .button {
            /* 枠は残してもOK。外したいなら border: 0; に */
            border-image: none;
        }

        #about .about__cta .button::before {
            transform: scaleX(1);
            /* ← 既に全幅で塗っておく */
        }

        #about .about__cta .button .btn-text {
            background-image: none;
            -webkit-text-fill-color: #fff;
            color: #fff;
        }

        #about .about__cta .button::after {
            background-image: none !important;
            -webkit-text-fill-color: #fff;
            color: #fff !important;
        }

        /* タップの微小フィードバック */
        #about .about__cta .button:active {
            transform: translateY(1px) scale(.98);
            box-shadow: 0 8px 20px rgba(0, 0, 0, .16);
        }

        /* ヒットエリア確保と読みやすさ */
        #about .about__cta .button {
            min-height: 48px;
            /* 親指サイズ */
            padding: .9rem 1.25rem;
            font-size: 1.0625rem;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
            touch-action: manipulation;
        }
    }
</style>