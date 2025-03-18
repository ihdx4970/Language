document.addEventListener('DOMContentLoaded', function() {
    // 语言切换器相关元素
    const languageSwitcher = document.querySelector('.menu-item-language-switcher');
    const languageOptions = document.querySelectorAll('.language-option');
    
    if (!languageSwitcher) return;

    // 获取/设置当前语言
    function getCurrentLanguage() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('lang') || 'en';
    }

    function setCurrentLanguage(lang) {
        localStorage.setItem('preferredLanguage', lang);
    }

    // 显示加载状态
    function showLoading() {
        const loader = document.createElement('div');
        loader.className = 'language-loader';
        languageSwitcher.appendChild(loader);
        loader.style.display = 'block';
    }

    // 隐藏加载状态
    function hideLoading() {
        const loader = languageSwitcher.querySelector('.language-loader');
        if (loader) {
            loader.remove();
        }
    }

    // 切换语言处理函数
    async function handleLanguageChange(lang) {
        if (lang === getCurrentLanguage()) return;

        showLoading();
        setCurrentLanguage(lang);

        // 构建新URL
        const url = new URL(window.location.href);
        url.searchParams.set('lang', lang);

        try {
            // 预加载页面内容
            const response = await fetch(url.toString());
            if (!response.ok) throw new Error('Failed to load page');
            
            // 重定向到新URL
            window.location.href = url.toString();
        } catch (error) {
            console.error('Language switch failed:', error);
            hideLoading();
            alert('Failed to switch language. Please try again.');
        }
    }

    // 为语言选项添加点击事件
    languageOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            const lang = this.getAttribute('data-lang');
            if (lang) {
                handleLanguageChange(lang);
            }
        });
    });

    // 移动端优化
    if (window.innerWidth <= 980) {
        let touchStartY = 0;
        let touchEndY = 0;
        
        // 处理触摸开始
        languageSwitcher.addEventListener('touchstart', function(e) {
            touchStartY = e.touches[0].clientY;
        }, { passive: true });

        // 处理触摸移动
        languageSwitcher.addEventListener('touchmove', function(e) {
            touchEndY = e.touches[0].clientY;
            
            // 下拉时阻止页面滚动
            if (touchEndY > touchStartY) {
                e.preventDefault();
            }
        });

        // 处理点击外部关闭下拉菜单
        document.addEventListener('click', function(e) {
            if (!languageSwitcher.contains(e.target)) {
                languageSwitcher.blur();
            }
        });
    }

    // 自动检测浏览器语言
    function detectBrowserLanguage() {
        const savedLang = localStorage.getItem('preferredLanguage');
        if (savedLang) return;

        const browserLang = navigator.language.split('-')[0];
        const supportedLangs = ['en', 'id', 'fr', 'ar', 'pl', 'de', 'es', 'ru', 'ko'];
        
        if (supportedLangs.includes(browserLang) && browserLang !== getCurrentLanguage()) {
            const shouldSwitch = confirm(`Would you like to switch to ${browserLang.toUpperCase()}?`);
            if (shouldSwitch) {
                handleLanguageChange(browserLang);
            }
        }
    }

    // 页面加载时检测浏览器语言
    // detectBrowserLanguage(); // 取消注释启用自动语言检测

    // 添加动画效果
    languageSwitcher.addEventListener('mouseenter', function() {
        const dropdown = this.querySelector('.language-dropdown');
        dropdown.style.display = 'block';
        setTimeout(() => {
            dropdown.style.opacity = '1';
            dropdown.style.transform = 'translateY(0)';
        }, 10);
    });

    languageSwitcher.addEventListener('mouseleave', function() {
        const dropdown = this.querySelector('.language-dropdown');
        dropdown.style.opacity = '0';
        dropdown.style.transform = 'translateY(10px)';
        setTimeout(() => {
            dropdown.style.display = 'none';
        }, 300);
    });
});