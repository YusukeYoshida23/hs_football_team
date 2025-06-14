<?php
require_once 'auth.php';
requireAuth();
$auth_status = getAuthStatus();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>桜ヶ丘高校アメリカンフットボール部 - ホーム（デモ版）</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .demo-banner {
            background: linear-gradient(90deg, #ffd700, #ffed4a);
            color: #1e3c72;
            padding: 0.5rem 0;
            text-align: center;
            font-weight: bold;
            font-size: 0.9rem;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1001;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .demo-banner .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .demo-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.8rem;
        }
        
        .logout-btn {
            background: #1e3c72;
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            text-decoration: none;
            font-size: 0.8rem;
            transition: background 0.3s;
        }
        
        .logout-btn:hover {
            background: #2a5298;
        }
        
        .remaining-time {
            color: #666;
        }
        
        header {
            margin-top: 40px;
        }
        
        main {
            margin-top: 120px;
        }
        
        @media (max-width: 768px) {
            .demo-banner .container {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .demo-info, .user-info {
                font-size: 0.8rem;
            }
            
            main {
                margin-top: 140px;
            }
        }
    </style>
</head>
<body>
    <div class="demo-banner">
        <div class="container">
            <div class="demo-info">
                <i class="fas fa-eye"></i>
                <span>デモサイト（限定公開中）- 開発用プレビュー版</span>
            </div>
            <div class="user-info">
                <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($auth_status['username']); ?></span>
                <span class="remaining-time">
                    <i class="fas fa-clock"></i> 
                    残り: <span id="remaining-time"><?php echo floor($auth_status['remaining_time'] / 60); ?></span>分
                </span>
                <a href="login.php?logout=1" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> ログアウト
                </a>
            </div>
        </div>
    </div>

    <header>
        <nav class="navbar">
            <div class="nav-container">
                <div class="logo">
                    <h1><i class="fas fa-football-ball"></i> 桜ヶ丘高校アメフト部</h1>
                </div>
                <ul class="nav-menu">
                    <li><a href="index-protected.php" class="active">ホーム</a></li>
                    <li><a href="members-protected.php">メンバー紹介</a></li>
                    <li><a href="schedule-protected.php">スケジュール</a></li>
                    <li><a href="contact-protected.php">お問い合わせ</a></li>
                </ul>
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h1>桜ヶ丘高校アメリカンフットボール部</h1>
                <p class="hero-subtitle">チーム一丸となって全国大会を目指します</p>
                <div class="hero-buttons">
                    <a href="members-protected.php" class="btn btn-primary">メンバー紹介</a>
                    <a href="contact-protected.php" class="btn btn-secondary">入部希望者はこちら</a>
                </div>
            </div>
        </section>

        <section class="about">
            <div class="container">
                <h2>チームについて</h2>
                <div class="about-grid">
                    <div class="about-text">
                        <h3>私たちの目標</h3>
                        <p>桜ヶ丘高校アメリカンフットボール部は、チームワークと個人の成長を大切にしながら、全国大会出場を目指しています。初心者も経験者も大歓迎です。</p>
                        
                        <h3>活動内容</h3>
                        <ul>
                            <li>基礎体力向上トレーニング</li>
                            <li>フォーメーション練習</li>
                            <li>戦術理解とゲーム分析</li>
                            <li>チームビルディング</li>
                        </ul>
                    </div>
                    <div class="about-stats">
                        <div class="stat-item">
                            <h3>30</h3>
                            <p>部員数</p>
                        </div>
                        <div class="stat-item">
                            <h3>3</h3>
                            <p>コーチ陣</p>
                        </div>
                        <div class="stat-item">
                            <h3>2023</h3>
                            <p>県大会ベスト8</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="news">
            <div class="container">
                <h2>最新情報</h2>
                <div class="news-grid">
                    <div class="news-item">
                        <div class="news-date">2024.03.15</div>
                        <h3>春季練習開始のお知らせ</h3>
                        <p>新年度に向けて春季練習を開始いたします。新入部員も募集中です。</p>
                    </div>
                    <div class="news-item">
                        <div class="news-date">2024.03.01</div>
                        <h3>県大会結果報告</h3>
                        <p>先日行われた県大会でベスト8の結果を収めることができました。</p>
                    </div>
                    <div class="news-item">
                        <div class="news-date">2024.02.20</div>
                        <h3>体験入部会開催</h3>
                        <p>中学生を対象とした体験入部会を開催いたします。詳細はお問い合わせください。</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="cta">
            <div class="container">
                <h2>入部をお考えの方へ</h2>
                <p>アメリカンフットボールに興味がある方、チームスポーツで成長したい方、ぜひ一度見学にお越しください。</p>
                <a href="contact-protected.php" class="btn btn-primary">お問い合わせ</a>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-info">
                    <h3>桜ヶ丘高校アメリカンフットボール部</h3>
                    <p><i class="fas fa-map-marker-alt"></i> 〒123-4567 東京都○○区○○1-2-3</p>
                    <p><i class="fas fa-phone"></i> TEL: 03-1234-5678</p>
                    <p><i class="fas fa-envelope"></i> football@sakuragaoka-hs.ac.jp</p>
                    <p style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1); color: #ffd700; font-size: 0.9rem;">
                        <i class="fas fa-info-circle"></i> このサイトはデモ版です。実際の学校・部活動とは関係ありません。
                    </p>
                </div>
                <div class="footer-links">
                    <h4>リンク</h4>
                    <ul>
                        <li><a href="index-protected.php">ホーム</a></li>
                        <li><a href="members-protected.php">メンバー紹介</a></li>
                        <li><a href="schedule-protected.php">スケジュール</a></li>
                        <li><a href="contact-protected.php">お問い合わせ</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 桜ヶ丘高校アメリカンフットボール部 デモサイト. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="js/script.js"></script>
    <script>
        // 残り時間のカウントダウン
        let remainingMinutes = <?php echo floor($auth_status['remaining_time'] / 60); ?>;
        let remainingSeconds = <?php echo $auth_status['remaining_time'] % 60; ?>;
        
        setInterval(function() {
            remainingSeconds--;
            if (remainingSeconds < 0) {
                remainingMinutes--;
                remainingSeconds = 59;
            }
            
            if (remainingMinutes <= 0 && remainingSeconds <= 0) {
                alert('セッションが切れました。再度ログインしてください。');
                window.location.href = 'login.php';
                return;
            }
            
            document.getElementById('remaining-time').textContent = remainingMinutes;
            
            // 5分を切ったら警告色に
            if (remainingMinutes < 5) {
                document.querySelector('.remaining-time').style.color = '#e74c3c';
            }
        }, 1000);
    </script>
</body>
</html>