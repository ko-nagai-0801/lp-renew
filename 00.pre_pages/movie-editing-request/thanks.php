<?php
session_start();

// セッションデータの確認
if (empty($_SESSION['name']) || empty($_SESSION['email'])) {
  header('Location: ./');
  exit;
}

// セッションの破棄
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta property='og:type' content='website'>
  <meta property='og:title' content='株式会社ＬｉＮＥ ＰＡＲＫ'>
  <meta property='og:description' content='株式会社ＬｉＮＥ ＰＡＲＫはWEBサイト・ホームページやLPなどのWEB制作と動画編集や動画広告づくりなどの動画制作を中心にサービスを展開しています。お客様のご希望を叶えるべく全力で制作させていただきます。'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/css/swiper.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  <link rel="stylesheet" href="/css/base.css">
  <link rel="stylesheet" href="/css/header.css">
  <link rel="stylesheet" href="/css/request-movie-footer.css">
  <link rel="stylesheet" href="/css/request-movie.css">
    <link rel="stylesheet" href="/css/contact.css" />
  <link rel="apple-touch-icon" sizes="180x180" href="/img/common/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/img/common/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/img/common/favicon-16x16.png">
  <link rel="manifest" href="/img/common/site.webmanifest">
  <link rel="mask-icon" href="/img/common/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <title>ご依頼受付完了</title>
  <meta name="description" content="株式会社ＬｉＮＥ ＰＡＲＫのホームページです">
</head>

<body>
  <!--ヘッダー-->
  <?php include $_SERVER['DOCUMENT_ROOT']  . '/includes/header.html'; ?>

  <div class="p-subhero p-subhero-movie-products">
    <div class="p-subhero-heading">
      <h2 class="c-heading-primary-center u-cts">
        <span class="c-heading-subprimary-center c-heading-subprimary-center-white">Thanks</span>ご依頼完了
      </h2>
    </div>
  </div>
  <div class="thanks-body l-subsection">
    <div class="l-inner">
      <div class="thanks-heading">
        <div class="thanks-message-title">
          動画編集のご依頼を<br>
          受け付けました。<br />
          内容を確認中です。
        </div>
        <div class="thanks-message-text">
          <p>
            内容によっては、ご返信できかねる場合もございます。<br />ご了承ください。
          </p>
        </div>
      </div>
      <div class="back-link-btn">
        <a href="./" class="back-link-btn-blue">
          <p>ご依頼フォームに戻る ▶</p>
        </a>
      </div>
    </div>
  </div>

  <div id="toTopBtn" class="c-page-top">
    <a href="#" class="c-page-top-link">
      <img src="/img/common/aroow-white.svg" alt="ページトップへ" class="c-page-top-img" />
    </a>
  </div>

  <!--フッター -->
  <?php include $_SERVER['DOCUMENT_ROOT']  . '/includes/footer.html'; ?>

  <script src="/js/script.js"></script>
</body>

</html>