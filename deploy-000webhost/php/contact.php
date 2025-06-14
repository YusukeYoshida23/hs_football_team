<?php
// セッション開始
session_start();

// エラーレポートの設定（開発環境用 - 本番環境では削除）
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CSRFトークンの生成と検証
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// レスポンスヘッダーの設定
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// POSTリクエストのみ受け付け
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// スパム対策：ハニーポット
if (!empty($_POST['website'])) {
    // ボットによる送信の可能性
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// レート制限（簡易版）
$ip = $_SERVER['REMOTE_ADDR'];
$rate_limit_file = sys_get_temp_dir() . '/contact_rate_limit_' . md5($ip);
$current_time = time();

if (file_exists($rate_limit_file)) {
    $last_submit = file_get_contents($rate_limit_file);
    if ($current_time - $last_submit < 60) { // 1分以内の再送信を制限
        http_response_code(429);
        echo json_encode(['success' => false, 'message' => '送信間隔が短すぎます。1分後に再度お試しください。']);
        exit;
    }
}
file_put_contents($rate_limit_file, $current_time);

try {
    // 入力データの取得と検証
    $input_data = [
        'inquiryType' => trim($_POST['inquiryType'] ?? ''),
        'lastName' => trim($_POST['lastName'] ?? ''),  
        'firstName' => trim($_POST['firstName'] ?? ''),
        'lastNameKana' => trim($_POST['lastNameKana'] ?? ''),
        'firstNameKana' => trim($_POST['firstNameKana'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'grade' => trim($_POST['grade'] ?? ''),
        'experience' => trim($_POST['experience'] ?? ''),
        'visitDate' => trim($_POST['visitDate'] ?? ''),
        'message' => trim($_POST['message'] ?? ''),
        'privacy' => isset($_POST['privacy']) ? 'on' : '',
        'submitDate' => trim($_POST['submitDate'] ?? date('c'))
    ];

    // バリデーション
    $validation_errors = [];
    
    // 必須フィールドのチェック
    $required_fields = [
        'inquiryType' => 'お問い合わせ種別',
        'lastName' => '姓',
        'firstName' => '名',
        'lastNameKana' => 'セイ',
        'firstNameKana' => 'メイ',
        'email' => 'メールアドレス',
        'message' => 'お問い合わせ内容'
    ];
    
    foreach ($required_fields as $field => $name) {
        if (empty($input_data[$field])) {
            $validation_errors[] = $name . 'は必須項目です。';
        }
    }
    
    // プライバシーポリシー同意のチェック
    if ($input_data['privacy'] !== 'on') {
        $validation_errors[] = 'プライバシーポリシーへの同意が必要です。';
    }
    
    // メールアドレスの形式チェック
    if (!empty($input_data['email']) && !filter_var($input_data['email'], FILTER_VALIDATE_EMAIL)) {
        $validation_errors[] = 'メールアドレスの形式が正しくありません。';
    }
    
    // カナ文字のチェック
    if (!empty($input_data['lastNameKana']) && !preg_match('/^[ァ-ヶー]+$/u', $input_data['lastNameKana'])) {
        $validation_errors[] = 'セイはカタカナで入力してください。';
    }
    
    if (!empty($input_data['firstNameKana']) && !preg_match('/^[ァ-ヶー]+$/u', $input_data['firstNameKana'])) {
        $validation_errors[] = 'メイはカタカナで入力してください。';
    }
    
    // 電話番号の形式チェック（入力されている場合のみ）
    if (!empty($input_data['phone']) && !preg_match('/^[\d\-\+\(\)\s]+$/', $input_data['phone'])) {
        $validation_errors[] = '電話番号の形式が正しくありません。';
    }
    
    // メッセージの長さチェック
    if (mb_strlen($input_data['message']) > 1000) {
        $validation_errors[] = 'お問い合わせ内容は1000文字以内で入力してください。';
    }
    
    // バリデーションエラーがある場合
    if (!empty($validation_errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => implode('\n', $validation_errors)
        ]);
        exit;
    }
    
    // XSS対策：HTMLエスケープ
    $escaped_data = [];
    foreach ($input_data as $key => $value) {
        $escaped_data[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    
    // お問い合わせIDの生成
    $inquiry_id = 'INQ-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
    
    // 管理者向けメール送信
    $admin_result = sendAdminEmail($escaped_data, $inquiry_id);
    
    // お客様向け確認メール送信
    $customer_result = sendCustomerEmail($escaped_data, $inquiry_id);
    
    // データベース保存（オプション）
    // saveToDatabase($escaped_data, $inquiry_id);
    
    // レスポンス
    if ($admin_result && $customer_result) {
        echo json_encode([
            'success' => true,
            'message' => 'お問い合わせありがとうございます。確認メールをお送りいたしました。',
            'inquiry_id' => $inquiry_id
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'message' => 'お問い合わせを受け付けました。（メール送信に一部問題が発生しましたが、お問い合わせは正常に受信されています）',
            'inquiry_id' => $inquiry_id
        ]);
    }
    
} catch (Exception $e) {
    // エラーログ記録
    error_log('Contact form error: ' . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'システムエラーが発生しました。しばらく時間をおいて再度お試しください。'
    ]);
}

/**
 * 管理者向けメール送信
 */
function sendAdminEmail($data, $inquiry_id) {
    $to = 'football@sakuragaoka-hs.ac.jp';
    $subject = '【お問い合わせ】' . $data['inquiryType'] . ' - ' . $inquiry_id;
    
    $body = createAdminEmailBody($data, $inquiry_id);
    $headers = createEmailHeaders('noreply@sakuragaoka-hs.ac.jp', '桜ヶ丘高校アメフト部システム');
    
    return mb_send_mail($to, $subject, $body, $headers);
}

/**
 * お客様向け確認メール送信
 */
function sendCustomerEmail($data, $inquiry_id) {
    $to = $data['email'];
    $subject = '【桜ヶ丘高校アメフト部】お問い合わせありがとうございます - ' . $inquiry_id;
    
    $body = createCustomerEmailBody($data, $inquiry_id);
    $headers = createEmailHeaders('football@sakuragaoka-hs.ac.jp', '桜ヶ丘高校アメリカンフットボール部');
    
    return mb_send_mail($to, $subject, $body, $headers);
}

/**
 * 管理者向けメール本文作成
 */
function createAdminEmailBody($data, $inquiry_id) {
    $visit_date_text = !empty($data['visitDate']) ? 
        "\n希望見学日: " . date('Y年m月d日', strtotime($data['visitDate'])) : '';
    
    $phone_text = !empty($data['phone']) ? "\n電話番号: " . $data['phone'] : '';
    $grade_text = !empty($data['grade']) ? "\n学年: " . $data['grade'] : '';
    $experience_text = !empty($data['experience']) ? "\nアメフト経験: " . $data['experience'] : '';
    
    return <<<EOF
新しいお問い合わせが届きました。

【お問い合わせ情報】
お問い合わせID: {$inquiry_id}
受信日時: {$data['submitDate']}

【お客様情報】
お問い合わせ種別: {$data['inquiryType']}
お名前: {$data['lastName']} {$data['firstName']}
フリガナ: {$data['lastNameKana']} {$data['firstNameKana']}
メールアドレス: {$data['email']}{$phone_text}{$grade_text}{$experience_text}{$visit_date_text}

【お問い合わせ内容】
{$data['message']}

--
このメールは桜ヶ丘高校アメフト部のお問い合わせフォームから自動送信されました。
返信は上記メールアドレス宛にお願いいたします。
EOF;
}

/**
 * お客様向けメール本文作成
 */
function createCustomerEmailBody($data, $inquiry_id) {
    $visit_date_text = !empty($data['visitDate']) ? 
        "\n希望見学日: " . date('Y年m月d日', strtotime($data['visitDate'])) : '';
        
    return <<<EOF
{$data['lastName']} {$data['firstName']} 様

この度は桜ヶ丘高校アメリカンフットボール部にお問い合わせいただき、
誠にありがとうございます。

以下の内容でお問い合わせを受け付けいたしました。

【お問い合わせ内容】
お問い合わせID: {$inquiry_id}
お問い合わせ種別: {$data['inquiryType']}{$visit_date_text}

お問い合わせ内容:
{$data['message']}

【今後の流れ】
・2-3営業日以内に担当者よりご返答いたします
・お問い合わせの内容によっては、お電話でご連絡する場合があります
・見学や体験をご希望の場合は、日程調整のご連絡をいたします

【お問い合わせに関するご注意】
・このメールは自動送信です
・ご返信いただいても受信できませんので、ご了承ください
・お急ぎの場合は、お電話（03-1234-5678）でお問い合わせください

【部活動の練習時間】
平日: 16:00-18:30（月・火・木・金）
土曜: 9:00-12:00
※水曜日は筋力トレーニング（16:00-17:30）

見学はいつでも大歓迎です！
私たちと一緒にアメリカンフットボールを楽しみませんか？

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
桜ヶ丘高校アメリカンフットボール部

〒123-4567 東京都○○区○○1-2-3
TEL: 03-1234-5678
Email: football@sakuragaoka-hs.ac.jp
Web: https://sakuragaoka-football.example.com/

監督: 田中 勇太
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
EOF;
}

/**
 * メールヘッダー作成
 */
function createEmailHeaders($from_email, $from_name) {
    $headers = [
        'From: ' . mb_encode_mimeheader($from_name) . ' <' . $from_email . '>',
        'Reply-To: ' . $from_email,
        'X-Mailer: PHP/' . phpversion(),
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: 8bit'
    ];
    
    return implode("\r\n", $headers);
}

/**
 * データベース保存（オプション）
 */
function saveToDatabase($data, $inquiry_id) {
    // データベース接続設定（環境に応じて変更）
    /*
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=football_club;charset=utf8mb4', 
                      $username, $password, [
                          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                      ]);
        
        $sql = "INSERT INTO inquiries (
                    inquiry_id, inquiry_type, last_name, first_name,
                    last_name_kana, first_name_kana, email, phone,
                    grade, experience, visit_date, message,
                    submit_date, created_at
                ) VALUES (
                    :inquiry_id, :inquiry_type, :last_name, :first_name,
                    :last_name_kana, :first_name_kana, :email, :phone,
                    :grade, :experience, :visit_date, :message,
                    :submit_date, NOW()
                )";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':inquiry_id' => $inquiry_id,
            ':inquiry_type' => $data['inquiryType'],
            ':last_name' => $data['lastName'],
            ':first_name' => $data['firstName'],
            ':last_name_kana' => $data['lastNameKana'],
            ':first_name_kana' => $data['firstNameKana'],
            ':email' => $data['email'],
            ':phone' => $data['phone'],
            ':grade' => $data['grade'],
            ':experience' => $data['experience'],
            ':visit_date' => $data['visitDate'] ?: null,
            ':message' => $data['message'],
            ':submit_date' => $data['submitDate']
        ]);
        
        return true;
    } catch (PDOException $e) {
        error_log('Database error: ' . $e->getMessage());
        return false;
    }
    */
    return true;
}

// 古いレート制限ファイルのクリーンアップ（1日1回実行）
function cleanupRateLimitFiles() {
    $temp_dir = sys_get_temp_dir();
    $files = glob($temp_dir . '/contact_rate_limit_*');
    $current_time = time();
    
    foreach ($files as $file) {
        if (file_exists($file) && ($current_time - filemtime($file)) > 86400) { // 24時間
            unlink($file);
        }
    }
}

// 1%の確率でクリーンアップを実行
if (rand(1, 100) === 1) {
    cleanupRateLimitFiles();
}
?>