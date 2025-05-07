<?php
// セッション開始
session_start();

// エラーメッセージの初期化
$errorMsg = '';
unset($_SESSION['inquiryType']);
unset($_SESSION['user_id']);

// POSTリクエストの確認
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // CSRFトークンの検証
  if (
    isset($_POST['csrf_token'], $_SESSION['csrf_token'], $_SESSION['csrf_token_time']) &&
    $_POST['csrf_token'] === $_SESSION['csrf_token'] &&
    time() - $_SESSION['csrf_token_time'] <= 1800
  ) {

    // フォームデータの受け取り、セッションに保存
    $_SESSION['name'] = $_POST['name'] ?? '';
    $_SESSION['kana'] = $_POST['kana'] ?? '';
    $_SESSION['companyName'] = $_POST['companyName'] ?? '';
    $_SESSION['email'] = $_POST['email'] ?? '';
    $_SESSION['confirmEmail'] = $_POST['confirmEmail'] ?? '';
    $_SESSION['telNumber'] = $_POST['telNumber'] ?? '';
    $_SESSION['inquiry'] = $_POST['inquiry'] ?? '';
  } else {
    $errorMsg = 'CSRFトークンが無効、または期限切れです。';
    header('Location: ./'); // コメントアウト
    exit; // コメントアウト
  }
} else {
  $errorMsg = '不正なアクセスです。';
  // header('Location: ./'); // コメントアウト
  // exit; // コメントアウト
}

// セッション変数が設定されていない場合のチェック
if (empty($_SESSION['name']) || empty($_SESSION['email'])) {
  $errorMsg = 'セッションが存在しません。';
  header('Location: ./'); // コメントアウト
  exit; // コメントアウト
}

// トークンを再生成
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="description" content="株式会社ＬｉＮＥ ＰＡＲＫのホームページです" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="株式会社ＬｉＮＥ ＰＡＲＫ" />
  <meta property="og:description" content="ＬｉＮＥ ＰＡＲＫへのお問い合わせ" />

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />

  <!-- CSS BootStrap CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">

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
  <title>お問い合わせ</title>
</head>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-HTFSPPVNFH"></script>
<script>
  window.dataLayer = window.dataLayer || [];

  function gtag() {
    dataLayer.push(arguments);
  }
  gtag('js', new Date());

  gtag('config', 'G-HTFSPPVNFH');
</script>

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

  <div class="thanks-heading">
    <div class="thanks-message-title">
      お問い合わせの内容をご確認ください。
    </div>
  </div>

  <div class="form-block mx-auto mb-5">
    <form action="sendmail.php" method="post">
      <?php echo '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">'; ?>
      <div class="mb-4">
        <h5>お名前</h5>
        <h4 class="h3"><?php echo htmlspecialchars($_SESSION['name']); ?></h4>
      </div>
      <div class="mb-4">
        <h5>会社名</h5>
        <h4 class="h3"><?php echo htmlspecialchars($_SESSION['companyName']); ?></h4>
      </div>
      <div class="mb-4">
        <h5>メールアドレス</h5>
        <h4 class="h3"><?php echo htmlspecialchars($_SESSION['email']); ?></h4>
      </div>
      <div class="mb-4">
        <h5>お電話番号</h5>
        <h4 class="h3"><?php echo htmlspecialchars($_SESSION['telNumber']); ?></h4>
      </div>
      <div class="mb-4">
        <h5>お問い合わせ内容</h5>
        <h4 class="h3"><?php echo nl2br(htmlspecialchars($_SESSION['inquiry'])); ?></h4>
      </div>
      <div class="d-flex justify-content-center">
        <a href="./" class="btn btn-secondary me-3">修正する</a>
        <button type="submit" class="btn btn-primary">送信する</button>
      </div>
    </form>
  </div>
  </section>

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