<?php
session_start();

$errors = [];

$company_name = '';
$company_kana = '';
$name = '';
$name_kana = '';
$postcode1 = ''; // 郵便番号上3桁
$postcode2 = ''; // 郵便番号下4桁
$full_postcode1 = ''; //postcode1 . '-' . postcode2
$address1 = ''; // 住所 町名まで
$address2 = ''; // 住所 番地以降
// $address_kana1 = '';
$telnum1 = '';
// $fax = '';
$email = '';
$email_conf = '';

$post_name = '';
$post_name_kana = '';
$postcode3 = ''; // 郵便番号上3桁
$postcode4 = ''; // 郵便番号下4桁
$full_postcode2 = ''; //postcode3 . '-' . postcode4
$address3 = ''; // お届け先 町名まで
$address4 = ''; // お届け先 番地以降
// $address_kana2 = '';
$telnum2 = ''; // お届け先電話番号

$order_spray = '';
$order_bottle = '';

$payment = '';

var_dump($_POST);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $company_name = $_POST["company-name"];
  $company_kana = $_POST["company-kana"];
  $name = isset($_POST['name']) ? $_POST['name'] : '';
  $name_kana = isset($_POST['name-kana']) ? $_POST['name-kana'] : '';
  $postcode1 = mb_convert_kana($_POST["postcode1"], "n");
  $postcode2 = mb_convert_kana($_POST["postcode2"], "n");
  $fullpostcode1 = $postcode1 . '-' . $postcode2;
  $address1 = $_POST["address1"];
  $address2 = $_POST["address2"];
  // $address_kana1 = $_POST["address-kana1"];
  $telnum1 = mb_convert_kana($_POST["telnum1"], "n");
  $email = $_POST["email"];
  $email_conf = $_POST["email-conf"];

  $post_name = $_POST["post-name"];
  $post_name_kana = $_POST["post-name-kana"];
  $postcode3 = mb_convert_kana($_POST["postcode3"], "n");
  $postcode4 = mb_convert_kana($_POST["postcode4"], "n");
  $fullpostcode2 = $postcode3 . '-' . $postcode4;
  $address3 = $_POST["address3"];
  $address4 = $_POST["address4"];
  // $address_kana2 = $_POST["address-kana2"];
  $telnum2 = mb_convert_kana($_POST["telnum2"], "n");

  $order_spray = $_POST["order-spray"];
  $order_bottle = $_POST["order-bottle"];
  $payment = $_POST["payment"];

  if (empty($company_name)) {
    $errors[] = "貴社名を入力してください。";
  }
  if (empty($company_kana)) {
    $errors[] = "貴社名のフリガナを入力してください。";
  }
  if (empty($name)) {
    $errors[] = "ご担当者様の氏名を入力してください。";
  }
  if (empty($name_kana)) {
    $errors[] = "ご担当者様のフリガナを入力してください。";
  }

  if (empty($postcode1) || empty($postcode2)) {
    $errors[] = "郵便番号を入力してください。";
  }

  if (empty($address1)) {
    $errors[] = "ご住所を入力してください。";
  }

  if (empty($email)) {
    $errors[] = "メールアドレスを入力してください。";
  }

  if (empty($email_conf)) {
    $errors[] = "確認用メールアドレスを入力してください。";
  }

  if ($email !== $email_conf) {
    $errors[] = "メールアドレスをご確認ください。";
  }

  if (!ctype_digit($postcode1) || !ctype_digit($postcode2)) {
    $errors[] = "郵便番号は数字のみで入力してください。";
  }

  if (empty($payment)) {
    $errors[] = "お支払方法を選択してください。";
  }

  if (empty($errors)) {
    $_SESSION['company-name'] = $company_name;
    $_SESSION['company-kana'] = $company_kana;
    $_SESSION['name'] = $name;
    $_SESSION['name-kana'] = $name_kana;
    $_SESSION['postcode1'] = $postcode1;
    $_SESSION['postcode2'] = $postcode2;
    $_SESSION['fullpostcode1'] = $fullpostcode1;
    $_SESSION['address1'] = $address1;
    $_SESSION['address2'] = $address2;
    $_SESSION['telnum1'] = $telnum1;
    $_SESSION['email'] = $email;
    $_SESSION['email-conf'] = $email_conf;
    $_SESSION['post-name'] = $post_name;
    $_SESSION['post-name-kana'] = $post_name_kana;
    $_SESSION['postcode3'] = $postcode3;
    $_SESSION['postcode4'] = $postcode4;
    $_SESSION['fullpostcode2'] = $fullpostcode2;
    $_SESSION['address3'] = $address3;
    $_SESSION['address4'] = $address4;
    $_SESSION['telnum2'] = $telnum2;
    $_SESSION['order-spray'] = $order_spray;
    $_SESSION['order-bottle'] = $order_bottle;
    $_SESSION['payment'] = $payment;

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

  <?php if (!empty($errors)) : ?>
    <div class="alert alert-danger">
      <?php foreach ($errors as $error) : ?>
        <p><?php echo $error; ?></p>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <div class="container">
    <div class="container row mt-5">
      <h2>お客様情報</h2>
      <form class="row" action="" method="post">

        <div class="form-group mt-3"> <!--貴社名-->
          <h4><label for="company-name">貴社名　</label><span class="badge bg-danger">必須</span></h4>
          <input type="text" class="form-control" name="company-name" id="company-name" value="<?php echo htmlspecialchars($company_name, ENT_QUOTES, 'UTF-8'); ?>" placeholder="貴社名を入力してください" maxlength="3" required>
        </div>
        <div class="form-group mt-3"> <!--貴社名：フリガナ-->
          <h4><label for="company-kana">貴社名：フリガナ　</label><span class="badge bg-danger">必須</span></h4>
          <input type="text" class="form-control" name="company-kana" id="company-kana" value="<?php echo htmlspecialchars($company_kana, ENT_QUOTES, 'UTF-8'); ?>" placeholder="貴社名のフリガナを入力してください" required>
        </div>
        <div class="form-group mt-3"> <!--ご担当者様氏名-->
        <h4><label for="name">ご担当者様氏名　</label><span class="badge bg-danger">必須</span></h4>
          <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" placeholder="ご担当者様の氏名を入力してください" required>
        </div>
        <div class="form-group mt-3"> <!--ご担当者様：フリガナ-->
          <h4><label for="name-kana1">ご担当者様：フリガナ　</label><span class="badge bg-danger">必須</span></h4>
          <input type="text" class="form-control" name="name-kana" id="name-kana" value="<?php echo htmlspecialchars($name_kana, ENT_QUOTES, 'UTF-8'); ?>" placeholder="ご担当者様のフリガナを入力してください" required>
        </div>
        <div class="form-group mt-3"> <!--郵便番号-->
          <h4><label for="postcode">郵便番号　</label><span class="badge bg-danger">必須</span></h4>
          <div class="input-group">
            <input type="text" name="postcode1" id="postcode1" maxlength="3" size="3" class="form-control d-block col-lg-1" value="<?php echo htmlspecialchars($postcode1, ENT_QUOTES, 'UTF-8'); ?>">
            <div class="input-group-append">
              <span class="input-group-text">-</span>
            </div>
            <input type="text" name="postcode2" id="postcode2" maxlength="4" size="4" class="form-control d-block col-lg-2" value="<?php echo htmlspecialchars($postcode2, ENT_QUOTES, 'UTF-8'); ?>"><button type="button" id="fetchAddress1" class="btn btn-primary">住所取得</button>
          </div>
        </div>
        <div class="form-group mt-3"> <!--ご住所 町名まで-->
          <h4><label for="address1">ご住所（町名まで）</label><span class="badge bg-danger">必須</span></h4>
          <input type="text" name="address1" id="address1" class="form-control" placeholder="ご住所を入力してください" value="<?php echo htmlspecialchars($address1, ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        <div class="form-group mt-3"> <!--ご住所 番地以降-->
          <h4><label for="address2">ご住所（番地以降。 建物名、部屋番号まで）</label></h4>
          <input type="text" name="address2" id="address2" class="form-control" placeholder="番地以降のご住所を入力してください" value="<?php echo htmlspecialchars($address2, ENT_QUOTES, 'UTF-8'); ?>">
        </div>
        <div class="form-group mt-3"> <!--お電話番号-->
          <h4><label for="telnum1">お電話番号　</label><span class="badge bg-danger">必須</span></h4>
          <input type="text" class="form-control" id="telnum1" name="telnum1" placeholder="お電話番号を入力してください" value="<?php echo htmlspecialchars($telnum1, ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        <div class="form-group mt-3"> <!--メールアドレス-->
          <h4><label for="email">メールアドレス　</label><span class="badge bg-danger">必須</span></h4>
          <input type="email" class="form-control" id="email" name="email" placeholder="メールアドレスを入力してください" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
        <div class="form-group mt-3"> <!--確認用メールアドレス-->
          <h4><label for="email-conf">確認用メールアドレス　</label><span class="badge bg-danger">必須</span></h4>
          <input type="email" class="form-control" id="email-conf" name="email-conf" placeholder="メールアドレスを入力してください" value="<?php echo htmlspecialchars($email_conf, ENT_QUOTES, 'UTF-8'); ?>" required>
        </div>
    </div>

    <div class="container row mt-5">
      <h2>お届け先 <span>*上記と同じ場合記入は不要です。</span> </h2>

      <div class="form-group mt-3"> <!--お届け先氏名-->
        <h4><label for="post-name">お届け先氏名　</label><span class="badge bg-secondary">任意</span></h4>
        <input type="text" class="form-control" name="post-name" id="post-name" placeholder="お届け先の氏名を入力してください">
      </div>
      <div class="form-group mt-3"><!--お届け先氏名：フリガナ-->
        <h4><label for="post-name-kana">お名前：フリガナ　</label><span class="badge bg-secondary">任意</span></h4>
        <input type="text" name="post-name-kana" class="form-control" id="post-name-kana" placeholder="お届け先氏名のフリガナを入力してください">
      </div>
      <div class="form-group mt-3"><!--お届け先郵便番号-->
        <h4><label for="postcode3">お届け先郵便番号　</label><span class="badge bg-secondary">任意</span></h4>
        <div class="input-group">
          <input type="text" name="postcode3" id="postcode3" maxlength="3" size="3" class="form-control d-block col-lg-1" value="<?php echo htmlspecialchars($postcode3, ENT_QUOTES, 'UTF-8'); ?>">
          <div class="input-group-append">
            <span class="input-group-text">-</span>
          </div>
          <input type="text" name="postcode4" id="postcode4" maxlength="4" size="4" class="form-control d-block col-lg-2" value="<?php echo htmlspecialchars($postcode4, ENT_QUOTES, 'UTF-8'); ?>"><button type="button" id="fetchAddress2" class="btn btn-primary">住所取得</button>
        </div>
      </div>
      <div class="form-group mt-3"><!--お届け先ご住所 町名まで-->
        <h4><label for="address3">お届け先ご住所（町名まで）　</label><span class="badge bg-secondary">任意</span></h4>
        <input type="text" name="address3" id="address3" class="form-control" placeholder="お届け先ご住所を入力してください" value="<?php echo htmlspecialchars($address3, ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="form-group mt-3"><!--お届け先ご住所 番地以降-->
        <h4><label for="address4">お届け先ご住所（番地以降。 建物名、部屋番号まで）　</label><span class="badge bg-secondary">任意</span></h4>
        <input type="text" name="address4" id="address4" class="form-control" placeholder="番地以降のご住所を入力してください" value="<?php echo htmlspecialchars($address4, ENT_QUOTES, 'UTF-8'); ?>">
      </div>
      <div class="form-group mt-3"><!--お届け先電話番号-->
        <h4><label for="telnum2">お届け先電話番号　</label><span class="badge bg-secondary">任意</span></h4>
        <input type="text" class="form-control" name="telnum2" id="telnum2" placeholder="お届け先のお電話番号を入力してください">
      </div>
    </div>

    <div class="container row mt-5">
      <h2>ご注文商品</h2>
      <h3>商品の詳細</h3>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th scope="col">商品名</th>
            <th scope="col">容量</th>
            <th scope="col">発注単位</th>
            <th scope="col">通常価格<br>（税抜）</th>
            <th scope="col">販売単価<br>（税抜）</th>
            <th scope="col">販売価格<br>（税抜）</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Adox M GUARD+<br>（スプレーボトル）</td>
            <td>300ml</td>
            <td>24</td>
            <td>￥1,300</td>
            <td>￥980</td>
            <td>￥23,520</td>
          </tr>
          <tr>
            <td>Adox M GUARD+<br>（詰め替えボトル）</td>
            <td>500ml</td>
            <td>12</td>
            <td>￥1,400</td>
            <td>￥1,080</td>
            <td>￥12,960</td>
          </tr>
        </tbody>
      </table>
      <h3>ご注文</h3>
      <div class="form-group mt-3 d-flex">
        <label for="order-sparay">Adox M GUARD＋ スプレーボトル</label>
        <select class="form-control mx-2 col-1" name="order-spray" id="order-spray">
          <?php for ($i = 0; $i <= 100; $i++) : ?>
            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
          <?php endfor; ?>
        </select>
        <p>セット</p>
      </div>
      <div class="form-group mt-3 d-flex">
        <label for="order-bottle">Adox M GUARD＋ 詰め替えボトル</label>
        <select class="form-control mx-2 col-1" name="order-bottle" id="order-bottle">
          <?php for ($i = 0; $i <= 100; $i++) : ?>
            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
          <?php endfor; ?>
        </select>
        <p>セット</p>
      </div>

      <div class="form-group mt-3">
        <div>
          <h3><label for="payment">お支払方法　</label><span class="badge bg-danger">必須</span></h3>
        </div>
        <div class="mb-3"><input type="radio" name="payment" value="代金引換">代金引換</div>
        <div class="mb-3"><input type="radio" name="payment" value="銀行振込">銀行振込</div>
      </div>

    </div>
    <button type="submit" class="btn btn-primary mt-3">ご注文内容の確認</button>
    </form>
  </div>


  <!--フッター -->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/includes/footer.html'; ?>

  <div id="topbtn" class="c-page-top">
    <a href="#" class="c-page-top_link">
      <img src="/img/common/aroow-white.svg" alt="トップへ戻るの矢印画像" class="c-page-top_img" />
    </a>
  </div>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- 住所取得 -->
  <script>
    $(document).ready(function() {

      function toHalfWidth(str) {
        return str.replace(/[０-９]/g, function(s) {
          return String.fromCharCode(s.charCodeAt(0) - 65248);
        });
      }

      function fetchAddress(postcodeElem1, postcodeElem2, addressElem) {
        let postcode1 = toHalfWidth($(postcodeElem1).val());
        let postcode2 = toHalfWidth($(postcodeElem2).val());

        if (!postcode1 || !postcode2) {
          alert("郵便番号を入力してください。");
          return;
        }

        $.ajax({
          url: "https://zipcloud.ibsnet.co.jp/api/search?zipcode=" + postcode1 + postcode2,
          type: "GET",
          dataType: "jsonp",
          success: function(data) {
            if (data.results && data.results[0].address1 && data.results[0].address2 && data.results[0].address3) {
              $(addressElem).val(data.results[0].address1 + data.results[0].address2 + data.results[0].address3);
            } else {
              alert("該当する住所が見つかりませんでした。");
            }
          },
          error: function(jqXHR, textStatus, errorThrown) {
            alert("住所の取得に失敗しました。再度お試しください。");
          }
        });
      }


      // 郵便番号1の「住所取得」ボタンがクリックされたときの処理
      $("#fetchAddress1").on("click", function() {
        fetchAddress("#postcode1", "#postcode2", "#address1");
      });

      // 郵便番号2の「住所取得」ボタンがクリックされたときの処理
      $("#fetchAddress2").on("click", function() {
        fetchAddress("#postcode3", "#postcode4", "#address3");
      });
    });
  </script>


  <!-- Bootstrap JS and Popper.js links
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/5.4.5/js/swiper.min.js"></script> -->
  <!-- <script src="../js/script.js"></script> -->
</body>

</html>