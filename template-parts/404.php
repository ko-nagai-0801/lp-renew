<?php
/**
 * 404ページ本体
 * template-parts/404.php
 * 
 * @package LP_WP_Theme
 * @since 1.0.0
 */
if (!defined('ABSPATH')) exit;
?>

<main class="page page--notfound" role="main">
    <section class="section notfound" aria-labelledby="notfound-heading">
        <div class="container">

            <header class="section__header notfound__header">
                <p class="section__sub">Oops!</p>
                <h1 id="notfound-heading" class="section__title">ページが見つかりませんでした</h1>
            </header>

            <!-- 大きな 404 ビジュアル -->
            <div class="notfound__hero" aria-hidden="true">
                <span class="notfound__digit" style="--i:0">4</span>
                <span class="notfound__digit" style="--i:1">0</span>
                <span class="notfound__digit" style="--i:2">4</span>
            </div>

            <p class="notfound__lead">
                入力したURLが間違っているか、ページが移動・削除された可能性があります。
            </p>

            <!-- アクション群 -->
            <nav class="notfound__actions" aria-label="便利なリンク">
                <a class="notfound__btn notfound__btn--primary" href="<?php echo esc_url(home_url('/')); ?>">トップへ戻る</a>
                <a class="notfound__btn" href="javascript:history.back()">ひとつ前のページへ</a>
                <a class="notfound__btn" href="<?php echo esc_url(home_url('/contact/')); ?>">お問い合わせ</a>
            </nav>

        </div>
    </section>
</main>