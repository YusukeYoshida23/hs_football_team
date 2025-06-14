# ⚡ 超簡単デプロイガイド

**5分でデモサイトを公開する方法**

## 🎯 最速デプロイ手順

### 方法1: 000webhost (推奨 - 認証機能あり)

1. **アカウント作成** (2分)
   - https://www.000webhost.com/ にアクセス
   - 「Free Sign Up」をクリック
   - メール・パスワード・サイト名を入力
   - 「Claim My Free Website」をクリック

2. **ファイルアップロード** (2分)
   ```bash
   cd /Users/yusukeyoshida/coding/claude/football-team
   ./deploy.sh 000webhost
   ```
   - 生成された `deploy-000webhost` フォルダ内の全ファイルをアップロード
   - 000webhostの「File Manager」→「Upload Files」を使用

3. **アクセス** (1分)
   - `https://your-site-name.000webhostapp.com/login.php`
   - ユーザー名: `demo` / パスワード: `football2024`

### 方法2: Netlify (超簡単 - 静的版)

1. **デプロイ** (1分)
   ```bash
   cd /Users/yusukeyoshida/coding/claude/football-team
   ./deploy.sh netlify
   ```

2. **アップロード** (1分)
   - https://netlify.com にアクセス
   - 「Deploy manually」をクリック
   - `deploy-netlify` フォルダをドラッグ&ドロップ

3. **完了**
   - 即座にURLが生成される
   - 認証なしで誰でもアクセス可能

## 📱 会社の人への共有方法

### 認証あり版 (000webhost)
```
件名: 【デモサイト公開】高校アメフト部ホームページ

デモサイトを公開しました！

🌐 URL: https://football-demo-123.000webhostapp.com/
🔐 ユーザー名: demo
🔐 パスワード: football2024

※セッションは1時間有効です
※スマホ・PCどちらでも閲覧可能

ご意見お聞かせください！
```

### 静的版 (Netlify)
```
件名: 【デモサイト公開】高校アメフト部ホームページ

デモサイトを公開しました！

🌐 URL: https://amazing-site-123.netlify.app/

※認証不要で誰でも閲覧可能
※お問い合わせフォームは無効化済み
※デザイン確認用

ご確認よろしくお願いします！
```

## 🚀 今すぐ実行

ターミナルで以下を実行：

```bash
cd /Users/yusukeyoshida/coding/claude/football-team

# 認証機能付きバージョン
./deploy.sh 000webhost

# または 簡単な静的バージョン
./deploy.sh netlify
```

## 🎊 完了！

選択した方法でデプロイすれば、すぐに会社の人にURLを共有できます！