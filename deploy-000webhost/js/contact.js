// お問い合わせフォーム専用JavaScript

document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const successModal = document.getElementById('success-modal');
    
    if (contactForm) {
        setupContactForm();
    }
});

function setupContactForm() {
    const form = document.getElementById('contactForm');
    const submitButton = form.querySelector('button[type="submit"]');
    
    // フォーム送信の処理
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // バリデーションチェック
        if (!validateForm()) {
            return;
        }
        
        // 送信処理
        submitForm();
    });
    
    // リアルタイムバリデーション
    setupRealTimeValidation();
    
    // フォームフィールドの初期化
    setupFormFields();
}

function validateForm() {
    const form = document.getElementById('contactForm');
    let isValid = true;
    const errors = [];
    
    // 必須フィールドのチェック
    const requiredFields = [
        { id: 'inquiryType', name: 'お問い合わせ種別' },
        { id: 'lastName', name: '姓' },
        { id: 'firstName', name: '名' },
        { id: 'lastNameKana', name: 'セイ' },
        { id: 'firstNameKana', name: 'メイ' },
        { id: 'email', name: 'メールアドレス' },
        { id: 'message', name: 'お問い合わせ内容' },
        { id: 'privacy', name: 'プライバシーポリシーへの同意' }
    ];
    
    requiredFields.forEach(field => {
        const element = document.getElementById(field.id);
        let value = '';
        
        if (element.type === 'checkbox') {
            value = element.checked;
        } else {
            value = element.value.trim();
        }
        
        if (!value) {
            isValid = false;
            errors.push(`${field.name}は必須項目です`);
            highlightError(element);
        } else {
            clearError(element);
        }
    });
    
    // メールアドレスの形式チェック
    const email = document.getElementById('email').value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email && !emailRegex.test(email)) {
        isValid = false;
        errors.push('メールアドレスの形式が正しくありません');
        highlightError(document.getElementById('email'));
    }
    
    // カナ文字のチェック
    const lastNameKana = document.getElementById('lastNameKana').value.trim();
    const firstNameKana = document.getElementById('firstNameKana').value.trim();
    const kanaRegex = /^[ァ-ヶー]+$/;
    
    if (lastNameKana && !kanaRegex.test(lastNameKana)) {
        isValid = false;
        errors.push('セイはカタカナで入力してください');
        highlightError(document.getElementById('lastNameKana'));
    }
    
    if (firstNameKana && !kanaRegex.test(firstNameKana)) {
        isValid = false;
        errors.push('メイはカタカナで入力してください');
        highlightError(document.getElementById('firstNameKana'));
    }
    
    // 電話番号の形式チェック（入力されている場合のみ）
    const phone = document.getElementById('phone').value.trim();
    const phoneRegex = /^[\d\-\+\(\)\s]+$/;
    if (phone && !phoneRegex.test(phone)) {
        isValid = false;
        errors.push('電話番号の形式が正しくありません');
        highlightError(document.getElementById('phone'));
    }
    
    // メッセージの文字数チェック
    const message = document.getElementById('message').value.trim();
    if (message.length > 1000) {
        isValid = false;
        errors.push('お問い合わせ内容は1000文字以内で入力してください');
        highlightError(document.getElementById('message'));
    }
    
    // エラーメッセージの表示
    if (!isValid) {
        showValidationErrors(errors);
        return false;
    }
    
    hideValidationErrors();
    return true;
}

function highlightError(element) {
    element.style.borderColor = '#e74c3c';
    element.classList.add('error');
}

function clearError(element) {
    element.style.borderColor = '#e9ecef';
    element.classList.remove('error');
}

function showValidationErrors(errors) {
    hideValidationErrors();
    
    const errorContainer = document.createElement('div');
    errorContainer.id = 'validation-errors';
    errorContainer.className = 'validation-errors';
    errorContainer.style.cssText = `
        background: #f8d7da;
        color: #721c24;
        padding: 1rem;
        border-radius: 5px;
        margin-bottom: 1rem;
        border: 1px solid #f5c6cb;
    `;
    
    const errorList = document.createElement('ul');
    errorList.style.cssText = `
        margin: 0;
        padding-left: 1.5rem;
    `;
    
    errors.forEach(error => {
        const errorItem = document.createElement('li');
        errorItem.textContent = error;
        errorItem.style.marginBottom = '0.25rem';
        errorList.appendChild(errorItem);
    });
    
    errorContainer.appendChild(errorList);
    
    const form = document.getElementById('contactForm');
    form.insertBefore(errorContainer, form.firstChild);
    
    // エラー位置までスクロール
    errorContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function hideValidationErrors() {
    const existingErrors = document.getElementById('validation-errors');
    if (existingErrors) {
        existingErrors.remove();
    }
}

function setupRealTimeValidation() {
    // メールアドレスのリアルタイムバリデーション
    const emailField = document.getElementById('email');
    emailField.addEventListener('input', function() {
        const email = this.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            this.style.borderColor = '#e74c3c';
        } else if (email) {
            this.style.borderColor = '#28a745';
        } else {
            this.style.borderColor = '#e9ecef';
        }
    });
    
    // カナ文字のリアルタイムバリデーション
    const kanaFields = ['lastNameKana', 'firstNameKana'];
    kanaFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        field.addEventListener('input', function() {
            const value = this.value.trim();
            const kanaRegex = /^[ァ-ヶー]*$/;
            
            if (value && !kanaRegex.test(value)) {
                this.style.borderColor = '#e74c3c';
            } else if (value) {
                this.style.borderColor = '#28a745';
            } else {
                this.style.borderColor = '#e9ecef';
            }
        });
    });
    
    // メッセージの文字数カウンター
    const messageField = document.getElementById('message');
    const counterDiv = document.createElement('div');
    counterDiv.className = 'char-counter';
    counterDiv.style.cssText = `
        text-align: right;
        font-size: 0.9rem;
        color: #666;
        margin-top: 0.25rem;
    `;
    messageField.parentNode.appendChild(counterDiv);
    
    messageField.addEventListener('input', function() {
        const length = this.value.length;
        const maxLength = 1000;
        counterDiv.textContent = `${length}/${maxLength}文字`;
        
        if (length > maxLength) {
            counterDiv.style.color = '#e74c3c';
            this.style.borderColor = '#e74c3c';
        } else if (length > maxLength * 0.8) {
            counterDiv.style.color = '#f39c12';
            this.style.borderColor = '#f39c12';
        } else {
            counterDiv.style.color = '#666';
            this.style.borderColor = '#e9ecef';
        }
    });
    
    // 初期表示
    messageField.dispatchEvent(new Event('input'));
}

function setupFormFields() {
    // 見学希望日の最小日付を今日に設定
    const visitDateField = document.getElementById('visitDate');
    const today = new Date();
    const todayString = today.toISOString().split('T')[0];
    visitDateField.setAttribute('min', todayString);
    
    // 電話番号の自動フォーマット
    const phoneField = document.getElementById('phone');
    phoneField.addEventListener('input', function() {
        let value = this.value.replace(/[^\d]/g, '');
        if (value.length >= 6) {
            if (value.length <= 10) {
                value = value.replace(/(\d{3})(\d{3})(\d{4})/, '$1-$2-$3');
            } else {
                value = value.replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3');
            }
        }
        this.value = value;
    });
    
    // お問い合わせ種別による表示制御
    const inquiryTypeField = document.getElementById('inquiryType');
    const visitDateGroup = document.querySelector('label[for="visitDate"]').parentNode;
    
    inquiryTypeField.addEventListener('change', function() {
        if (this.value === '見学希望' || this.value === '体験参加希望') {
            visitDateGroup.style.display = 'block';
            visitDateGroup.style.opacity = '1';
        } else {
            visitDateGroup.style.display = 'none';
        }
    });
    
    // 初期状態の設定
    inquiryTypeField.dispatchEvent(new Event('change'));
}

function submitForm() {
    const form = document.getElementById('contactForm');
    const submitButton = form.querySelector('button[type="submit"]');
    const loadingIndicator = showLoadingSpinner(submitButton);
    
    // フォームデータの準備
    const formData = new FormData(form);
    
    // 送信日時を追加
    formData.append('submitDate', new Date().toISOString());
    
    // デバッグ用のログ
    console.log('送信先:', form.action);
    console.log('送信データ:', Object.fromEntries(formData));
    
    // Ajax送信
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('レスポンス受信:', response.status, response.statusText);
        
        // レスポンスが正常でない場合でもJSONを試す
        return response.text().then(text => {
            console.log('レスポンス内容:', text);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}\nResponse: ${text}`);
            }
            
            try {
                return JSON.parse(text);
            } catch (e) {
                throw new Error(`JSONパースエラー: ${e.message}\nResponse: ${text}`);
            }
        });
    })
    .then(data => {
        loadingIndicator.hide();
        console.log('パース済みデータ:', data);
        
        if (data.success) {
            // 成功時の処理
            if (data.demo_mode) {
                showSuccessModalDemo(data);
            } else {
                showSuccessModal();
            }
            form.reset();
            
            // 自動保存データを削除
            localStorage.removeItem('contactFormData');
            
            // アナリティクス送信（Google Analyticsなど）
            if (typeof gtag !== 'undefined') {
                gtag('event', 'form_submit', {
                    event_category: 'contact',
                    event_label: formData.get('inquiryType')
                });
            }
        } else {
            // エラーの表示
            showErrorMessage(data.message || '送信に失敗しました。もう一度お試しください。');
        }
    })
    .catch(error => {
        loadingIndicator.hide();
        console.error('送信エラー詳細:', error);
        
        // より詳細なエラーメッセージ
        let errorMessage = '送信に失敗しました。';
        
        if (error.message.includes('Failed to fetch')) {
            errorMessage += '\n・インターネット接続を確認してください\n・サーバーが起動しているか確認してください';
        } else if (error.message.includes('HTTP 404')) {
            errorMessage += '\n・PHPファイルが見つかりません\n・ファイルパスを確認してください';
        } else if (error.message.includes('HTTP 500')) {
            errorMessage += '\n・サーバーエラーが発生しました\n・PHPの設定を確認してください';
        } else {
            errorMessage += `\n詳細: ${error.message}`;
        }
        
        showErrorMessage(errorMessage);
    });
}

function showSuccessModal() {
    const modal = document.getElementById('success-modal');
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        // 3秒後に自動で閉じる
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }, 5000);
    }
}

function showSuccessModalDemo(data) {
    const modal = document.getElementById('success-modal');
    if (modal) {
        // デモ用のメッセージに変更
        const modalBody = modal.querySelector('.modal-body');
        if (modalBody) {
            modalBody.innerHTML = `
                <i class="fas fa-check-circle"></i>
                <h3>送信完了（デモ版）</h3>
                <p>お問い合わせありがとうございます。<br>
                お問い合わせID: ${data.inquiry_id}<br><br>
                <strong>※これはデモ版です※</strong><br>
                実際のメールは送信されません。</p>
            `;
        }
        
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        // 7秒後に自動で閉じる
        setTimeout(() => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }, 7000);
    }
}

function showErrorMessage(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.style.cssText = `
        background: #f8d7da;
        color: #721c24;  
        padding: 1rem;
        border-radius: 5px;
        margin-bottom: 1rem;
        border: 1px solid #f5c6cb;
        position: fixed;
        top: 100px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1000;
        min-width: 300px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    `;
    
    errorDiv.innerHTML = `
        <strong>エラー</strong><br>
        ${message}
        <button onclick="this.parentElement.remove()" style="
            background: none;
            border: none;
            color: #721c24;
            font-size: 1.2rem;
            position: absolute;
            right: 0.5rem;
            top: 0.5rem;
            cursor: pointer;
        ">&times;</button>
    `;
    
    document.body.appendChild(errorDiv);
    
    // 5秒後に自動で削除
    setTimeout(() => {
        if (errorDiv.parentNode) {
            errorDiv.remove();
        }
    }, 5000);
}

// フォームのセキュリティ対策
function sanitizeInput(input) {
    return input.replace(/[<>\"']/g, function(match) {
        const map = {
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        };
        return map[match];
    });
}

// 重複送信防止
let isSubmitting = false;

function preventDoubleSubmit() {
    if (isSubmitting) {
        return false;
    }
    
    isSubmitting = true;
    
    setTimeout(() => {
        isSubmitting = false;
    }, 3000);
    
    return true;
}

// スパム対策（簡易版）
function honeypotCheck() {
    const honeypot = document.createElement('input');
    honeypot.type = 'text';
    honeypot.name = 'website';
    honeypot.style.display = 'none';
    honeypot.tabIndex = -1;
    honeypot.autocomplete = 'off';
    
    const form = document.getElementById('contactForm');
    form.appendChild(honeypot);
    
    return function() {
        return honeypot.value === '';
    };
}

// 初期化時にハニーポットを設定
document.addEventListener('DOMContentLoaded', function() {
    const isHuman = honeypotCheck();
    
    // フォーム送信時にハニーポットをチェック
    const originalSubmitForm = submitForm;
    submitForm = function() {
        if (!isHuman()) {
            console.warn('スパムの可能性があります');
            return;
        }
        
        if (!preventDoubleSubmit()) {
            return;
        }
        
        originalSubmitForm();
    };
});