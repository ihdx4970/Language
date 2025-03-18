<?php

  /*
   * 多语言翻译
   */
   
  require_once get_stylesheet_directory() . '/includes/translation-service.php';
  
  // 加载语言切换器的样式和脚本
  function add_language_switcher_assets() {
      wp_enqueue_style('language-switcher', get_stylesheet_directory_uri() . '/css/language-switcher.css', array(), '1.0.0');
      wp_enqueue_script('language-switcher', get_stylesheet_directory_uri() . '/js/language-switcher.js', array('jquery'), '1.0.0', true);
  }
  add_action('wp_enqueue_scripts', 'add_language_switcher_assets');
  
  // 初始化翻译服务
  $translation_service = new AutoTranslationService();
  
  // 添加翻译过滤器
  function auto_translate_content($content) {
      global $translation_service;
      
      $current_lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';
      if ($current_lang === 'en') {
          return $content;
      }
  
      $cache_key = 'translated_content_' . md5($content . $current_lang);
      $translated_content = get_transient($cache_key);
  
      if ($translated_content === false) {
          $translated_content = $translation_service->translate($content, $current_lang);
          set_transient($cache_key, $translated_content, 30 * DAY_IN_SECONDS);
      }
  
      return $translated_content;
  }
  add_filter('the_content', 'auto_translate_content');
  add_filter('the_title', 'auto_translate_content');
  
  // 添加语言切换器到 Divi 主菜单
  function add_language_switcher_to_menu($items, $args) {
      if($args->theme_location == 'primary-menu') { // Divi 主菜单位置
          $current_lang = isset($_GET['lang']) ? $_GET['lang'] : 'en';
          
          // 定义支持的语言
          $supported_languages = array(
              'en' => array('name' => 'English', 'flag' => '/images/flags/en.png'),
              'id' => array('name' => 'Bahasa Indonesia', 'flag' => '/images/flags/id.png'),
              'fr' => array('name' => 'Français', 'flag' => '/images/flags/fr.png'),
              'ar' => array('name' => 'العربية', 'flag' => '/images/flags/ar.png'),
              'pl' => array('name' => 'Polski', 'flag' => '/images/flags/pl.png'),
              'de' => array('name' => 'Deutsch', 'flag' => '/images/flags/de.png'),
              'es' => array('name' => 'Español', 'flag' => '/images/flags/es.png'),
              'ru' => array('name' => 'Русский', 'flag' => '/images/flags/ru.png'),
              'ko' => array('name' => '한국어', 'flag' => '/images/flags/ko.png')
          );
          
          $switcher_html = '<li class="menu-item menu-item-language-switcher">';
          $switcher_html .= '<a href="#" class="language-icon">';
          $switcher_html .= '<img src="' . get_stylesheet_directory_uri() . $supported_languages[$current_lang]['flag'] . '" alt="' . $supported_languages[$current_lang]['name'] . '">';
          $switcher_html .= '</a>';
          
          $switcher_html .= '<ul class="sub-menu language-dropdown">';
          foreach ($supported_languages as $lang_code => $lang) {
              if ($lang_code !== $current_lang) {
                  $switcher_html .= sprintf(
                      '<li><a href="%s" class="language-option" data-lang="%s">
                          <img src="%s" alt="%s" class="language-flag">
                          <span>%s</span>
                      </a></li>',
                      add_query_arg('lang', $lang_code),
                      $lang_code,
                      get_stylesheet_directory_uri() . $lang['flag'],
                      $lang['name'],
                      $lang['name']
                  );
              }
          }
          
          // 添加更多语言选项
          $switcher_html .= '<li class="more-languages">';
          $switcher_html .= '<a href="' . home_url('/languages') . '">More Languages</a>';
          $switcher_html .= '</li>';
          
          $switcher_html .= '</ul></li>';
          
          $items .= $switcher_html;
      }
      return $items;
  }
  add_filter('wp_nav_menu_items', 'add_language_switcher_to_menu', 10, 2);
  
  // 添加语言切换器的样式到 Divi 主题
  function add_language_switcher_divi_styles() {
      echo '<style>
          .menu-item-language-switcher {
              position: relative;
              display: inline-block;
          }
          .menu-item-language-switcher .language-icon {
              padding: 0 !important;
          }
          .menu-item-language-switcher .language-icon img {
              width: 24px;
              height: 24px;
              vertical-align: middle;
          }
          .menu-item-language-switcher .sub-menu {
              display: none;
              position: absolute;
              top: 100%;
              right: 0;
              background: #fff;
              box-shadow: 0 2px 5px rgba(0,0,0,0.1);
              min-width: 200px;
              border-radius: 4px;
              z-index: 999;
          }
          .menu-item-language-switcher:hover .sub-menu {
              display: block;
          }
          .language-option {
              display: flex !important;
              align-items: center;
              padding: 10px 15px !important;
          }
          .language-flag {
              width: 20px;
              height: 15px;
              margin-right: 10px;
          }
          .more-languages {
              border-top: 1px solid #eee;
              margin-top: 5px;
          }
          .more-languages a {
              color: #666 !important;
              font-weight: 500;
          }
      </style>';
  }
  add_action('wp_head', 'add_language_switcher_divi_styles');

?>
