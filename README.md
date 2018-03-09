# deploy-php
GitHubにプッシュしたらWebhookでサーバーのプログラムを実行するPHPコード。

# 手順
## 開発環境の構築
- ローカルポートをグローバルに一時的に公開するngrokをダウンロード
  - https://ngrok.com/
- このリポジトリーにIssuesのwebhookを設定
- PHPのコードを作成
  - 以下を参考 https://qiita.com/oyas/items/1cbdc3e0ac35d4316885
  - ハッシュコードの確認は http://php.net/manual/ja/function.hash-equals.php
- PHPのコードのフォルダーで`php -S 127.0.0.1:4567`でサーバー起動
- GitHubでIssuesを作成
- 認証が届くか確認

# PHPでpullする
以下のように呼び出す。

```php
exec('/home/sakura_account/local/bin/git pull origin master 2>&1 1>/dev/null', $output, $return_var);
```

`git pull origin master`がプルをする処理です。その後ろの`2>&1 1>/dev/null`は、標準出力は捨てて、標準エラー出力を標準出力にリダイレクトさせて変数$outputで受け取るための設定です。

## メモ
頻出する`>/dev/null 2>&1`は、標準出力と標準エラー出力の双方を捨ててしまう設定ですが、今回は標準エラー出力を$outputで受け取りたかったのでこのように変更しています。

以下、参考。

- http://www.m-bsys.com/linux/redirect-1
- https://qiita.com/ritukiii/items/b3d91e97b71ecd41d4ea
- https://qiita.com/hasegit/items/2598963393e0395685f6

並べ方と設定内容があやふやだったので大混乱しました。自分的な要点は以下の２点でした。

- リダイレクトは、左から右に設定される
- <b>リダイレクトを指定したタイミングの設定先に設定される。それ以降の変更は前の設定には適用されない</b>

頻出の`>/dev/null 2>&1`は、以下のように設定されます。

- `>/dev/null`は、`1>/dev/null`と同義で、標準出力を`/dev/null`に切り替えて捨てるようにします
- 次に`2>&1`の設定で、標準エラー出力を標準出力に切り替えますが、この時点で標準出力はすでに`/dev/null`に切り替えられているので、標準エラー出力も`/dev/null`に切り替わります
- 以上で、標準出力、標準エラー出力とも、表示されなくなります

`2>&1 1>/dev/null`は以下の通りです。

- 最初に`2>&1`を設定するので、標準エラー出力は<b>標準出力</b>に出力します
- 次に`1>/dev/null`として、標準出力を`/dev/null`にリダイレクトして捨てます

標準出力を捨てる設定の方が後なので、標準エラー出力は先に設定した<b>標準出力のまま</b>ということになります。

設定した時点の状態を維持するというところがミソでした。




# 参考URL
- https://developer.github.com/webhooks/
- https://qiita.com/oyas/items/1cbdc3e0ac35d4316885
- https://qiita.com/prex-uchida/items/f8bc05eb91b944b6214e
- http://php.net/manual/ja/function.exec.php
