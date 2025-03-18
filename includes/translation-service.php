<?php
    class AutoTranslationService {
        private $apiUrl = 'https://api.mymemory.translated.net/get';
        private $cacheExpiration = 2592000; // 30天缓存
    
        public function translate($text, $targetLang, $sourceLang = 'en') {
            // 检查缓存
            $cacheKey = md5($text . $targetLang . $sourceLang);
            $cachedTranslation = get_transient($cacheKey);
            
            if ($cachedTranslation !== false) {
                return $cachedTranslation;
            }
    
            $url = add_query_arg(array(
                'q' => $text,
                'langpair' => $sourceLang . '|' . $targetLang,
                'de' => 'your@email.com' // 可选：添加您的邮箱以获得更高的每日限额
            ), $this->apiUrl);
    
            $response = wp_remote_get($url);
    
            if (is_wp_error($response)) {
                return $text;
            }
    
            $result = json_decode(wp_remote_retrieve_body($response), true);
            if (isset($result['responseData']['translatedText'])) {
                $translatedText = $result['responseData']['translatedText'];
                // 清理翻译文本中的 HTML 实体
                $translatedText = html_entity_decode($translatedText, ENT_QUOTES, 'UTF-8');
                set_transient($cacheKey, $translatedText, $this->cacheExpiration);
                return $translatedText;
            }
    
            return $text;
        }
    }
?>