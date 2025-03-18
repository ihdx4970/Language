<?php
/*
Template Name: Languages
*/

get_header();

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
?>

<div id="main-content">
    <div class="container">
        <div id="content-area" class="clearfix">
            <div class="languages-grid">
                <?php foreach ($supported_languages as $lang_code => $lang) : ?>
                    <a href="<?php echo add_query_arg('lang', $lang_code, home_url()); ?>" class="language-card">
                        <img src="<?php echo get_stylesheet_directory_uri() . $lang['flag']; ?>" alt="<?php echo $lang['name']; ?>" class="language-flag">
                        <h3><?php echo $lang['name']; ?></h3>
                        <p><?php echo $lang_code; ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<style>
.languages-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    padding: 40px 0;
}
.language-card {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    text-decoration: none;
    color: #333;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}
.language-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.language-card img {
    width: 40px;
    height: 30px;
    object-fit: cover;
    margin-bottom: 12px;
}
.language-card h3 {
    margin: 0 0 8px;
    font-size: 16px;
}
.language-card p {
    margin: 0;
    color: #666;
    font-size: 14px;
}
</style>

<?php get_footer(); ?>