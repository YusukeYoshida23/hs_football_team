// ハンバーガーメニューの制御
document.addEventListener('DOMContentLoaded', function() {
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            hamburger.classList.toggle('active');
        });
        
        // メニューアイテムをクリックしたときにメニューを閉じる
        const navItems = document.querySelectorAll('.nav-menu a');
        navItems.forEach(item => {
            item.addEventListener('click', () => {
                navMenu.classList.remove('active');
                hamburger.classList.remove('active');
            });
        });
        
        // メニュー外をクリックしたときにメニューを閉じる
        document.addEventListener('click', function(e) {
            if (!hamburger.contains(e.target) && !navMenu.contains(e.target)) {
                navMenu.classList.remove('active');
                hamburger.classList.remove('active');
            }
        });
    }
});

// スムーススクロール
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                const headerHeight = document.querySelector('header').offsetHeight;
                const targetPosition = targetElement.offsetTop - headerHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
});

// スクロール時のナビゲーション背景変更
window.addEventListener('scroll', function() {
    const header = document.querySelector('header');
    if (window.scrollY > 50) {
        header.style.backgroundColor = 'rgba(30, 60, 114, 0.95)';
    } else {
        header.style.backgroundColor = 'rgba(30, 60, 114, 1)';
    }
});

// フェードインアニメーション
function fadeInOnScroll() {
    const elements = document.querySelectorAll('.fade-in-up');
    const windowHeight = window.innerHeight;
    
    elements.forEach(element => {
        const elementTop = element.getBoundingClientRect().top;
        const elementVisible = 150;
        
        if (elementTop < windowHeight - elementVisible) {
            element.classList.add('fade-in-up');
        }
    });
}

window.addEventListener('scroll', fadeInOnScroll);

// カウントアップアニメーション（統計情報用）
function animateCounters() {
    const counters = document.querySelectorAll('.stat-item h3');
    const speed = 200;
    
    counters.forEach(counter => {
        const animate = () => {
            const value = +counter.getAttribute('data-target') || +counter.innerText;
            const data = +counter.innerText;
            const time = value / speed;
            
            if (data < value) {
                counter.innerText = Math.ceil(data + time);
                setTimeout(animate, 1);
            } else {
                counter.innerText = value;
            }
        };
        
        // data-target属性を設定
        if (!counter.getAttribute('data-target')) {
            counter.setAttribute('data-target', counter.innerText);
        }
        
        animate();
    });
}

// ページ読み込み時にカウンターアニメーションを実行
window.addEventListener('load', function() {
    setTimeout(animateCounters, 500);
});

// ローディングアニメーション
function showLoadingSpinner(button) {
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> 送信中...';
    button.disabled = true;
    
    return {
        hide: function() {
            button.innerHTML = originalText;
            button.disabled = false;
        }
    };
}

// モーダル制御
function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }
}

function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// モーダルの閉じるボタンとバックグラウンドクリック
document.addEventListener('DOMContentLoaded', function() {
    const modals = document.querySelectorAll('.modal');
    
    modals.forEach(modal => {
        const closeBtn = modal.querySelector('.close');
        
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                hideModal(modal.id);
            });
        }
        
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                hideModal(modal.id);
            }
        });
    });
    
    // ESCキーでモーダルを閉じる
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            modals.forEach(modal => {
                if (modal.style.display === 'block') {
                    hideModal(modal.id);
                }
            });
        }
    });
});

// 入力フィールドの動的バリデーション
function setupFieldValidation() {
    const emailField = document.getElementById('email');
    const phoneField = document.getElementById('phone');
    const requiredFields = document.querySelectorAll('input[required], select[required], textarea[required]');
    
    // メールアドレスの形式チェック
    if (emailField) {
        emailField.addEventListener('blur', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.style.borderColor = '#e74c3c';
                showFieldError(this, 'メールアドレスの形式が正しくありません');
            } else {
                this.style.borderColor = '#e9ecef';
                hideFieldError(this);
            }
        });
    }
    
    // 電話番号の形式チェック
    if (phoneField) {
        phoneField.addEventListener('blur', function() {
            const phone = this.value;
            const phoneRegex = /^[\d\-\+\(\)\s]+$/;
            
            if (phone && !phoneRegex.test(phone)) {
                this.style.borderColor = '#e74c3c';
                showFieldError(this, '電話番号の形式が正しくありません');
            } else {
                this.style.borderColor = '#e9ecef';
                hideFieldError(this);
            }
        });
    }
    
    // 必須フィールドのチェック
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.style.borderColor = '#e74c3c';
                showFieldError(this, 'この項目は必須です');
            } else {
                this.style.borderColor = '#e9ecef';
                hideFieldError(this);
            }
        });
        
        field.addEventListener('input', function() {
            if (this.value.trim()) {
                this.style.borderColor = '#28a745';
                hideFieldError(this);
            }
        });
    });
}

function showFieldError(field, message) {
    hideFieldError(field);
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.style.color = '#e74c3c';
    errorDiv.style.fontSize = '0.9em';
    errorDiv.style.marginTop = '0.25rem';
    errorDiv.textContent = message;
    
    field.parentNode.appendChild(errorDiv);
}

function hideFieldError(field) {
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

// フォームの初期化
document.addEventListener('DOMContentLoaded', function() {
    setupFieldValidation();
});

// フォームデータの自動保存（ローカルストレージ使用）
function setupAutoSave() {
    const form = document.getElementById('contactForm');
    if (!form) return;
    
    const formFields = form.querySelectorAll('input, select, textarea');
    const storageKey = 'contactFormData';
    
    // 保存されたデータを復元
    function restoreFormData() {
        const savedData = localStorage.getItem(storageKey);
        if (savedData) {
            try {
                const data = JSON.parse(savedData);
                Object.keys(data).forEach(key => {
                    const field = form.querySelector(`[name="${key}"]`);
                    if (field) {
                        if (field.type === 'radio' || field.type === 'checkbox') {
                            if (field.value === data[key]) {
                                field.checked = true;
                            }
                        } else {
                            field.value = data[key];
                        }
                    }
                });
            } catch (e) {
                console.error('フォームデータの復元に失敗しました:', e);
            }
        }
    }
    
    // フォームデータを保存
    function saveFormData() {
        const formData = new FormData(form);
        const data = {};
        
        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }
        
        localStorage.setItem(storageKey, JSON.stringify(data));
    }
    
    // 各フィールドの変更時に自動保存
    formFields.forEach(field => {
        field.addEventListener('input', saveFormData);
        field.addEventListener('change', saveFormData);
    });
    
    // 送信成功時にデータを削除
    form.addEventListener('submit', function() {
        setTimeout(() => {
            localStorage.removeItem(storageKey);
        }, 1000);
    });
    
    // ページ読み込み時にデータを復元
    restoreFormData();
}

// ページ読み込み時に自動保存機能を初期化
document.addEventListener('DOMContentLoaded', function() {
    setupAutoSave();
});

// ユーティリティ関数
const utils = {
    // 日付フォーマット
    formatDate: function(date) {
        const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
        return new Date(date).toLocaleDateString('ja-JP', options);
    },
    
    // 電話番号フォーマット
    formatPhone: function(phone) {
        return phone.replace(/[^\d]/g, '').replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3');
    },
    
    // 文字数制限チェック
    checkTextLength: function(text, maxLength) {
        return text.length <= maxLength;
    },
    
    // XSS対策のためのHTML エスケープ
    escapeHtml: function(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
};

// グローバルエラーハンドラー
window.addEventListener('error', function(e) {
    console.error('JavaScript エラーが発生しました:', e.error);
    // 本番環境では適切なエラーログサービスに送信
});

// パフォーマンス最適化：遅延読み込み
function setupLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    } else {
        // フォールバック：IntersectionObserver非対応の場合
        images.forEach(img => {
            img.src = img.dataset.src;
        });
    }
}

document.addEventListener('DOMContentLoaded', setupLazyLoading);