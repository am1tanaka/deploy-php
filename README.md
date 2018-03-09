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

# WPサーバーとGitHubの設定シナリオ
- WPサーバーに練習用のフォルダーと中身を作成しておく
- WPサーバーからGitHubへアクセスできるようにSSH関連のファイルを設定する
- GitHub上でファイルを変更して、WPサーバーで以下を実行してpullできることを確認する

```
git pull origin master
```

- 練習用のフォルダーにwebhookを処理するためのPHPファイルをおく
- GitHubにpushのwebhookを設定
- 作業用PCに環境をクローン
- 変更をして、プッシュ
- WPサーバーが自動更新されているかを確認
- master以外のブランチにプッシュ
- WPサーバーへの影響がないことを確認
- webhookの入り口にアクセスして、エラーが出ることを確認

## 手順
### プロジェクトをGitHubにプッシュ
- プロジェクトのある場所から、GitHubにSSHアクセスできるように設定
- GitHubに、管理したいプロジェクト用のリポジトリーを作成しておく
- 管理したいプロジェクトのフォルダー内で以下を実行

```
git init
git add.
git commit -m 'first commit.'
git remote add origin git@github.com:<ユーザー名>/<リポジトリー名>.git
git push -u origin master
```

以上で、既存のプロジェクトをGitHubにプッシュできる。

# 作業
- ブランチがmasterであることの確認を実行前に行う
- エラーが発生したら、管理者にエラー内容を送信する設定をPHPにする

# 参考URL
- https://developer.github.com/webhooks/
- https://qiita.com/oyas/items/1cbdc3e0ac35d4316885
- https://qiita.com/prex-uchida/items/f8bc05eb91b944b6214e
- http://php.net/manual/ja/function.exec.php
