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
- PHPのコードのフォルダーで`php -S 0.0.0.0:5678`でサーバー起動
- GitHubでIssuesを作成
- 認証が届くか確認


# 参考URL
- https://developer.github.com/webhooks/
- https://qiita.com/oyas/items/1cbdc3e0ac35d4316885
