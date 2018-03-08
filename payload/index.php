<?php

// @copyright 2018 YuTanaka@AmuseOne
// @license https://github.com/am1tanaka/deploy-php/blob/master/LICENSE MIT
// 参考URL https://qiita.com/oyas/items/1cbdc3e0ac35d4316885

// ログファイルをこのフォルダーと同じ場所のhook.logに設定
$LOG_FILE = dirname(__FILE__).'/hook.log';

// 必要なデータを取得
$secret = getenv("SECRET_TOKEN");
$sig = array_key_exists('HTTP_X_HUB_SIGNATURE', $_SERVER) ? $_SERVER['HTTP_X_HUB_SIGNATURE'] : "";
$event = array_key_exists('HTTP_X_GITHUB_EVENT', $_SERVER) ? $_SERVER['HTTP_X_GITHUB_EVENT'] : "";
$body = file_get_contents('php://input');
$hmac = 'sha1='.hash_hmac('sha1', $body, $secret);

// 送られてきたSIGNATUREの長さが違う場合は
// セキュリティー対策で本来の長さの文字列に差し替える
if (strlen($sig) !== strlen($hmac)) {
    $sig = "";
    for ($i=0 ; $i<strlen($hmac) ; $i++) {
        $sig .= "a";
    }
}

// 認証
if (hash_equals($hmac, $sig)) {
    // 認証成功
    $payload = json_decode($body, true);
    file_put_contents($LOG_FILE, date("[Y-m-d H:i:s]")." ".$_SERVER['REMOTE_ADDR']." git issue recieved.\n", FILE_APPEND|LOCK_EX);

    // これ以降に実行したい処理を書く
}
else {
    // 認証失敗
    file_put_contents($LOG_FILE, date("[Y-m-d H:i:s]")." invalid access: ".$_SERVER['REMOTE_ADDR']."\n", FILE_APPEND|LOCK_EX);
}

 ?>
