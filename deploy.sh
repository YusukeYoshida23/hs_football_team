#!/bin/bash

# 🚀 高校アメフト部デモサイト - 簡単デプロイスクリプト
# 使い方: ./deploy.sh [service]
# service: 000webhost, github, netlify

echo "🏈 高校アメフト部デモサイト デプロイスクリプト"
echo "================================================"

SERVICE=${1:-"000webhost"}

case $SERVICE in
    "000webhost")
        echo "📁 000webhost用ファイル準備中..."
        
        # デプロイ用ディレクトリ作成
        mkdir -p deploy-000webhost
        
        # 必要ファイルをコピー
        cp login.php deploy-000webhost/
        cp auth.php deploy-000webhost/
        cp index-protected.php deploy-000webhost/
        cp .htaccess deploy-000webhost/
        cp -r css deploy-000webhost/
        cp -r js deploy-000webhost/
        cp -r php deploy-000webhost/
        mkdir -p deploy-000webhost/images
        
        echo "✅ ファイル準備完了"
        echo ""
        echo "📋 次の手順:"
        echo "1. https://www.000webhost.com/ でアカウント作成"
        echo "2. 新しいウェブサイトを作成"
        echo "3. deploy-000webhost/ フォルダ内の全ファイルをアップロード"
        echo "4. サイトURL/login.php にアクセス"
        echo ""
        echo "🔐 認証情報:"
        echo "   ユーザー名: demo"
        echo "   パスワード: football2024"
        ;;
        
    "github")
        echo "📁 GitHub Pages用ファイル準備中..."
        
        # 静的版ディレクトリ作成
        mkdir -p deploy-github
        
        # HTMLファイルをコピー（認証なし版）
        cp index.html deploy-github/
        cp members.html deploy-github/
        cp schedule.html deploy-github/
        cp contact.html deploy-github/
        cp -r css deploy-github/
        cp -r js deploy-github/
        mkdir -p deploy-github/images
        
        # 静的版用の設定追加
        cat > deploy-github/README.md << 'EOF'
# 高校アメフト部デモサイト

GitHub Pages版（静的サイト）

## 機能制限
- 認証機能なし
- お問い合わせフォーム無効
- デザイン確認用

## アクセス
https://your-username.github.io/football-demo/
EOF
        
        echo "✅ ファイル準備完了"
        echo ""
        echo "📋 次の手順:"
        echo "1. GitHubでリポジトリ作成"
        echo "2. deploy-github/ フォルダ内容をアップロード"
        echo "3. Settings → Pages → Source: main branch"
        echo "4. 生成されたURLにアクセス"
        ;;
        
    "netlify")
        echo "📁 Netlify用ファイル準備中..."
        
        # Netlify用ディレクトリ作成
        mkdir -p deploy-netlify
        
        # 静的ファイルコピー
        cp index.html deploy-netlify/
        cp members.html deploy-netlify/
        cp schedule.html deploy-netlify/
        cp contact.html deploy-netlify/
        cp -r css deploy-netlify/
        cp -r js deploy-netlify/
        mkdir -p deploy-netlify/images
        
        # Netlify設定ファイル作成
        cat > deploy-netlify/netlify.toml << 'EOF'
[build]
  publish = "."

[[headers]]
  for = "/*"
  [headers.values]
    X-Frame-Options = "DENY"
    X-XSS-Protection = "1; mode=block"
    X-Content-Type-Options = "nosniff"

[[redirects]]
  from = "/admin/*"
  to = "/404.html"
  status = 404
EOF
        
        echo "✅ ファイル準備完了"
        echo ""
        echo "📋 次の手順:"
        echo "1. https://netlify.com でアカウント作成"
        echo "2. 'Deploy manually'を選択"
        echo "3. deploy-netlify/ フォルダをドラッグ&ドロップ"
        echo "4. 生成されたURLにアクセス"
        ;;
        
    *)
        echo "❌ 不明なサービス: $SERVICE"
        echo ""
        echo "利用可能なサービス:"
        echo "  ./deploy.sh 000webhost  (推奨 - PHP認証あり)"
        echo "  ./deploy.sh github      (静的版)"
        echo "  ./deploy.sh netlify     (静的版)"
        exit 1
        ;;
esac

echo ""
echo "🎉 デプロイ準備完了！"
echo "📖 詳細な手順は DEPLOY-GUIDE.md を参照してください"