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

// トークンと現在のタイムスタンプを生成してセッションに保存
$csrfToken = bin2hex(random_bytes(32));
$tokenTime = time();
$_SESSION['csrf_token'] = $csrfToken;
$_SESSION['csrf_token_time'] = $tokenTime;

$errors = [];
$name = $_SESSION['name'] ?? '';
$kana = $_SESSION['kana'] ?? '';
$companyName = $_SESSION['companyName'] ?? '';
$companyNameKana = $_SESSION['companyNameKana'] ?? '';
$email = $_SESSION['email'] ?? '';
$confirmEmail = $_SESSION['confirmEmail'] ?? '';
$telNumber = $_SESSION['telNumber'] ?? '';
$inquiry = $_SESSION['inquiry'] ?? '';
$meeting1 = $_SESSION['meeting1'] ?? '';
$meeting2 = $_SESSION['meeting2'] ?? '';
$meeting3 = $_SESSION['meeting3'] ?? '';
$agreement = $_SESSION['agreement'] ?? '';

// フォームが送信された場合の処理
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // CSRFトークンのチェック
  if (isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    $errors['csrf'] = '無効なCSRFトークンです。';
  } else {
    $name = trim($_POST['name'] ?? '');
    $kana = trim($_POST['kana'] ?? '');
    $companyName = trim($_POST['companyName'] ?? '');
    $companyNameKana = trim($_POST['companyNameKana'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $confirmEmail = trim($_POST['confirmEmail'] ?? '');
    $telNumber = trim($_POST['telNumber'] ?? '');
    $inquiry = trim($_POST['inquiry'] ?? '');
    $meeting1 = trim($_POST['meeting1'] ?? '');
    $meeting2 = trim($_POST['meeting2'] ?? '');
    $meeting3 = trim($_POST['meeting3'] ?? '');
    $agreement = $_POST['agreement'] ?? '';

  // プラン選択、完成動画の長さ、元動画の長さ、オプションの内容を保持
  $estimateData['plan'] = $_POST['plan'] ?? $estimateData['plan'];
  $estimateData['videoLength'] = $_POST['videoLength'] ?? $estimateData['videoLength'];
  $estimateData['videoSourceLength'] = $_POST['videoSourceLength'] ?? $estimateData['videoSourceLength'];
  $estimateData['photoCount'] = $_POST['photoCount'] ?? $estimateData['photoCount'];
  $estimateData['options'] = $_POST['options'] ?? $estimateData['options'];
  $_SESSION['estimate_data'] = $estimateData;

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
    $errors['inquiry'] = 'ご依頼内容を入力してください。';
  } elseif (strlen($inquiry) > 1000) {
    $errors['inquiry'] = 'ご依頼内容は1000文字以内で入力してください。';
  }

  // オンラインミーティング日時
  if (empty($meeting1)) {
    $errors['meeting1'] = '第一希望の日時を選択してください。';
  }

  // 同意チェック
  if (empty($agreement)) {
    $errors['agreement'] = '内容をご確認の上、チェックを入れてください。';
  }

  if (empty($errors)) {
    // エラーがない場合、セッションにデータを保存してリダイレクト
    $_SESSION['name'] = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $_SESSION['kana'] = htmlspecialchars($kana, ENT_QUOTES, 'UTF-8');
    $_SESSION['companyName'] = htmlspecialchars($companyName, ENT_QUOTES, 'UTF-8');
    $_SESSION['companyNameKana'] = htmlspecialchars($companyNameKana, ENT_QUOTES, 'UTF-8');
    $_SESSION['email'] = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $_SESSION['confirmEmail'] = htmlspecialchars($confirmEmail, ENT_QUOTES, 'UTF-8');
    $_SESSION['telNumber'] = htmlspecialchars($telNumber, ENT_QUOTES, 'UTF-8');
    $_SESSION['inquiry'] = htmlspecialchars($inquiry, ENT_QUOTES, 'UTF-8');
    $_SESSION['meeting1'] = htmlspecialchars($meeting1, ENT_QUOTES, 'UTF-8');
    $_SESSION['meeting2'] = htmlspecialchars($meeting2, ENT_QUOTES, 'UTF-8');
    $_SESSION['meeting3'] = htmlspecialchars($meeting3, ENT_QUOTES, 'UTF-8');
    $_SESSION['agreement'] = $agreement;

    $_SESSION['estimate_data']['plan'] = $_POST['plan'] ?? '';
    $_SESSION['estimate_data']['videoLength'] = $_POST['videoLength'] ?? 1;
    $_SESSION['estimate_data']['videoSourceLength'] = $_POST['videoSourceLength'] ?? 1;
    $_SESSION['estimate_data']['photoCount'] = $_POST['photoCount'] ?? 0;
    $_SESSION['estimate_data']['options'] = $_POST['options'] ?? [];

    header('Location: ./confirm.php');
    exit;
  }
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
  <link rel="stylesheet" href="/css/header.css">
  <link rel="stylesheet" href="/css/request-movie-footer.css">
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

  <!-- flatpickr CSS & JS -->
  <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>
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
      <h2 class="c-heading-primary-center u-cts">
        <span class="c-heading-subprimary-center c-heading-subprimary-center-white">Movie Editing Request</span>動画編集ご依頼フォーム
      </h2>
    </div>
  </div>

  <main class="l-contents">
    <section class="p-movie l-subsection">
      <div class="p-movie-inner l-inner">
        <div class="p-movie-heading">
          <h2 class="c-heading-primary u-cts"><span class="c-heading-subprimary">Movie Editing Request</span>動画編集ご依頼フォーム</h2>
        </div>

        <div id="app" class="container my-5">
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
              <input type="number" class="form-control video-length" id="videoLength" name="videoLength" v-model.number="videoLength">
            </div>
            <div class="mb-3" v-if="selectedPlan !== 'photo'">
              <label for="videoSourceLength" class="form-label">元動画の長さ（分）:</label>
              <input type="number" class="form-control video-length" id="videoSourceLength" name="videoSourceLength" v-model.number="videoSourceLength">
            </div>
            <div class="mb-3" v-if="selectedPlan === 'photo'">
              <label for="photoCount" class="form-label">素材となる写真の枚数:</label>
              <input type="number" class="form-control photo-count" id="photoCount" name="photoCount" v-model.number="photoCount">
            </div>
            <!-- オプション -->
            <div class="mb-3">
              <label class="form-label">オプション</label>
              <div class="form-check" v-for="option in options" :key="option.id">
                <input class="form-check-input" type="checkbox" :id="option.id" :value="option.id" v-model="selectedOptions" name="options[]">
                <label class="form-check-label" :for="option.id">{{ option.name }} ({{ option.price ? formatPrice(option.price) + (option.suffix) : option.priceInfo }})</label>
              </div>
            </div>
            <!-- お見積もり金額の表示 -->
            <div class="my-3">
              <h2 class="total-estimate d-block">
                お見積もり金額: {{ formatPrice(totalEstimate) }}円 <span class="has-consultation-pc" v-if="hasConsultation">＋有料コンテンツ（画像・BGMなど）追加料</span>
                <span class="has-consultation-mb" v-if="hasConsultation"><br>＋有料コンテンツ（画像・BGMなど）追加料</span>
              </h2>
            </div>

            <!-- お名前 -->
            <div class="form-group d-flex flex-column justify-center mb-3">
              <label for="name" class="form-label mb-2 me-5">お名前 <span class="badge text-bg-danger">必須</span>
                <br><span class="ms-3 small caution-txt">姓と名の間は１文字空けて下さい。</span></label>
              <div class="d-flex flex-column">
                <input type="text" name="name" class="form-control d-block" id="name" placeholder="お名前を入力してください" maxlength="32" value="<?php echo htmlspecialchars($name); ?>">
                <?php if (!empty($errors['name'])) : ?>
                  <div class="error text-danger"><?php echo $errors['name']; ?></div>
                <?php endif; ?>
              </div>
            </div>
            <!-- ふりがな -->
            <div class="form-group d-lg-flex flex-column justify-center mb-3">
              <label for="kana" class="form-label mb-2 me-5">おなまえ（ふりがな） <span class="badge text-bg-danger">必須</span>
                <br><span class="ms-3 small caution-txt">姓と名の間は１文字空けて下さい。</span></label>
              <div class="d-flex flex-column">
                <input type="text" name="kana" class="form-control d-block" id="kana" placeholder="ふりがなを入力してください" maxlength="32" value="<?php echo htmlspecialchars($kana); ?>">
                <?php if (!empty($errors['kana'])) : ?>
                  <div class="error text-danger"><?php echo $errors['kana']; ?></div>
                <?php endif; ?>
              </div>
            </div>
            <!-- 会社名 -->
            <div class="form-group d-lg-flex flex-column justify-center mb-3">
              <label for="companyName" class="form-label mb-2 me-5">会社名 <span class="badge text-bg-secondary">任意</span></label>
              <div class="d-flex flex-column">
                <input type="text" name="companyName" class="form-control d-block" id="companyName" placeholder="会社名を入力してください" maxlength="64" value="<?php echo htmlspecialchars($companyName); ?>">
                <?php if (!empty($errors['companyName'])) : ?>
                  <div class="error text-danger"><?php echo $errors['companyName']; ?></div>
                <?php endif; ?>
              </div>
            </div>
            <!-- 会社名 -->
            <div class="form-group d-lg-flex flex-column justify-center mb-3">
              <label for="companyName" class="form-label mb-2 me-5">会社名（ふりがな） <span class="badge text-bg-secondary">任意</span></label>
              <div class="d-flex flex-column">
                <input type="text" name="companyNameKana" class="form-control d-block" id="companyNameKana" placeholder="会社名（ふりがな）を入力してください" maxlength="64" value="<?php echo htmlspecialchars($companyNameKana); ?>">
                <?php if (!empty($errors['companyNameKana'])) : ?>
                  <div class="error text-danger"><?php echo $errors['companyNameKana']; ?></div>
                <?php endif; ?>
              </div>
            </div>
            <!-- メールアドレス -->
            <div class="form-group d-lg-flex flex-column justify-center mb-3">
              <label for="email" class="form-label mb-2 me-5">メールアドレス <span class="badge text-bg-danger">必須</span></label>
              <div class="d-flex flex-column">
                <input type="email" name="email" class="form-control d-block" id="email" placeholder="メールアドレスを入力してください" maxlength="64" value="<?php echo htmlspecialchars($email); ?>">
                <?php if (!empty($errors['email'])) : ?>
                  <div class="error text-danger"><?php echo $errors['email']; ?></div>
                <?php endif; ?>
              </div>
            </div>
            <!-- メールアドレス（確認用） -->
            <div class="form-group d-lg-flex flex-column justify-center mb-3">
              <label for="confirmEmail" class="form-label mb-2 me-5">メールアドレス（確認用） <span class="badge text-bg-danger">必須</span></label>
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
              <label for="telNumber" class="form-label mb-2 me-5">お電話番号 <span class="badge text-bg-danger">必須</span></label>
              <div class="d-flex flex-column">
                <input type="tel" name="telNumber" class="form-control d-block" id="telNumber" placeholder="電話番号を入力してください" maxlength="32" value="<?php echo htmlspecialchars($telNumber); ?>">
                <?php if (!empty($errors['telNumber'])) : ?>
                  <div class="error text-danger"><?php echo $errors['telNumber']; ?></div>
                <?php endif; ?>
              </div>
            </div>

            <!-- オンラインミーティング可能な日時 -->
            <div class="form-group mb-3">
              <label class="form-label">オンラインミーティング可能な日時</label>
              <label for="meeting1" class="form-label">第一希望の日時 <span class="badge text-bg-danger">必須</span></label>
              <input type="text" id="meeting1" name="meeting1" class="form-control" placeholder="例：次の営業日の10時" value="<?php echo htmlspecialchars($meeting1); ?>">
              <?php if (!empty($errors['meeting1'])) : ?>
                <div class="error text-danger"><?php echo $errors['meeting1']; ?></div>
              <?php endif; ?>
            </div>

            <div class="form-group mb-3">
              <label for="meeting2" class="form-label">第二希望の日時 <span class="badge text-bg-secondary">任意</span></label>
              <input type="text" id="meeting2" name="meeting2" class="form-control" placeholder="例：次の営業日の11時" value="<?php echo htmlspecialchars($meeting2); ?>">
            </div>

            <div class="form-group mb-3">
              <label for="meeting3" class="form-label">第三希望の日時 <span class="badge text-bg-secondary">任意</span></label>
              <input type="text" id="meeting3" name="meeting3" class="form-control" placeholder="例：次の営業日の13時" value="<?php echo htmlspecialchars($meeting3); ?>">
            </div>

            <!-- ご依頼内容の詳細 -->
            <div class="form-group d-lg-flex flex-column justify-center mb-3">
              <label for="inquiry" class="form-label mb-2">ご依頼内容の詳細<span class="badge text-bg-danger ms-2">必須</span></label>
              <div class="d-flex flex-column">
                <textarea name="inquiry" class="form-control" id="inquiry" rows="10" placeholder="ご依頼内容の詳細を入力してください
例）
・YouTubeに載せるヘアアレンジ方法の紹介動画がたくさんあるので、3分にまとめ、BGM付きで作って欲しい。
・旅行に行った写真を楽しげなBGMとテロップ付きの5分ぐらいの動画にして欲しい。
・30分のインタビュー動画を10分にカット編集してテロップをつけて欲しい。
・こんな雰囲気で作って欲しい（参考動画のURL…）" maxlength="1000"><?php echo htmlspecialchars($inquiry); ?></textarea>
                <span class="small">（1000文字以内）</span>
                <?php if (!empty($errors['inquiry'])) : ?>
                  <div class="error text-danger"><?php echo $errors['inquiry']; ?></div>
                <?php endif; ?>
              </div>
            </div>

            <!-- ご依頼に際しての確認事項 -->
            <div class="form-group mb-3">
              <label for="agreement" class="form-label mb-2">ご依頼の際、必ずお読みください<span class="badge text-bg-danger ms-2">必須</span></label>
              <div class="d-flex flex-column align-items-start">
                <!-- スクロール可能なテキストエリア -->
                <div class="terms-scroll mb-2 p-2">
                  <h2>動画編集ご依頼利用規約</h2>
                  <br>
                  <p>ご利用料金は税込みで表示しております。</p>
                  <br>
                  <p>当サービスは編集専門ですので、撮影は行っておりません。</p>
                  <br>
                  <p>追加のご要望によっては、別途追加料金がかかることがございます。詳細はお問い合わせください。</p>
                  <br>
                  <p>使用する素材は、無料で提供されるイラストなどを利用しております。完成動画が30分を超える場合、具体的な条件はお問い合わせにて承ります。</p>
                  <br>
                  <p>完成動画の修正は2回まで無料です。3回目以降の修正は、1回につき基本料金の70%を追加請求させていただきます。</p>
                  <br>
                  <p>通常、作業開始から約5～10営業日での納品となりますが、混雑状況によってはさらに時間がかかる場合がございます。</p>
                  <br>
                  <p>お支払いは、見積もり金額の50%を事前にお支払いいただき、その後作業を開始いたします。残りの50%は、動画の納品と確認後にお支払いをお願いいたします。</p>
                  <br>
                  <p>以上の内容をご確認の上、当フォーム下部にある「内容を確認し、同意する」ボタンをクリックしていただくことで、お客様との契約が成立します。本利用規約に同意の上、サービスをご利用ください。</p>
                  <br>
                  <p>ご不明点や追加のご要望がある場合は、お問い合わせください。</p>
                </div>
                <div class="d-flex flex-row align-items-center">
                  <input class="me-2 mb-1" type="checkbox" name="agreement" id="agreement" value="1" <?php echo $agreement ? 'checked' : ''; ?>>
                  <label for="agreement" class="form-label">内容を確認し、同意する</label>
                </div>
              </div>
              <?php if (!empty($errors['agreement'])) : ?>
                <div class="error text-danger"><?php echo $errors['agreement']; ?></div>
              <?php endif; ?>
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


  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/js/swiper.min.js"></script>
  <script src="/js/movie-request.js"></script>
</body>

</html>