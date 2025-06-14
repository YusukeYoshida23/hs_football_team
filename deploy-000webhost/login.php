<?php
require_once 'auth.php';

// ログアウト処理
if (isset($_GET['logout'])) {
    processLogout();
}

// すでにログイン済みの場合はリダイレクト
if (checkAuth()) {
    $redirect = $_GET['redirect'] ?? 'index.html';
    header('Location: ' . $redirect);
    exit;
}

// ログイン処理
$error_message = processLogin();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>デモサイト認証 - 桜ヶ丘高校アメフト部</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            padding: 2rem;
        }
        
        .login-card {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            max-width: 400px;
            width: 100%;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-header i {
            font-size: 3rem;
            color: #1e3c72;
            margin-bottom: 1rem;
        }
        
        .login-header h1 {
            color: #1e3c72;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }
        
        .login-header p {
            color: #666;
            font-size: 0.9rem;
        }
        
        .demo-badge {
            background: #ffd700;
            color: #1e3c72;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 1.5rem;
        }
        
        .login-form {
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e9ecef;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #1e3c72;
        }
        
        .login-btn {
            width: 100%;
            background: #1e3c72;
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .login-btn:hover {
            background: #2a5298;
        }
        
        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1.5rem;
            border: 1px solid #f5c6cb;
        }
        
        .demo-info {
            background: #e3f2fd;
            color: #1565c0;
            padding: 1rem;
            border-radius: 5px;
            font-size: 0.9rem;
            line-height: 1.4;
        }
        
        .demo-info h3 {
            margin-bottom: 0.5rem;
            color: #1565c0;
        }
        
        .credentials {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
            border: 1px solid #dee2e6;
        }
        
        .credentials code {
            background: #e9ecef;
            padding: 0.2rem 0.4rem;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            color: #495057;
        }
        
        @media (max-width: 480px) {
            .login-card {
                padding: 2rem;
                margin: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-lock"></i>
                <h1>デモサイト認証</h1>
                <div class="demo-badge">DEMO VERSION</div>
                <p>桜ヶ丘高校アメフト部ホームページ</p>
            </div>

            <?php if ($error_message): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-triangle"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="username">ユーザー名</label>
                    <input type="text" id="username" name="username" required autocomplete="username">
                </div>

                <div class="form-group">
                    <label for="password">パスワード</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>

                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> ログイン
                </button>
            </form>

            <div class="demo-info">
                <h3><i class="fas fa-info-circle"></i> デモサイトについて</h3>
                <p>このデモサイトは会社関係者限定で公開されています。以下の認証情報でアクセスできます。</p>
                
                <div class="credentials">
                    <strong>認証情報:</strong><br>
                    ユーザー名: <code>demo</code><br>
                    パスワード: <code>football2024</code>
                </div>
                
                <p><strong>注意事項:</strong></p>
                <ul style="margin: 0; padding-left: 1.2rem;">
                    <li>セッションは1時間で自動切断されます</li>
                    <li>お問い合わせフォームはデモ版です</li>
                    <li>実際のメール送信は行われません</li>
                    <li>このサイトは開発中のため、内容は仮のものです</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        // エンターキーでログイン
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                document.querySelector('.login-btn').click();
            }
        });
        
        // フォーカス制御
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('username').focus();
        });
    </script>
</body>
</html>