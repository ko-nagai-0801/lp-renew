<?php
session_start();
var_dump($_SESSION);
// echo $full_postcode1;
// エスケープ関数の定義
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// SESSIONデータを取得
if (isset($_SESSION)) {
    $company_name = isset($_SESSION['company-name']) ? h($_SESSION['company-name']) : '';
    $company_kana = isset($_SESSION['company-kana']) ? h($_SESSION['company-kana']) : '';
    $name = isset($_SESSION['name']) ? h($_SESSION['name']) : '';
    $name_kana = isset($_SESSION['name-kana']) ? h($_SESSION['name-kana']) : '';
    $postcode1 = isset($_SESSION['postcode1']) ? h($_SESSION['postcode1']) : '';
    $postcode2 = isset($_SESSION['postcode2']) ? h($_SESSION['postcode2']) : '';
    $fullpostcode1 = isset($_SESSION['fullpostcode1']) ? h($_SESSION['fullpostcode1']) : '';
    $address1 = isset($_SESSION['address1']) ? h($_SESSION['address1']) : '';
    $address2 = isset($_SESSION['address2']) ? h($_SESSION['address2']) : '';
    $telnum1 = isset($_SESSION['telnum1']) ? h($_SESSION['telnum1']) : '';
    $email = isset($_SESSION['email']) ? h($_SESSION['email']) : '';
    $post_name = isset($_SESSION['post-name']) ? h($_SESSION['post-name']) : '';
    $post_name_kana = isset($_SESSION['post-name-kana']) ? h($_SESSION['post-name-kana']) : '';
    $postcode3 = isset($_SESSION['postcode3']) ? h($_SESSION['postcode3']) : '';
    $postcode4 = isset($_SESSION['postcode4']) ? h($_SESSION['postcode4']) : '';
    $fullpostcode2 = isset($_SESSION['fullpostcode2']) ? h($_SESSION['fullpostcode2']) : '';
    $address3 = isset($_SESSION['address3']) ? h($_SESSION['address3']) : '';
    $address4 = isset($_SESSION['address4']) ? h($_SESSION['address4']) : '';

    $telnum2 = isset($_SESSION['telnum2']) ? h($_SESSION['telnum2']) : '';
    $order_spray = isset($_SESSION['order-spray']) ? h($_SESSION['order-spray']) : '';
    $order_bottle = isset($_SESSION['order-bottle']) ? h($_SESSION['order-bottle']) : '';
    $payment = isset($_SESSION['payment']) ? h($_SESSION['payment']) : '';
} else {
    die('不正なアクセスです。');
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="株式会社LiNE PARKのホームページです">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta property='og:type' content='website'>
  <meta property='og:title' content='株式会社LiNE PARK'>
  <meta property='og:description' content='株式会社LiNE PARKはWEBサイト・ホームページやLPなどのWEB制作と動画編集や動画広告づくりなどの動画制作を中心にサービスを展開しています。お客様のご希望を叶えるべく全力で制作させていただきます。'>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/css/swiper.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <!-- <link rel="stylesheet" href="./common/css/style.css" /> -->
  <!-- Bootstrap CSS link -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="/css/base.css">
  <link rel="stylesheet" href="/css/header.css">
  <link rel="stylesheet" href="/css/footer.css?v=3">
  <link rel="apple-touch-icon" sizes="180x180" href="/img/common/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/img/common/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/img/common/favicon-16x16.png">
  <link rel="manifest" href="/img/common/site.webmanifest">
  <link rel="mask-icon" href="/img/common/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">
  <!-- <meta name="format-detection" content="telephone=no"> -->
  <title>株式会社LiNE PARK test</title>

  <!-- Bootstrap CSS link -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

  <!-- jQuery link -->
  <!-- <script
  src="https://code.jquery.com/jquery-3.7.1.js"
  integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script> -->



</head>


<body>
  <!--ヘッダー-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/header.html'; ?>

  <div class="p-subhero p-subhero-newspage">
    <div class="p-subhero_heading">
      <h2 class="c-heading_primary-center u-cts">
        <span class="c-heading_subprimary-center c-heading_subprimary-center-white">M GUARD+</span>エムガードプラスご注文フォーム
      </h2>
    </div>
  </div>


<div class="container mt-5">
    <h2>入力内容の確認</h2>

    <div>
        <h3 class="mt-3 mb-5">お客様情報</h3>
        <div class="mb-5">
            <h5><label class="form-label">貴社名:</label></h5>
            <h4>
                <div><?php echo $company_name; ?></div>
            </h4>
        </div>
        <div class="mb-5">
            <h5><label class="form-label">ご担当者様氏名:</label></h5>
            <h4>
                <div><?php echo $name; ?></div>
            </h4>
        </div>
        <div class="mb-5">
            <h5><label class="form-label">郵便番号:</label></h5>
            <h4>
                <div><?php echo $fullpostcode1; ?></div>
            </h4>
        </div>
        <div class="mb-5">
            <h5><label class="form-label">ご住所:</label></h5>
            <h4>
                <div><?php echo $address1; ?></div>
            </h4>
        </div>
        <div class="mb-5">
            <h5><label class="form-label">お電話番号:</label></h5>
            <h4>
                <div><?php echo $telnum1; ?></div>
            </h4>
        </div>
        <div class="mb-5">
            <h5><label class="form-label">メールアドレス:</label></h5>
            <h4>
                <div><?php echo $email; ?></div>
            </h4>
        </div>
    </div>

    <div>
        <h3 class="mt-3 mb-5">お届け先情報</h3>
        <div class="mb-5">
            <h5><label class="form-label">お届け先氏名:</label></h5>
            <h4>
                <div><?php echo $company_name; ?></div>
            </h4>
        </div>
        <div class="mb-5">
            <h5><label class="form-label">お届け先郵便番号:</label></h5>
            <h4>
                <div><?php echo $fullpostcode2; ?></div>
            </h4>
        </div>
        <div class="mb-5">
            <h5><label class="form-label">お届け先ご住所:</label></h5>
            <h4>
                <div><?php echo $address2; ?></div>
            </h4>
        </div>
        <div class="mb-5">
            <h5><label class="form-label">お届け先お電話番号:</label></h5>
            <h4>
                <div><?php echo $telnum2; ?></div>
            </h4>
        </div>
    </div>

    <div>
        <h3 class="mt-3 mb-5">ご注文商品</h3>
        <div class="mb-5">
            <h5><label class="form-label">Adox M GUARD＋ スプレーボトル:</label></h5>
            <h4>
                <div><?php echo $order_spray; ?>個</div>
            </h4>
        </div>
        <div class="mb-5">
            <h5><label class="form-label">Adox M GUARD＋ 詰め替えボトル:</label></h5>
            <h4>
                <div><?php echo $order_bottle; ?>個</div>
            </h4>
        </div>
        <div class="mb-5">
            <h5><label class="form-label">お支払方法:</label></h5>
            <h4>
                <div><?php echo $payment; ?></div>
            </h4>
        </div>
        <div class="mb-5">
            <h5><label class="form-label">お支払い金額:</label></h5>
            <h4>
                <div><?php echo ($order_spray*23520)+ ($order_bottle*12960); ?>円</div>
            </h4>
        </div>
    </div>

    <form action="complete.php" method="post">
        <input type="hidden" name="company-name" value="<?php echo $company_name; ?>">
        <input type="hidden" name="company-kana" value="<?php echo $company_kana; ?>">
        <input type="hidden" name="name" value="<?php echo $name; ?>">
        <input type="hidden" name="name-kana" value="<?php echo $name_kana; ?>">
        <input type="hidden" name="fullpostcode1" value="<?php echo $fullpostcode1; ?>">
        <input type="hidden" name="address1" value="<?php echo $address1; ?>">
        <input type="hidden" name="address2" value="<?php echo $address2; ?>">
        <input type="hidden" name="telnum1" value="<?php echo $telnum1; ?>">
        <input type="hidden" name="email" value="<?php echo $email; ?>">

        <input type="hidden" name="post-name" value="<?php echo $post_name; ?>">
        <input type="hidden" name="post-name-kana" value="<?php echo $post_name_kana; ?>">
        <input type="hidden" name="fullpostcode2" value="<?php echo $fullpostcode2; ?>">
        <input type="hidden" name="address3" value="<?php echo $address3; ?>">
        <input type="hidden" name="address4" value="<?php echo $address4; ?>">
        <input type="hidden" name="telnum2" value="<?php echo $telnum2; ?>">

        <input type="hidden" name="order-spray" value="<?php echo $order_spray; ?>">
        <input type="hidden" name="order-bottle" value="<?php echo $order_bottle; ?>">

        <input type="hidden" name="payment" value="<?php echo $payment; ?>">

        <button type="button" class="btn btn-secondary" onclick="history.back()">戻って修正する</button>
        <button type="submit" class="btn btn-primary">ご注文を確定する</button>
    </form>
</div>

  <!--フッター -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.html'; ?>

  <div id="topbtn" class="c-page-top">
    <a href="#" class="c-page-top_link">
      <img src="/img/common/aroow-white.svg" alt="トップへ戻るの矢印画像" class="c-page-top_img" />
    </a>
  </div>

<!-- Bootstrap 5 JS and Popper.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
