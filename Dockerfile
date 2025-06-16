FROM php:8.1-cli

# 作業ディレクトリ
WORKDIR /app

# ファイルをコピー
COPY . /app

# ポート設定
EXPOSE 10000

# PHPサーバー起動
CMD ["sh", "-c", "php -S 0.0.0.0:${PORT:-10000}"]
