<?php
@require "./push-config.php";

// @copyright 2018 YuTanaka@AmuseOne
// @license https://github.com/am1tanaka/deploy-php/blob/master/LICENSE MIT
// 参考URL https://qiita.com/oyas/items/1cbdc3e0ac35d4316885

// ログファイルをこのフォルダーと同じ場所のhook.logに設定
$LOG_FILE = dirname(__FILE__).'/hook.log';

// 必要なデータを取得
$secret = WP_GH_SECRET;
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

    // masterへのpushか
    if (    (strcmp("push", $event) === 0)
        &&  (strcmp("refs/heads/master", $payload['ref']) === 0))
    {
        // プル実行
        exec("git pull origin master 2>&1 1>/dev/null", $output, $return_var);
        if ($return_var !== 0) {
            // プル失敗
            $mes = date("[Y-m-d H:i:s]")." git pull failed.: ".print_r($output, true)."\n";
            send_error("git pull failed", $mes);
        }
        else {
            // 成功ログ出力
            file_put_contents($LOG_FILE, date("[Y-m-d H:i:s]")." ".$_SERVER['REMOTE_ADDR']." git issue recieved.\n", FILE_APPEND|LOCK_EX);
        }
    }
    else {
        // リクエスト違い
        $mes = date("[Y-m-d H:i:s]")." different command or branch: event=".$event." / refs=".$payload->ref."\n";
        send_error("different command or branch", $mes);
    }

}
else {
    // 認証失敗
    $mes = date("[Y-m-d H:i:s]")." invalid access: ".$_SERVER['REMOTE_ADDR']."\n";
    send_error("invalid access", $mes);
}

// メール送信してログに出力
function send_error($subject, $body) {
    // エラーをログに書き出し
    file_put_contents($LOG_FILE, $mes, FILE_APPEND|LOCK_EX);

    // SEND_TOが空文字ならメールは送信しない
    if (strlen(SEND_TO) === 0) {
        return;
    }
    // メール送信
    mb_language("Japanese");
    mb_internal_encoding("UTF-8");
    $subject = SUBJECT_PREFIX.$subject;

    if (!mb_send_mail(
        SEND_TO,
        $subject,
        $body,
        "From :".SEND_FROM))
    {
        // メール送信に失敗
        file_put_contents($LOG_FILE, date("[Y-m-d H:i:s]")." send mail failed: ".SEND_TO."\n", FILE_APPEND|LOCK_EX);
    }
}

 ?>
