<?php
session_start();

if (empty($_SESSION['email'])) {
  header('Location: ./');
  exit;
}

// プランとオプションの日本語説明
$planDescriptions = [
  'detailed' => 'こだわりプラン (9,900円)',
  'simple' => 'シンプルプラン (5,940円)',
  'photo' => 'Photoプラン (4,950円)'
];

$optionsDescriptions = [
  'channelSetup' => 'チャンネル及びアカウントの立ち上げ (1,650円 / 件)',
  'thumbnailCreation' => 'サムネイル作成 (3,300円 / 件)',
  'photoInsertion' => '写真素材の挿入 (330円 / 枚)',
  'openingCreation' => 'オープニング作成 (3,300円 ～)',
  'endingCreation' => 'エンディング作成 (3,300円 ～)',
  'materialCreation' => '素材作成 (3,300円 ～)',
  'paidPhotos' => '有料写真や画像・イラストの挿入 (応相談)',
  'paidBGM' => '有料BGMの追加 (応相談)',
  'planning' => '企画・構成 (22,000円 〜)'
];

// プランとオプションの価格設定
$prices = [
  'detailed' => 9900,
  'simple' => 5940,
  'photo' => 4950,
  'channelSetup' => 1650,
  'thumbnailCreation' => 3300,
  'photoInsertion' => 330,
  'openingCreation' => 3300,
  'endingCreation' => 3300,
  'materialCreation' => 3300,
  'paidPhotos' => 0,  // 価格は応相談
  'paidBGM' => 0,     // 価格は応相談
  'planning' => 22000
];

// 合計価格の計算
$totalPrice = 0;
$consultationRequired = false;  // 追加料金が必要かどうかを判定するフラグ
$selectedPlan = $_SESSION['estimate_data']['plan'] ?? '';
$selectedOptions = $_SESSION['estimate_data']['options'] ?? [];
$optionsDisplay = [];

if (!empty($selectedPlan) && array_key_exists($selectedPlan, $prices)) {
  $totalPrice += $prices[$selectedPlan];
}

foreach ($selectedOptions as $option) {
  if (array_key_exists($option, $prices)) {
    $totalPrice += $prices[$option];
    if ($option === 'paidPhotos' || $option === 'paidBGM') {
      $consultationRequired = true;  // 応相談のオプションが選択されている
    }
  }
  $optionsDisplay[] = $optionsDescriptions[$option] ?? '不明なオプション (' . htmlspecialchars($option) . ')';
}

// 日本語でのプラン表示
$planDisplay = $planDescriptions[$selectedPlan] ?? 'プランが選択されていません';
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
  <title>ご依頼の確認</title>
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
  <?php include $_SERVER['DOCUMENT_ROOT']  . '/includes/header.html'; ?>

  <div class="p-subhero p-subhero-movie-products">
    <div class="p-subhero-heading">
      <h2 class="c-heading-primary-center u-cts">
        <span class="c-heading-subprimary-center c-heading-subprimary-center-white">Confirm</span>ご依頼内容の確認
      </h2>
    </div>
  </div>

  <div class="confirm-heading">
    <div class="confirm-message-title">
      ご依頼の内容をご確認ください。
    </div>
  </div>
  <?php var_dump($_SESSION) ?>
  <div class="form-block container mx-auto mb-5">
    <form action="sendmail.php" method="post">
      <?php echo '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">'; ?>
      <div class="mb-4">
        <h5>お名前</h5>
        <h4 class="fs-4"><?php echo htmlspecialchars($_SESSION['name']); ?></h4>
      </div>
      <div class="mb-4">
        <h5>ふりがな</h5>
        <h4 class="fs-4"><?php echo htmlspecialchars($_SESSION['kana']); ?></h4>
      </div>
      <?php if (!empty($_SESSION['companyName'])) : ?>
        <div class="mb-4">
          <h5>会社名</h5>
          <h4 class="fs-4"><?php echo htmlspecialchars($_SESSION['companyName']); ?></h4>
        </div>
      <?php endif; ?>
      <?php if (!empty($_SESSION['companyNameKana'])) : ?>
        <div class="mb-4">
          <h5>会社名（ふりがな）</h5>
          <h4 class="fs-4"><?php echo htmlspecialchars($_SESSION['companyNameKana']); ?></h4>
        </div>
      <?php endif; ?>
      <div class="mb-4">
        <h5>メールアドレス</h5>
        <h4 class="fs-4"><?php echo htmlspecialchars($_SESSION['email']); ?></h4>
      </div>
      <div class="mb-4">
        <h5>お電話番号</h5>
        <h4 class="fs-4"><?php echo htmlspecialchars($_SESSION['telNumber']); ?></h4>
      </div>
      <div class="mb-4">
        <h5>お問い合わせ内容</h5>
        <h4 class="fs-4"><?php echo nl2br(htmlspecialchars($_SESSION['inquiry'])); ?></h4>
      </div>
      <div class="mb-4">
        <h5>第一希望の日時</h5>
        <h4 class="fs-4"><?php echo htmlspecialchars($_SESSION['meeting1']); ?></h4>
      </div>
      <?php if (!empty($_SESSION['meeting2'])) : ?>
        <div class="mb-4">
          <h5>第二希望の日時</h5>
          <h4 class="fs-4"><?php echo htmlspecialchars($_SESSION['meeting2']); ?></h4>
        </div>
      <?php endif; ?>
      <?php if (!empty($_SESSION['meeting3'])) : ?>
        <div class="mb-4">
          <h5>第三希望の日時</h5>
          <h4 class="fs-4"><?php echo htmlspecialchars($_SESSION['meeting3']); ?></h4>
        </div>
      <?php endif; ?>
      <div class="mb-4">
        <h5>プラン選択</h5>
        <h4 class="fs-4"><?php echo htmlspecialchars($planDisplay); ?></h4>
      </div>
      <div class="mb-4">
        <h5>完成動画の長さ（分）</h5>
        <h4 class="fs-4"><?php echo htmlspecialchars($_SESSION['estimate_data']['videoLength']); ?></h4>
      </div>
      <?php if ($selectedPlan === 'photo') : ?>
        <div class="mb-4">
          <h5>素材となる写真の枚数</h5>
          <h4 class="fs-4"><?php echo htmlspecialchars($_SESSION['estimate_data']['photoCount']); ?></h4>
        </div>
      <?php else : ?>
        <div class="mb-4">
          <h5>元動画の長さ（分）</h5>
          <h4 class="fs-4"><?php echo htmlspecialchars($_SESSION['estimate_data']['videoSourceLength']); ?></h4>
        </div>
      <?php endif; ?>
      <div class="mb-4">
        <h5>オプションの内容</h5>
        <h4 class="fs-4">
          <?php foreach ($optionsDisplay as $option) : ?>
            <?php echo htmlspecialchars($option); ?><br>
          <?php endforeach; ?>
        </h4>
      </div>
      <div class="mb-4">
        <h5>お見積り総額</h5>
        <h4 class="fs-4"><?php echo number_format($totalPrice) . '円' . ($consultationRequired ? '<br>＋有料コンテンツ（画像・BGMなど）追加料' : ''); ?></h4>
      </div>

      <div class="d-flex justify-content-center">
        <a href="./" class="btn btn-secondary me-3">修正する</a>
        <button type="submit" class="btn btn-primary">送信する</button>
      </div>
    </form>
  </div>
  </section>

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