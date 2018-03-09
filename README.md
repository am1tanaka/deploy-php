# deploy-php
GitHubにプッシュしたらWebhookでサーバーのプログラムを実行するPHPコード。

# webhookをローカル環境で受け取る手順
- http://am1tanaka.hatenablog.com/entry/2018/03/09/004014

# PHPでpullする方法
以下のように呼び出す。

```php
exec('/home/sakura_account/local/bin/git pull origin master 2>&1 1>/dev/null', $output, $return_var);
```

`git pull origin master`がプルをする処理です。その後ろの`2>&1 1>/dev/null`は、標準出力は捨てて、標準エラー出力を標準出力にリダイレクトさせて変数$outputで受け取るための設定です。理屈は[こちら](http://am1tanaka.hatenablog.com/entry/2018/03/09/143501)。

# さくらインターネットとGitHubの設定シナリオ
- さくらインターネットに既存のフォルダー下の


GitHubに


# 参考URL
- https://developer.github.com/webhooks/
- https://qiita.com/oyas/items/1cbdc3e0ac35d4316885
- https://qiita.com/prex-uchida/items/f8bc05eb91b944b6214e
- http://php.net/manual/ja/function.exec.php
