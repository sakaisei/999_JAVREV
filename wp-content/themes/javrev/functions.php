<?php
$function_files = [
  'init.php', // テーマの基本設定
  'lang.php', // 翻訳関連
  'load-css-js.php', // CSS & JS の読み込み
  'custom_post.php', // カスタム投稿タイプの設定
  'taxonomy-get.php', // タクソノミー取得
  'taxonomy-url-rewrite.php', // タクソノミーのリライトルール
  'playtime-get.php', // 再生時間取得
  'shortcode.php', // ショートコード (一般)
  'shortcode_cta.php',// CTA 用ショートコード
  'card-loop.php', // カードループ
  'slider-functions.php', // カードのswiper箇所の関数
  'wp_ulike.php', // いいね機能
  //'sync_wpml_slug.php', // WPMLのスラッグを元の言語のものに強制的に統一
  //'yoast-seo.php', // Yoast SEO 設定
  'affiliate_info.php', // アフィリエイト情報
  'flush_rewrite_rules.php' // 最後にリライトルールをフラッシュ
];

foreach ( $function_files as $file ) {
  $file_path = get_template_directory() . '/assets/inc/functions/' . $file;
  if ( file_exists( $file_path ) ) {
    require_once $file_path;
  }
}
