#!/bin/bash

echo "🚀 Render用デプロイファイル準備"
echo "================================"

# Render用ディレクトリ作成
mkdir -p deploy-render

# 必要ファイルをコピー
cp login.php deploy-render/
cp auth.php deploy-render/
cp index-protected.php deploy-render/
cp .htaccess deploy-render/
cp -r css deploy-render/
cp -r js deploy-render/
cp -r php deploy-render/
mkdir -p deploy-render/images

# Render設定ファイル作成
cat > deploy-render/render.yaml << 'EOF'
services:
  - type: web
    name: football-demo
    env: php
    buildCommand: |
      echo "Building PHP application..."
    startCommand: |
      php -S 0.0.0.0:$PORT -t .
    envVars:
      - key: PHP_VERSION
        value: "8.1"
EOF

# composer.jsonを作成（PHP環境設定用）
cat > deploy-render/composer.json << 'EOF'
{
    "name": "football-demo/website",
    "description": "高校アメフト部デモサイト",
    "require": {
        "php": ">=7.4"
    },
    "scripts": {
        "start": "php -S 0.0.0.0:$PORT -t ."
    }
}
EOF

# README.md作成
cat > deploy-render/README.md << 'EOF'
# 高校アメフト部デモサイト

## 認証情報
- ユーザー名: demo
- パスワード: football2024

## デプロイ方法
1. このリポジトリをGitHubにプッシュ
2. Renderでサービス作成
3. GitHubリポジトリを連携
4. 自動デプロイ完了

## アクセス
https://your-app-name.onrender.com/login.php
EOF

echo "✅ Render用ファイル準備完了"
echo ""
echo "📋 次の手順:"
echo "1. GitHubでリポジトリ作成"
echo "2. deploy-render/ 内容をプッシュ"
echo "3. https://render.com でアカウント作成"
echo "4. 'New Web Service' → GitHubリポジトリ選択"
echo "5. 完了！"
echo ""
echo "🔐 認証情報:"
echo "   ユーザー名: demo"
echo "   パスワード: football2024"