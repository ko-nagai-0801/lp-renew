<?php
session_start();

// 入力値の検証とクリーニング関数
function cleanInput($data, $default = '') {
    return isset($data) ? trim($data) : $default;
}

function cleanInt($data, $default = 0) {
    return isset($data) ? intval($data) : $default;
}

// POSTデータの受け取りと検証
$plan = cleanInput($_POST['plan']);
$videoLength = cleanInt($_POST['videoLength']);
$videoSourceLength = cleanInt($_POST['videoSourceLength']);
$photoCount = cleanInt($_POST['photoCount']);

// オプションは配列として送信されるため、配列であることを確認
$options = isset($_POST['options']) && is_array($_POST['options']) ? $_POST['options'] : [];

// セッションにフォームデータを保存
$_SESSION['estimate_data'] = [
    'plan' => $plan,
    'videoLength' => $videoLength,
    'videoSourceLength' => $videoSourceLength,
    'photoCount' => $photoCount,
    'options' => $options
];

// リダイレクト
header("Location: /movie-editing-request/");
exit();
?>
