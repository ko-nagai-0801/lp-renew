<?php
session_start();

// 現在のURLのパスを取得
$currentPath = $_SERVER['REQUEST_URI'];

// /contact/ディレクトリ外へのアクセスをチェック
if (!preg_match('/^\/contact\//', $currentPath)) {
  // セッション変数を全て削除
  $_SESSION = array();

  // セッションクッキーを削除
  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
      session_name(),
      '',
      time() - 42000,
      $params["path"],
      $params["domain"],
      $params["secure"],
      $params["httponly"]
    );
  }

  // セッションを破棄してから新たに開始
  session_destroy();
  session_start();
}

// トークンと現在のタイムスタンプを生成してセッションに保存
$csrfToken = bin2hex(random_bytes(32));
$tokenTime = time();
$_SESSION['csrf_token'] = $csrfToken;
$_SESSION['csrf_token_time'] = $tokenTime;

$errors = [];
$name = $_SESSION['name'] ?? '';
$kana = $_SESSION['kana'] ?? '';
$companyName = $_SESSION['companyName'] ?? '';
$email = $_SESSION['email'] ?? '';
$confirmEmail = $_SESSION['confirmEmail'] ?? '';
$telNumber = $_SESSION['telNumber'] ?? '';
$inquiry = $_SESSION['inquiry'] ?? '';

// フォームが送信された場合の処理
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['name'] ?? '';
  $_SESSION['name'] = $name;

  $kana = $_POST['kana'] ?? '';
  $_SESSION['kana'] = $kana;

  $companyName = $_POST['companyName'] ?? '';
  $_SESSION['companyName'] = $companyName;

  $email = $_POST['email'] ?? '';
  $_SESSION['email'] = $email;

  $confirmEmail = $_POST['confirmEmail'] ?? '';
  $_SESSION['confirmEmail'] = $confirmEmail;

  $telNumber = $_POST['telNumber'] ?? '';
  $_SESSION['telNumber'] = $telNumber;

  $inquiry = $_POST['inquiry'] ?? '';
  $_SESSION['inquiry'] = $inquiry;

  // 必須項目の入力・バリデーションチェック
  // お名前
  $name = trim($name);
  if (!empty($name)) {
    if (strlen($name) > 32) {
      $errors['name'] = 'お名前は32文字以内で入力してください。';
    } elseif (!preg_match('/[ 　]+/u', $name)) {
      $errors['name'] = '姓と名の間は1文字空けてください。';
    }
  } else {
    $errors['name'] = 'お名前は必須です。';
  }

  $kana = trim($kana);
  // ふりがな
  if (!empty($kana)) {
    if (strlen($kana) > 32) {
      $errors['kana'] = 'ふりがなは32文字以内で入力してください。';
    }
    // ひらがなまたは半角スペースのみで構成されているかチェック
    elseif (!preg_match('/^[ぁ-んー 　]+$/u', $kana)) {
      $errors['kana'] = 'ふりがなはひらがなで入力してください（姓と名の間には空白を入れてください）。';
    }
    // 姓と名の間に空白があるかチェック
    elseif (!preg_match('/[ 　]+/u', $kana)) {
      $errors['kana'] = '姓と名の間は1文字空けてください。';
    }
  } else {
    $errors['kana'] = 'ふりがなは必須です。';
  }


  // 会社名
  if (strlen($companyName) > 64) {
    $errors['companyName'] = '会社名は64文字以内で入力してください。';
  }

  // メールアドレス
  if (empty($email)) {
    $errors['email'] = 'メールアドレスは必須です。';
  } elseif (strlen($email) > 64) {
    $errors['email'] = 'メールアドレスは64文字以内で入力してください。';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = '正しいメールアドレスを入力してください。';
  }

  // 確認用メールアドレス
  if ($email !== $confirmEmail) {
    $errors['confirmEmail'] = 'メールアドレスと確認用メールアドレスが一致しません。';
  }

  // 電話番号
  $telNumber = trim($telNumber);

  if (!empty($telNumber)) {
    // 文字数チェック（全角文字を考慮して mb_strlen を使用）
    if (mb_strlen($telNumber) > 32) {
      $errors['telNumber'] = '電話番号は32文字以内で入力してください。';
    }
    // 半角・全角数字、ハイフンのみで構成されているかチェック
    elseif (!preg_match('/^[0-9０-９\-－]+$/u', $telNumber)) {
      $errors['telNumber'] = '電話番号の形式が正しくありません。（数字、ハイフンのみ使用可）';
    }
  } else {
    $errors['telNumber'] = '電話番号は必須です。';
  }


  // お問い合わせ内容
  if (empty($inquiry)) {
    $errors['inquiry'] = 'お問い合わせ内容を入力してください。';
  } elseif (strlen($inquiry) > 500) {
    $errors['inquiry'] = 'お問い合わせ内容は500文字以内で入力してください。';
  }

  // エラーがなければconfirm.phpへリダイレクト
  if (empty($errors)) {
    header('Location: confirm.php');
    exit;
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">

  <link rel="canonical" href="https://www.linepark.co.jp/">

  <meta property="og:type" content="website">
  <meta property="og:url" content="https://www.linepark.co.jp/">
  <!-- サイト全体のタイトル -->
  <meta property="og:site_name" content="株式会社ＬｉＮＥ ＰＡＲＫ">
  <!-- ページ固有のタイトル -->
  <meta property="og:title" content="お問い合わせ">
  <!-- 1200x630pxの画像を指定 -->
  <meta property="og:image" content="https://www.linepark.co.jp/img/ogp.jpg">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:image:alt" content="株式会社ＬｉＮＥ ＰＡＲＫの紹介画像">
  <meta property="og:description" content="ＬｉＮＥ ＰＡＲＫへのお問い合わせはこちら">
  <meta property="og:locale" content="ja_JP">
  <meta property="og:updated_time" content="2024-11-12T15:30:00+09:00">

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="株式会社ＬｉＮＥ ＰＡＲＫ">
  <meta name="twitter:description" content="ＬｉＮＥ ＰＡＲＫへのお問い合わせはこちら">
  <meta name="twitter:image" content="https://www.linepark.co.jp/img/ogp.jpg">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

  <link rel="stylesheet" href="/css/base.css">
  <link rel="stylesheet" href="/css/header.css?v=2">
  <link rel="stylesheet" href="/css/footer.css?v=2">
  <link rel="stylesheet" href="/css/contact.css?v=2">
  <link rel="apple-touch-icon" sizes="180x180" href="/img/common/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/img/common/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/img/common/favicon-16x16.png">
  <link rel="manifest" href="/img/common/site.webmanifest">
  <link rel="mask-icon" href="/img/common/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">
  <meta name="format-detection" content="telephone=no">
  <title>お問い合わせ</title>
  <meta name="description" content="ＬｉＮＥ ＰＡＲＫへのお問い合わせはこちら">
  <meta name="keywords" content="就労継続支援B型,就労継続支援事業所, B型, 足立区, 綾瀬, Web制作, デザイン制作, 動画編集, バナー作成, チラシデザイン, 障害福祉, ホームページ制作">

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
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

  <section class="p-subhero contact-view">
    <div class="p-subhero-heading">
      <span class="c-heading-subprimary-center c-heading-subprimary-center-white">Contact us</span>
      <h2 class="c-heading-primary-center u-cts">
        お問い合わせ
      </h2>
    </div>
  </section>

  <section class="contact">
    <div class="contact-greeting">
      <p>
        この度は<span class="LiNE">ＬｉＮＥ</span> <span class="PARK">ＰＡＲＫ</span> のホームページをご覧いただき<br />
        誠にありがとうございます。
      </p>
    </div>
    <div class="contact-reception">
      <p class="reception-time">☎03-4400-5584</p>
      <p>（平日 9:00～16:00 対応可能）</p>
      <br />
      <p>
        ご不明な点やサービスについては、<br />以下のフォームよりお問い合わせください。
      </p>
    </div>
  </section>

  <section class="mb-5 py-5">
    <div class="container">
      <div class="mb-3">
        <div class="row d-flex justify-center">
          <div class="form-block mx-auto">
            <form action="" method="POST">
              <?php echo '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">'; ?>
              <!-- お名前 -->
              <div class="form-group d-flex flex-column justify-center mb-3">
                <label for="name" class="mb-2 me-5">お名前 <span class="badge text-bg-danger">必須</span>
                  <br><span class="ms-3 small">姓と名の間は１文字空けて下さい。</span></label>
                <div class="d-flex flex-column">
                  <input type="text" name="name" class="form-control d-block" id="name" placeholder="お名前を入力してください" maxlength="32" value="<?php echo htmlspecialchars($name); ?>">
                  <!-- <span class="small">（32文字以内）</span> -->
                  <?php if (!empty($errors['name'])) : ?>
                    <div class="error text-danger"><?php echo $errors['name']; ?></div>
                  <?php endif; ?>
                </div>
              </div>
              <!-- ふりがな -->
              <div class="form-group d-lg-flex flex-column justify-center mb-3">
                <label for="kana" class="mb-2 me-5">おなまえ（ふりがな） <span class="badge text-bg-danger">必須</span>
                  <br><span class="ms-3 small">姓と名の間は１文字空けて下さい。</span></label>
                <div class="d-flex flex-column">
                  <input type="text" name="kana" class="form-control d-block" id="kana" placeholder="ふりがなを入力してください" maxlength="32" value="<?php echo htmlspecialchars($kana); ?>">
                  <!-- <span class="small">（32文字以内）</span> -->
                  <?php if (!empty($errors['kana'])) : ?>
                    <div class="error text-danger"><?php echo $errors['kana']; ?></div>
                  <?php endif; ?>
                </div>
              </div>
              <!-- 会社名 -->
              <div class="form-group d-lg-flex flex-column justify-center mb-3">
                <label for="companyName" class="mb-2 me-5">会社名 <span class="badge text-bg-secondary">任意</span></label>
                <div class="d-flex flex-column">
                  <input type="text" name="companyName" class="form-control d-block" id="companyName" placeholder="会社名を入力してください" maxlength="64" value="<?php echo htmlspecialchars($companyName); ?>">
                  <!-- <span class="small">（64文字以内）</span> -->
                  <?php if (!empty($errors['companyName'])) : ?>
                    <div class="error text-danger"><?php echo $errors['companyName']; ?></div>
                  <?php endif; ?>
                </div>
              </div>
              <!-- メールアドレス -->
              <div class="form-group d-lg-flex flex-column justify-center mb-3">
                <label for="email" class="mb-2 me-5">メールアドレス <span class="badge text-bg-danger">必須</span></label>
                <div class="d-flex flex-column">
                  <input type="email" name="email" class="form-control d-block" id="email" placeholder="メールアドレスを入力してください" maxlength="64" value="<?php echo htmlspecialchars($email); ?>">
                  <!-- <span class="small">（64文字以内）</span> -->
                  <?php if (!empty($errors['email'])) : ?>
                    <div class="error text-danger"><?php echo $errors['email']; ?></div>
                  <?php endif; ?>
                </div>
              </div>
              <!-- メールアドレス（確認用） -->
              <div class="form-group d-lg-flex flex-column justify-center mb-3">
                <label for="confirmEmail" class="mb-2 me-5">メールアドレス（確認用） <span class="badge text-bg-danger">必須</span></label>
                <div class="d-flex flex-column">
                  <input type="email" name="confirmEmail" class="form-control d-block" id="confirmEmail" placeholder="確認用メールアドレスを入力してください" maxlength="64" value="<?php echo htmlspecialchars($confirmEmail); ?>">
                  <span class="small">（上記と同じメールアドレスを入力してください）</span>
                  <?php if (!empty($errors['confirmEmail'])) : ?>
                    <div class="error text-danger"><?php echo $errors['confirmEmail']; ?></div>
                  <?php endif; ?>
                </div>
              </div>

              <!-- 電話番号 -->
              <div class="form-group d-lg-flex flex-column justify-center mb-3">
                <label for="telNumber" class="mb-2 me-5">お電話番号 <span class="badge text-bg-danger">必須</span></label>
                <div class="d-flex flex-column">
                  <input type="tel" name="telNumber" class="form-control d-block" id="telNumber" placeholder="電話番号を入力してください" maxlength="32" value="<?php echo htmlspecialchars($telNumber); ?>">
                  <!-- <span class="small">（32文字以内）</span> -->
                  <?php if (!empty($errors['telNumber'])) : ?>
                    <div class="error text-danger"><?php echo $errors['telNumber']; ?></div>
                  <?php endif; ?>
                </div>
              </div>

              <!-- お問合せ内容 -->
              <div class="form-group d-lg-flex flex-column justify-center mb-3">
                <label for="inquiry" class="mb-2 me-5">お問合せ内容 <span class="badge text-bg-danger">必須</span></label>
                <div class="d-flex flex-column">
                  <textarea name="inquiry" class="form-control" id="inquiry" rows="10" placeholder="お問い合わせ内容を入力してください" maxlength="500"><?php echo htmlspecialchars($inquiry); ?></textarea>
                  <span class="small">（500文字以内）</span>
                  <?php if (!empty($errors['inquiry'])) : ?>
                    <div class="error text-danger"><?php echo $errors['inquiry']; ?></div>
                  <?php endif; ?>
                </div>
              </div>
              <!-- 送信ボタン -->
              <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-outline-primary btn-lg">確認ページへ</button>
              </div>
            </form>
          </div>

        </div>
      </div>
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