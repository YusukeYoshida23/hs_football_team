<?php
/**
 * 設定ファイル
 * 桜ヶ丘高校アメリカンフットボール部 ホームページ
 */

// 基本設定
define('SITE_NAME', '桜ヶ丘高校アメリカンフットボール部');
define('SITE_URL', 'https://sakuragaoka-football.example.com');
define('ADMIN_EMAIL', 'football@sakuragaoka-hs.ac.jp');
define('NOREPLY_EMAIL', 'noreply@sakuragaoka-hs.ac.jp');

// メール設定
define('MAIL_FROM_NAME', '桜ヶ丘高校アメリカンフットボール部');
define('MAIL_ENCODING', 'UTF-8');

// セキュリティ設定
define('CSRF_TOKEN_LIFETIME', 3600); // 1時間
define('RATE_LIMIT_INTERVAL', 60); // 1分
define('MAX_MESSAGE_LENGTH', 1000);

// データベース設定（使用する場合）
/*
define('DB_HOST', 'localhost');
define('DB_NAME', 'football_club');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_CHARSET', 'utf8mb4');
*/

// エラーログ設定
define('ERROR_LOG_FILE', __DIR__ . '/logs/error.log');

// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');

// 文字エンコーディング設定
mb_internal_encoding('UTF-8');
mb_language('Japanese');

// セッション設定
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);

// 本番環境での設定（本番環境でのみ有効にする）
if ($_SERVER['HTTP_HOST'] !== 'localhost' && !str_contains($_SERVER['HTTP_HOST'], '.local')) {
    // エラー表示を無効化
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
    
    // HTTPS強制
    if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
        $redirect_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header('Location: ' . $redirect_url, true, 301);
        exit;
    }
}

/**
 * ユーティリティ関数
 */

/**
 * 安全なリダイレクト
 */
function safe_redirect($url) {
    // 相対URLまたは同一ドメインのみ許可
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $parsed = parse_url($url);
        $site_parsed = parse_url(SITE_URL);
        if ($parsed['host'] !== $site_parsed['host']) {
            $url = '/';
        }
    }
    
    header('Location: ' . $url, true, 302);
    exit;
}

/**
 * XSS対策のHTMLエスケープ
 */
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * CSRFトークンの生成
 */
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token']) || 
        !isset($_SESSION['csrf_token_time']) ||
        (time() - $_SESSION['csrf_token_time']) > CSRF_TOKEN_LIFETIME) {
        
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * CSRFトークンの検証
 */
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && 
           isset($_SESSION['csrf_token_time']) &&
           (time() - $_SESSION['csrf_token_time']) <= CSRF_TOKEN_LIFETIME &&
           hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * エラーログ記録
 */
function log_error($message, $context = []) {
    $log_message = date('Y-m-d H:i:s') . ' - ' . $message;
    if (!empty($context)) {
        $log_message .= ' - Context: ' . json_encode($context, JSON_UNESCAPED_UNICODE);
    }
    $log_message .= PHP_EOL;
    
    // ログディレクトリが存在しない場合は作成
    $log_dir = dirname(ERROR_LOG_FILE);
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
    
    error_log($log_message, 3, ERROR_LOG_FILE);
}

/**
 * IPアドレス取得
 */
function get_client_ip() {
    $ip_headers = [
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_REAL_IP',
        'HTTP_CLIENT_IP',
        'REMOTE_ADDR'
    ];
    
    foreach ($ip_headers as $header) {
        if (isset($_SERVER[$header]) && !empty($_SERVER[$header])) {
            $ips = explode(',', $_SERVER[$header]);
            $ip = trim($ips[0]);
            
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                return $ip;
            }
        }
    }
    
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

/**
 * ファイルアップロードのセキュリティチェック
 */
function is_safe_upload($file) {
    // 許可する拡張子
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
    
    // 許可するMIMEタイプ
    $allowed_mime_types = [
        'image/jpeg',
        'image/png', 
        'image/gif',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    // ファイルサイズチェック（5MB制限）
    if ($file['size'] > 5 * 1024 * 1024) {
        return false;
    }
    
    // 拡張子チェック
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowed_extensions)) {
        return false;
    }
    
    // MIMEタイプチェック
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime_type, $allowed_mime_types)) {
        return false;
    }
    
    return true;
}

/**
 * 日本語の曜日取得
 */
function get_japanese_weekday($date) {
    $weekdays = ['日', '月', '火', '水', '木', '金', '土'];
    return $weekdays[date('w', strtotime($date))];
}

/**
 * 日本語の日付フォーマット
 */
function format_japanese_date($date, $format = 'Y年m月d日') {
    return date($format, strtotime($date));
}
?>