# 🌐 無料サーバーデプロイガイド

高校アメフト部デモサイトを無料サーバーに公開する方法

## 🚀 推奨無料サーバー

### 1. **000webhost** ⭐⭐⭐ (最推奨 - PHP対応)
- **PHP対応**: ✅ 完全対応
- **容量**: 1GB
- **帯域**: 月10GB
- **ドメイン**: 無料サブドメイン
- **認証機能**: ✅ 完全動作

### 2. **InfinityFree** ⭐⭐⭐ (PHP対応)
- **PHP対応**: ✅ 完全対応
- **容量**: 5GB
- **帯域**: 無制限
- **ドメイン**: 無料サブドメイン

### 3. **GitHub Pages + Netlify** ⭐⭐ (静的サイト版)
- **PHP対応**: ❌ 静的版のみ
- **容量**: 無制限
- **帯域**: 無制限
- **ドメイン**: 無料サブドメイン

## 📋 方法1: 000webhost (推奨)

### ステップ1: アカウント作成
1. [000webhost.com](https://www.000webhost.com/) にアクセス
2. 「Sign Up」をクリック
3. メールアドレスとパスワードを設定
4. 無料アカウントを作成

### ステップ2: ウェブサイト作成
1. ダッシュボードで「Create New Website」
2. サイト名を入力（例: `football-demo-123`）
3. 無料サブドメインを選択
4. 「Create」をクリック

### ステップ3: ファイルアップロード
1. 「File Manager」をクリック
2. 「Upload Files」を選択
3. 以下のファイルをアップロード：

```
public_html/
├── login.php
├── auth.php  
├── index-protected.php
├── .htaccess
├── css/style.css
├── js/script.js
├── js/contact.js
├── php/contact-demo.php
├── php/config.php
└── images/ (空のフォルダ)
```

### ステップ4: アクセス確認
- URL: `https://your-site-name.000webhostapp.com`
- ログイン: `demo` / `football2024`

## 📋 方法2: InfinityFree

### ステップ1: アカウント作成
1. [infinityfree.net](https://infinityfree.net/) にアクセス
2. 「Sign Up」でアカウント作成
3. メール認証を完了

### ステップ2: ウェブサイト作成
1. 「Create Account」をクリック
2. サブドメインを選択
3. アカウント作成を完了

### ステップ3: ファイルアップロード
1. 「Control Panel」→「File Manager」
2. 「htdocs」フォルダに移動
3. 全ファイルをアップロード

## 📋 方法3: 静的サイト版 (GitHub Pages)

認証機能は使えませんが、サイトのデザインを見せるだけなら十分：

### ステップ1: GitHub準備
```bash
# GitHubリポジトリ作成用
cd /Users/yusukeyoshida/coding/claude/football-team
```

### ステップ2: 静的版ファイル準備
`index.html`, `members.html`, `schedule.html`, `contact.html` をそのまま使用

### ステップ3: GitHub Pages設定
1. GitHubでリポジトリ作成
2. ファイルをアップロード
3. Settings → Pages → Source: main branch

## 🔧 デプロイ前の設定変更

### PHP版の場合
`auth.php`の設定確認：

```php
// 本番環境の場合はHTTPS強制
if ($_SERVER['HTTP_HOST'] !== 'localhost') {
    if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
        $redirect_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header('Location: ' . $redirect_url, true, 301);
        exit;
    }
}
```

### 静的版の場合
お問い合わせフォームを無効化：

```javascript
// contact.js に追加
function submitForm() {
    showStaticMessage();
}

function showStaticMessage() {
    alert('これは静的デモサイトです。\nお問い合わせ機能は実装されていません。');
}
```

## 📱 各サービスの詳細手順

### 🔥 000webhost 詳細手順

1. **サインアップ**
   - https://www.000webhost.com/
   - 「Free Sign Up」をクリック
   - Email、Password、Website nameを入力
   - 「Claim My Free Website」をクリック

2. **ファイルアップロード**
   - 「Manage Website」をクリック
   - 「Upload Files」を選択
   - ファイルを選択してアップロード
   - または「File Manager」でフォルダ構造を作成

3. **確認事項**
   - PHPバージョンが7.4以上か確認
   - .htaccessが動作するか確認
   - セッション機能が有効か確認

### ⚡ InfinityFree 詳細手順

1. **アカウント作成**
   - https://infinityfree.net/
   - 「Sign Up」をクリック
   - フォームに入力して登録

2. **ホスティングアカウント作成**
   - 「Create Account」をクリック
   - 希望のサブドメインを入力
   - 作成を実行

3. **ファイルマネージャー使用**
   - cPanelにログイン
   - 「File Manager」を開く
   - 「htdocs」フォルダにファイルをアップロード

## 🛠️ トラブルシューティング

### よくある問題

**PHPエラーが表示される**
- PHPバージョンを確認
- エラーログを確認
- ファイルパーミッションを確認

**.htaccessが動作しない**
- mod_rewriteが有効か確認
- サーバーが.htaccessをサポートしているか確認

**セッションが保存されない**
- session.save_pathの設定確認
- 書き込み権限の確認

**ファイルアップロードができない**
- ファイルサイズ制限を確認
- 対応ファイル形式を確認
- 一度に大量アップロードしない

## 📊 サービス比較表

| サービス | PHP | 容量 | 帯域 | 広告 | SSL | 推奨度 |
|----------|-----|------|------|------|-----|---------|
| 000webhost | ✅ | 1GB | 10GB/月 | なし | ✅ | ⭐⭐⭐ |
| InfinityFree | ✅ | 5GB | 無制限 | なし | ✅ | ⭐⭐⭐ |
| GitHub Pages | ❌ | 1GB | 100GB/月 | なし | ✅ | ⭐⭐ |
| Netlify | ❌ | 1GB | 100GB/月 | なし | ✅ | ⭐⭐ |

## 🎯 推奨デプロイ戦略

### フル機能版 (認証あり)
1. **000webhost** または **InfinityFree** を使用
2. PHP認証機能を有効活用
3. お問い合わせフォームも動作

### 簡易版 (デザイン確認用)
1. **GitHub Pages** または **Netlify** を使用
2. 静的HTMLファイルのみ
3. 認証なし・フォーム無効

## 📞 サポート

デプロイに問題がある場合は、各サービスのサポートページを確認するか、コミュニティフォーラムで質問してください。

---

**次のステップ**: 選択したサービスでデプロイを実行し、動作確認を行ってください。

**推奨**: まず000webhostで試してみることをお勧めします！