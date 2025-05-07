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
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="/css/base.css" />
  <link rel="stylesheet" href="/css/header.css?v=2">
  <link rel="stylesheet" href="/css/footer.css">
  <link rel="stylesheet" href="/css/contact.css" />
  <link rel="apple-touch-icon" sizes="180x180" href="/img/common/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="/img/common/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="/img/common/favicon-16x16.png" />
  <link rel="manifest" href="/img/common/site.webmanifest" />
  <link rel="mask-icon" href="/img/common/safari-pinned-tab.svg" color="#5bbad5" />
  <meta name="msapplication-TileColor" content="#da532c" />
  <title>お問い合わせ受付完了</title>
</head>

<body>
  <!-- header -->
  <?php include $_SERVER['DOCUMENT_ROOT']  . '/includes/header.html'; ?>

  <div class="p-subhero contact-view">
    <div class="p-subhero-heading">
      <span class="c-heading-subprimary-center c-heading-subprimary-center-white">Contact us</span>
      <h2 class="c-heading-primary-center u-cts">
        お問い合わせ
      </h2>
    </div>
  </div>
  <div class="thanks-body l-subsection">
    <div class="l-inner">
      <div class="thanks-heading">
        <div class="thanks-message-title">
          お問い合わせを受け付けました。<br />
          内容を確認中です。
        </div>
        <div class="thanks-message-text">
          <p>
            内容によっては、ご返信できかねる場合もございます。<br />ご了承ください。
          </p>
        </div>
      </div>
      <div class="back-link-btn">
        <a href="/" class="back-link-btn-blue">
          <p>ホームに戻る ▶</p>
        </a>
      </div>
    </div>
  </div>

	<!-- to TOP button -->
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/to-top.html'; ?>

  <!-- footer -->
  <?php include $_SERVER['DOCUMENT_ROOT']  . '/includes/footer.html'; ?>

  <!-- Swipper -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/js/swiper.min.js"></script>
  <!-- GSAP CDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/ScrollTrigger.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  <script src="/js/script.js"></script>
</body>

</html>