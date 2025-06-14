<?php
/**
 * デモサイト限定公開用認証システム
 * 会社の人向けの簡易認証
 */

session_start();

// 認証設定
define('DEMO_USERNAME', 'demo');
define('DEMO_PASSWORD', 'football2024');
define('SESSION_TIMEOUT', 3600); // 1時間

/**
 * 認証チェック
 */
function checkAuth() {
    if (!isset($_SESSION['authenticated']) || 
        !$_SESSION['authenticated'] ||
        !isset($_SESSION['login_time']) ||
        (time() - $_SESSION['login_time']) > SESSION_TIMEOUT) {
        
        // セッションをクリア
        session_unset();
        session_destroy();
        return false;
    }
    
    return true;
}

/**
 * ログイン処理
 */
function processLogin() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        
        if ($username === DEMO_USERNAME && $password === DEMO_PASSWORD) {
            $_SESSION['authenticated'] = true;
            $_SESSION['login_time'] = time();
            $_SESSION['username'] = $username;
            
            // ログイン成功後のリダイレクト
            $redirect = $_GET['redirect'] ?? 'index.html';
            header('Location: ' . $redirect);
            exit;
        } else {
            return '認証に失敗しました。正しいユーザー名とパスワードを入力してください。';
        }
    }
    return null;
}

/**
 * ログアウト処理
 */
function processLogout() {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

/**
 * 認証が必要なページを保護
 */
function requireAuth() {
    if (!checkAuth()) {
        $current_page = $_SERVER['REQUEST_URI'];
        header('Location: login.php?redirect=' . urlencode($current_page));
        exit;
    }
}

/**
 * 認証状態を取得
 */
function getAuthStatus() {
    return [
        'authenticated' => checkAuth(),
        'username' => $_SESSION['username'] ?? null,
        'remaining_time' => checkAuth() ? (SESSION_TIMEOUT - (time() - $_SESSION['login_time'])) : 0
    ];
}
?>