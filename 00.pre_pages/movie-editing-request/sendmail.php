<?php
session_start();

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// プランの日本語説明
$planDescriptions = [
  'detailed' => 'こだわりプラン (9,900円)',
  'simple' => 'シンプルプラン (5,940円)',
  'photo' => 'Photoプラン (4,950円)'
];

// オプションの日本語説明
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

// セッションデータの確認
if (empty($_SESSION['email'])) {
    header('Location: ./');
    exit;
}

// セッションからデータを取得
$name = $_SESSION['name'] ?? '';
$kana = $_SESSION['kana'] ?? '';
$companyName = $_SESSION['companyName'] ?? '';
$companyNameKana = $_SESSION['companyNameKana'] ?? '';
$email = $_SESSION['email'] ?? '';
$telNumber = $_SESSION['telNumber'] ?? '';
$inquiry = $_SESSION['inquiry'] ?? '';
$meeting1 = $_SESSION['meeting1'] ?? '';
$meeting2 = $_SESSION['meeting2'] ?? '';
$meeting3 = $_SESSION['meeting3'] ?? '';
$selectedPlan = $_SESSION['estimate_data']['plan'] ?? 'プラン未選択';
$planDisplay = $planDescriptions[$selectedPlan] ?? 'プランが選択されていません';
$videoLength = $_SESSION['estimate_data']['videoLength'] ?? 0;
$sourceOrPhoto = ($selectedPlan === 'photo') ? $_SESSION['estimate_data']['photoCount'] . '枚' : $_SESSION['estimate_data']['videoSourceLength'] . '分';
$options = $_SESSION['estimate_data']['options'] ?? [];
$totalPrice = 0;  // 初期化
$consultationRequired = false;

// プラン価格を加算
if (array_key_exists($selectedPlan, $prices)) {
    $totalPrice += $prices[$selectedPlan];
}

// オプション価格を加算
foreach ($options as $option) {
    if (isset($prices[$option])) {
        $totalPrice += $prices[$option];
        if ($option === 'paidPhotos' || $option === 'paidBGM') {
            $consultationRequired = true;
        }
    }
}

// オプションをテキストに変換
$optionsDisplay = array_map(function($opt) use ($optionsDescriptions) {
    return $optionsDescriptions[$opt] ?? '不明なオプション (' . htmlspecialchars($opt) . ')';
}, $options);

// PHPMailerを使用してメールを送信
$mail = new PHPMailer(true);

try {
    // PHPMailerの設定
    $mail->CharSet = 'UTF-8';
    $mail->setFrom('noreply@linepark.co.jp', '株式会社ＬｉＮＥ ＰＡＲＫ');
    $mail->addAddress($email, $name);
    $mail->isHTML(true); // HTML形式のメール
    $mail->Subject = 'お問い合わせありがとうございます';

    // メール本文の設定
    $mail->Body = "以下の内容でお問い合わせを受け付けました。<br><br>" .
        "お名前：" . htmlspecialchars($name) . "<br>" .
        "ふりがな：" . htmlspecialchars($kana) . "<br>" .
        (!empty($companyName) ? "会社名：" . htmlspecialchars($companyName) . "<br>" : '') .
        (!empty($companyNameKana) ? "会社名（ふりがな）：" . htmlspecialchars($companyNameKana) . "<br>" : '') .
        "メールアドレス：" . htmlspecialchars($email) . "<br>" .
        "電話番号：" . htmlspecialchars($telNumber) . "<br>" .
        "第一希望の日時：" . htmlspecialchars($meeting1) . "<br>" .
        (!empty($meeting2) ? "第二希望の日時：" . htmlspecialchars($meeting2) . "<br>" : '') .
        (!empty($meeting3) ? "第三希望の日時：" . htmlspecialchars($meeting3) . "<br>" : '') .
        "プラン：" . htmlspecialchars($planDisplay) . "<br>" .
        "完成動画の長さ：" . htmlspecialchars($videoLength) . "分<br>" .
        ($selectedPlan === 'photo' ? "素材となる写真の枚数：" : "元動画の長さ：") . htmlspecialchars($sourceOrPhoto) . "<br>" .
        "ご依頼のオプション：<br>" . implode('<br>', $optionsDisplay) . "<br>" .
        "お見積もり総額：" . number_format($totalPrice) . "円～" . ($consultationRequired ? '<br>＋有料コンテンツ（画像・BGMなど）追加料' : '') . "<br><br>" .
        "ご依頼の詳細：<br>" . nl2br(htmlspecialchars($inquiry));

    $mail->send();

    // 完了ページにリダイレクト
    header('Location: thanks.php');
    exit;
} catch (Exception $e) {
    echo "メッセージは送信できませんでした。Mailer Error: {$mail->ErrorInfo}";
}
?>