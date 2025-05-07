<?php
session_start();

require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// セッションデータの確認
if (empty($_SESSION['name']) || empty($_SESSION['email'])) {
    header('Location: ./');
    exit;
}

// セッションからデータを取得
$name = $_SESSION['name'] ?? '';
$kana = $_SESSION['kana'] ?? '';
$companyName = $_SESSION['companyName'] ?? '';
$email = $_SESSION['email'] ?? '';
$telNumber = $_SESSION['telNumber'] ?? '';
$inquiry = $_SESSION['inquiry'] ?? '';

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
        "会社名：" . htmlspecialchars($companyName) . "<br>" .
        "メールアドレス：" . htmlspecialchars($email) . "<br>" .
        "電話番号：" . htmlspecialchars($telNumber) . "<br>" .
        "お問い合わせ内容：<br>" . nl2br(htmlspecialchars($inquiry));

    $mail->send();

    // 送信元メールアドレスと名前を設定
    $mail->setFrom('info@linepark.co.jp', 'ＬｉＮＥ ＰＡＲＫ HP');
    // 指定したアドレスに送るメール
    $mail->clearAddresses();
    $mail->addAddress('info@linepark.co.jp', '管理者');
    $mail->Subject = 'HPより新しいお問い合わせがあります';
    $mail->Body = "新しいお問い合わせがあります。<br><br>" .
        "お名前：" . htmlspecialchars($name) . "<br>" .
        "ふりがな：" . htmlspecialchars($kana) . "<br>" .
        "会社名：" . htmlspecialchars($companyName) . "<br>" .
        "メールアドレス：" . htmlspecialchars($email) . "<br>" .
        "電話番号：" . htmlspecialchars($telNumber) . "<br>" .
        "お問い合わせ内容：<br>" . nl2br(htmlspecialchars($inquiry));

    $mail->send();


    // 完了ページにリダイレクト
    header('Location: thanks.php');
    exit;
} catch (Exception $e) {
    echo "メッセージは送信できませんでした。Mailer Error: {$mail->ErrorInfo}";
}