<?php
// CSSとJSの読み込み
function my_theme_enqueue_assets() {
  // CSSファイルの読み込み
  wp_enqueue_style(
    'swiper', 
    get_template_directory_uri() . '/assets/lib/swiper@11/swiper-bundle.min.css',
    array(), 
    '11.0.0'
  );

  wp_enqueue_style(
    'main-style',
    get_template_directory_uri() . '/assets/css/style.css',
    array('swiper'), 
    '1.0.0'
  );

  // JSファイルの読み込み
  wp_enqueue_script(
    'jquery-cdn',
    'https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js',
    array(),
    '3.7.1',
    true
  );

  wp_enqueue_script(
    'swiper',
    get_template_directory_uri() . '/assets/lib/swiper@11/swiper-bundle.min.js',
    array(),
    '11.0.0',
    true
  );

  wp_enqueue_script(
    'common-js',
    get_template_directory_uri() . '/assets/js/common.js',
    array('swiper'),
    '1.0.0',
    true
  );

}
add_action('wp_enqueue_scripts', 'my_theme_enqueue_assets');

