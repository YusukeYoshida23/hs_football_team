<?php
/**
 * お問い合わせフォーム デモ版
 * ローカル環境やテスト環境用の簡易版
 */

// レスポンスヘッダーの設定
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// プリフライトリクエストの処理
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// POSTリクエストのみ受け付け
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// 簡易的なスリープでリアルな送信時間をシミュレート
sleep(1);

try {
    // 入力データの取得
    $input_data = [
        'inquiryType' => trim($_POST['inquiryType'] ?? ''),
        'lastName' => trim($_POST['lastName'] ?? ''),  
        'firstName' => trim($_POST['firstName'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'message' => trim($_POST['message'] ?? ''),
        'privacy' => isset($_POST['privacy']) ? 'on' : ''
    ];

    // 基本的なバリデーション
    $errors = [];
    
    if (empty($input_data['inquiryType'])) {
        $errors[] = 'お問い合わせ種別は必須です';
    }
    
    if (empty($input_data['lastName'])) {
        $errors[] = '姓は必須です';
    }
    
    if (empty($input_data['firstName'])) {
        $errors[] = '名は必須です';
    }
    
    if (empty($input_data['email'])) {
        $errors[] = 'メールアドレスは必須です';
    } elseif (!filter_var($input_data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'メールアドレスの形式が正しくありません';
    }
    
    if (empty($input_data['message'])) {
        $errors[] = 'お問い合わせ内容は必須です';
    }
    
    if ($input_data['privacy'] !== 'on') {
        $errors[] = 'プライバシーポリシーへの同意が必要です';
    }

    if (!empty($errors)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => implode(', ', $errors)
        ]);
        exit;
    }

    // お問い合わせIDの生成
    $inquiry_id = 'DEMO-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
    
    // デモ用ログファイルに保存
    $log_data = [
        'timestamp' => date('Y-m-d H:i:s'),
        'inquiry_id' => $inquiry_id,
        'data' => $input_data
    ];
    
    $log_file = __DIR__ . '/demo_inquiries.json';
    $existing_logs = [];
    
    if (file_exists($log_file)) {
        $existing_logs = json_decode(file_get_contents($log_file), true) ?? [];
    }
    
    $existing_logs[] = $log_data;
    file_put_contents($log_file, json_encode($existing_logs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    // 成功レスポンス
    echo json_encode([
        'success' => true,
        'message' => 'お問い合わせありがとうございます。（デモ版のため実際のメールは送信されません）',
        'inquiry_id' => $inquiry_id,
        'demo_mode' => true
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'システムエラーが発生しました: ' . $e->getMessage()
    ]);
}
?>