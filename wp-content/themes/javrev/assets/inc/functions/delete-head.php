<?php

// wpのバージョンを削除
remove_action('wp_head', 'wp_generator');

// wpmlのバージョンを強制削除
add_action('init', function() {
  ob_start(function($buffer) {
    return preg_replace('/<meta name="generator"[^>]+>/i', '', $buffer);
  });
});

// XML-RPC 自体を無効化
remove_action( 'wp_head', 'rsd_link' );
add_filter( 'xmlrpc_enabled', '__return_false' );

// wpのブロックエディター関連のcssを削除
add_action( 'wp_enqueue_scripts', function() {
  wp_dequeue_style( 'global-styles' ); //　グーテンベルク
  wp_dequeue_style( 'classic-theme-styles' ); // クラシックテーマスタイル
}, 20 );

// WPML関連
// add_action('wp_print_styles', function() {
//   wp_dequeue_style('wpml-legacy-post-translations-0-css'); //クラシック言語スイッチャーを削除
//   wp_dequeue_style( 'wp-block-library' ); // Gutenberg スタイル
// }, 100);
// ※効かない

// add_action('wp_enqueue_scripts', function() {
//   global $wp_styles;
//   foreach ($wp_styles->registered as $handle => $style) {
//       error_log("Enqueued CSS: " . $handle . " -> " . $style->src);
//   }
// }, 100);
// ※効かない

// RSSフィールドの削除
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'feed_links', 2);

//　jquery-migrate.min.js だけ削除
add_action( 'wp_default_scripts', function( $scripts ) {
  if ( !is_admin() && isset( $scripts->registered['jquery'] ) ) {
    $scripts->registered['jquery']->deps = array_diff( $scripts->registered['jquery']->deps, ['jquery-migrate'] );
  }
} );
