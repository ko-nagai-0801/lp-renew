<?php
session_start();
session_regenerate_id(true);  // セッションIDの再生成

// セッションからデータを取得し、なければデフォルト値を設定
$estimateData = $_SESSION['estimate_data'] ?? [
  'plan' => '',
  'videoLength' => 1,
  'videoSourceLength' => 1,
  'photoCount' => 0,
  'options' => []
];

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
  // CSRFトークンのチェック
  if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $errors['csrf'] = '無効なCSRFトークンです。';
  }

  $name = trim($_POST['name'] ?? '');
  $_SESSION['name'] = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

  $kana = trim($_POST['kana'] ?? '');
  $_SESSION['kana'] = htmlspecialchars($kana, ENT_QUOTES, 'UTF-8');

  $companyName = trim($_POST['companyName'] ?? '');
  $_SESSION['companyName'] = htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8');

  $email = trim($_POST['email'] ?? '');
  $_SESSION['email'] = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

  $confirmEmail = trim($_POST['confirmEmail'] ?? '');
  $_SESSION['confirmEmail'] = htmlspecialchars($confirmEmail, ENT_QUOTES, 'UTF-8');

  $telNumber = trim($_POST['telNumber'] ?? '');
  $_SESSION['telNumber'] = htmlspecialchars($telNumber, ENT_QUOTES, 'UTF-8');

  $inquiry = trim($_POST['inquiry'] ?? '');
  $_SESSION['inquiry'] = htmlspecialchars($inquiry, ENT_QUOTES, 'UTF-8');

  // 必須項目の入力・バリデーションチェック
  // お名前
  if (!empty($name)) {
    if (strlen($name) > 32) {
      $errors['name'] = 'お名前は32文字以内で入力してください。';
    } elseif (!preg_match('/[ 　]+/u', $name)) {
      $errors['name'] = '姓と名の間は1文字空けてください。';
    }
  } else {
    $errors['name'] = 'お名前は必須です。';
  }

  // ふりがな
  if (!empty($kana)) {
    if (strlen($kana) > 32) {
      $errors['kana'] = 'ふりがなは32文字以内で入力してください。';
    } elseif (!preg_match('/^[ぁ-んー 　]+$/u', $kana)) {
      $errors['kana'] = 'ふりがなはひらがなで入力してください（姓と名の間には空白を入れてください）。';
    } elseif (!preg_match('/[ 　]+/u', $kana)) {
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
  if (!empty($telNumber)) {
    if (mb_strlen($telNumber) > 32) {
      $errors['telNumber'] = '電話番号は32文字以内で入力してください。';
    } elseif (!preg_match('/^[0-9０-９\-－]+$/u', $telNumber)) {
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

// オプションの選択状態をJavaScriptで使用できるようにJSON形式でエンコード
$estimateDataJson = json_encode($estimateData);

// JavaScriptでデータを使用するためにページ内に埋め込む
echo "<script>let initialData = $estimateDataJson;</script>";
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
  <link rel="stylesheet" href="/css/header.css?v=2">
  <link rel="stylesheet" href="/css/request-footer.css">
  <link rel="stylesheet" href="/css/request-movie.css">
  <link rel="apple-touch-icon" sizes="180x180" href="/img/common/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/img/common/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/img/common/favicon-16x16.png">
  <link rel="manifest" href="/img/common/site.webmanifest">
  <link rel="mask-icon" href="/img/common/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <title>動画編集ご依頼フォーム</title>
  <meta name="description" content="株式会社ＬｉＮＥ ＰＡＲＫのホームページです">

  <!-- vue3 -->
  <script src="https://unpkg.com/vue@3"></script>
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

  <!--ヘッダー-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.html'; ?>

  <div class="p-subhero p-subhero-movie-products">
    <div class="p-subhero-heading">
      <span class="c-heading-subprimary-center c-heading-subprimary-center-white">Movie Editing</span>
      <h2 class="c-heading-primary-center u-cts">
        動画編集
      </h2>
    </div>
  </div>

  <main class="l-contents">
    <section class="p-movie l-subsection">
      <div class="p-movie-inner l-inner">
        <div class="p-movie-heading">
          <h3 class="c-heading-primary u-cts"><span class="c-heading-subprimary">Movie Editing</span>動画編集</h3>
        </div>

        <div id="app" class="container my-5">
          <h1>依頼フォーム</h1>
          <form action="" method="post">
            <!-- CSRFトークンを追加 -->
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken); ?>">

            <div class="mb-3">
              <label class="form-label">プラン選択</label>
              <div class="form-check">
                <input class="form-check-input" type="radio" id="detailedPlan" value="detailed" v-model="selectedPlan" name="plan">
                <label class="form-check-label" for="detailedPlan">こだわりプラン (9,900円)</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" id="simplePlan" value="simple" v-model="selectedPlan" name="plan">
                <label class="form-check-label" for="simplePlan">シンプルプラン (5,940円)</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" id="photoPlan" value="photo" v-model="selectedPlan" name="plan">
                <label class="form-check-label" for="photoPlan">Photoプラン (4,950円)</label>
              </div>
            </div>
            <div class="mb-3">
              <label for="videoLength" class="form-label">完成動画の長さ（分）:</label>
              <input type="number" class="form-control" id="videoLength" name="videoLength" v-model.number="videoLength">
            </div>
            <div class="mb-3" v-if="selectedPlan !== 'photo'">
              <label for="videoSourceLength" class="form-label">元動画の長さ（分）:</label>
              <input type="number" class="form-control" id="videoSourceLength" name="videoSourceLength" v-model.number="videoSourceLength">
            </div>
            <div class="mb-3" v-if="selectedPlan === 'photo'">
              <label for="photoCount" class="form-label">素材となる写真の枚数:</label>
              <input type="number" class="form-control" id="photoCount" name="photoCount" v-model.number="photoCount">
            </div>
            <!-- オプション -->
            <div class="mb-3">
              <div class="form-check" v-for="option in options" :key="option.id">
                <input class="form-check-input" type="checkbox" :id="option.id" :value="option.id" v-model="selectedOptions">
                <label class="form-check-label" :for="option.id">{{ option.name }} ({{ option.price ? formatPrice(option.price) + (option.suffix) : option.priceInfo }})</label>
              </div>
            </div>
            <!-- お見積もり金額の表示 -->
            <div class="my-3">
              <h3 class="total-estimate d-block">
                お見積もり金額: {{ formatPrice(totalEstimate) }}円 <span v-if="hasConsultation">＋有料コンテンツ（画像・BGMなど）追加料</span>
              </h3>
            </div>

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
                <textarea name="inquiry" class="form-control" id="inquiry" rows="10" placeholder="お問い合わせ内容を入力してください" maxlength="1000"><?php echo htmlspecialchars($inquiry); ?></textarea>
                <span class="small">（1000文字以内）</span>
                <?php if (!empty($errors['inquiry'])) : ?>
                  <div class="error text-danger"><?php echo $errors['inquiry']; ?></div>
                <?php endif; ?>
              </div>
            </div>
            <!-- 送信ボタン -->
            <div class="d-flex justify-content-center">
              <div class="p-contact-link">
                <button type="submit" class="c-btn c-btn-skyblue u-cts">確認ページへ</button>
              </div>
            </div>
          </form>

        </div>
    </section>
  </main>

  <!-- contact -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/contact.html'; ?>
  <!-- contact -->

  <!--フッター -->
  <?php include $_SERVER['DOCUMENT_ROOT']  . '/includes/footer.html'; ?>

  <div id="toTopBtn" class="c-page-top">
    <a href="#" class="c-page-top-link">
      <img src="/img/common/aroow-white.svg" alt="ページトップへ" class="c-page-top-img" />
    </a>
  </div>

  <!-- Vue.js の初期化と設定 -->
  <!-- <script>
    Vue.createApp({
      data() {
        return {
          selectedPlan: initialData.plan,
          videoLength: initialData.videoLength,
          videoSourceLength: initialData.videoSourceLength,
          photoCount: initialData.photoCount,
          options: [{
              id: 'channelSetup',
              name: 'チャンネル及びアカウントの立ち上げ',
              price: 1650,
              suffix: '円 / 件'
            },
            {
              id: 'thumbnailCreation',
              name: 'サムネイル作成',
              price: 3300,
              suffix: '円 / 枚'
            },
            {
              id: 'photoInsertion',
              name: '写真素材の挿入',
              price: 330,
              suffix: '円 / 枚'
            },
            {
              id: 'openingCreation',
              name: 'オープニング作成',
              price: 3300,
              suffix: '円 ～'
            },
            {
              id: 'endingCreation',
              name: 'エンディング作成',
              price: 3300,
              suffix: '円 ～'
            },
            {
              id: 'materialCreation',
              name: '素材作成',
              price: 3300,
              suffix: '円 ～'
            },
            {
              id: 'paidPhotos',
              name: '有料写真や画像・イラストの挿入',
              priceInfo: '応相談'
            },
            {
              id: 'paidBGM',
              name: '有料BGMの追加',
              priceInfo: '応相談'
            },
            {
              id: 'planning',
              name: '企画・構成',
              price: 22000,
              suffix: '円 ～'
            }
          ],
          selectedOptions: initialData.options
        };
      },
      methods: {
        formatPrice(value) {
          return new Intl.NumberFormat('ja-JP').format(value);
        }
      },
      computed: {
        totalEstimate() {
          let total = 0;
          let additionalMinutes = Math.max(this.videoLength - 1, 0);
          switch (this.selectedPlan) {
            case 'simple':
              total += 5940 + additionalMinutes * 550;
              break;
            case 'detailed':
              total += 9900 + additionalMinutes * 1100;
              break;
            case 'photo':
              total += 4950 + additionalMinutes * 550;
              break;
          }
          this.selectedOptions.forEach(optionId => {
            const option = this.options.find(o => o.id === optionId);
            if (option && option.price) {
              total += option.price;
            }
          });
          return total;
        },
        hasConsultation() {
          return this.selectedOptions.some(optionId => {
            const option = this.options.find(o => o.id === optionId);
            return option && option.priceInfo === '応相談';
          });
        }
      }
    }).mount('#app');
  </script> -->

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/js/swiper.min.js"></script>
  <script src="/js/movie-request.js"></script>
</body>

</html>