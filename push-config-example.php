<?php
// 失敗時にメールを送信する先のアドレス。空文字にするとメール送信をしない
define("SEND_TO", "your@mail.addr");
// 送信元アドレス。送信するメールサーバーに所属するものでないと迷惑メールになるので注意
define("SEND_FROM", "from@mail.addr");
// メールの件名の最初につける接頭語
define("SUBJECT_PREFIX", "[PREFIX]");
// GitHub WebhookのSecret欄に設定したのと同じ文字列
define("WP_GH_SECRET", "GitHubWebHookSecret");
?>
