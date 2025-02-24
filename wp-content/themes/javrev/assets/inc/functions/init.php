<?php
/*
テーマのための関数
*/

/////////////////////////////////////////////////////////
// 基本設定
/////////////////////////////////////////////////////////

// wordpressの自動更新停止
define( 'WP_AUTO_UPDATE_CORE', false );

// プラグインの自動更新停止
add_filter( 'auto_update_plugin', '__return_false' );

// WordPressのバージョンを非表示
//remove_action('wp_head','wp_generator');

// 絵文字削除
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles' );
remove_action('admin_print_styles', 'print_emoji_styles');

// 投稿ページにてアイキャッチ画像の欄を表示
// add_theme_support( 'post-thumbnails' );

// WordPressコアから出力されるHTMLタグをHTML5のフォーマットにする
add_theme_support( 'html5', array(
  'search-form',
  'comment-form',
  'comment-list',
  'gallery',
  'caption',
));

// 【管理画面】投稿編集画面で不要な項目を非表示にする
function my_remove_meta_boxes() {
  remove_meta_box('postexcerpt','post','normal' );      // 抜粋
  //remove_meta_box('trackbacksdiv','post','normal' );    // トラックバック
  //remove_meta_box('slugdiv','post','normal');           // スラッグ
  //remove_meta_box('postcustom','post','normal' );       // カスタムフィールド
  remove_meta_box('commentsdiv','post','normal' );      // コメント
  //remove_meta_box('submitdiv','post','normal' );        // 公開
  //remove_meta_box('categorydiv','post','normal');       // カテゴリー
  //remove_meta_box('tagsdiv-post_tag','post','normal' ); // タグ
  //remove_meta_box('commentstatusdiv','post','normal' ); // ディスカッション
  //remove_meta_box('authordiv','post','normal' );        // 作成者
  //remove_meta_box('revisionsdiv','post','normal' );     // リビジョン
  //remove_meta_box('formatdiv','post','normal' );        // フォーマット
  remove_meta_box('pageparentdiv','post','normal' );    // 属性

  // 固定ページ
  //remove_post_type_support('page','editor'); //本文
  //remove_post_type_support( 'page', 'thumbnail' ); //アイキャッチ

  // カスタム投稿
  //remove_post_type_support('movie','editor'); //本文
  //remove_meta_box('postexcerpt','movie','normal' );
  remove_meta_box('commentstatusdiv','news','normal' ); // ディスカッション
}
add_action('admin_menu','my_remove_meta_boxes' );

// 固定ページのビジュアルエディター禁止
// function disable_visual_editor_in_page(){
//   global $typenow;
//   if( $typenow == 'page' ){
//     add_filter('user_can_richedit', 'disable_visual_editor_filter');
//   }
// }
// function disable_visual_editor_filter(){
//   return false;
// }
// add_action( 'load-post.php', 'disable_visual_editor_in_page' );
// add_action( 'load-post-new.php', 'disable_visual_editor_in_page' );


// 【管理画面】左サイドバーで不要な項目を非表示にする
function remove_menus() {
//remove_menu_page('index.php');//ダッシュボード
//remove_submenu_page('index.php','index.php');//ダッシュボード>ホーム
//remove_submenu_page('index.php','update-core.php');//ダッシュボード>更新
//remove_menu_page('separator1');//セパレータ1
remove_menu_page('edit.php');//投稿
//remove_submenu_page('edit.php','edit.php');//投稿>投稿一覧
//remove_submenu_page('edit.php','post-new.php');//投稿>新規投稿
//remove_submenu_page('edit.php','edit-tags.php?taxonomy=category');//投稿>カテゴリー
//remove_submenu_page('edit.php','edit-tags.php?taxonomy=post_tag');//投稿>タグ
//remove_menu_page('upload.php');//メディア
//remove_submenu_page('upload.php', 'upload.php');//メディア>ライブラリ
//remove_submenu_page('upload.php', 'media-new.php');//メディア>新規追加
//remove_menu_page('edit.php?post_type=page');//固定ページ
//remove_submenu_page('edit.php?post_type=page', 'edit.php?post_type=page');//固定ページ>固定ページ一覧
//remove_submenu_page('edit.php?post_type=page', 'post-new.php?post_type=page');//固定ページ>新規追加
//remove_menu_page('edit-comments.php');//コメント
//remove_menu_page('separator2');//セパレータ2
//remove_menu_page('themes.php');//外観
//remove_submenu_page('themes.php', 'themes.php');//外観>テーマ
//remove_submenu_page('themes.php', 'customize.php?return=%2Finformation%2Fwp-admin%2Fthemes.php');//外観>カスタマイズ
//remove_submenu_page('themes.php', 'customize.php?return='. urlencode($_SERVER["REQUEST_URI"])); // 外観->カスタマイズ
//remove_submenu_page('themes.php', 'widgets.php');//外観>ウィジェット
//remove_submenu_page('themes.php', 'nav-menus.php');//外観>メニュー
//remove_submenu_page('themes.php', 'theme-editor.php');//外観>テーマの編集
//remove_menu_page('plugins.php');//プラグイン
//remove_submenu_page('plugins.php', 'plugins.php');//プラグイン>インストール済プラグイン
//remove_submenu_page('plugins.php', 'plugin-install.php');//プラグイン>新規追加
//remove_submenu_page('plugins.php', 'plugin-editor.php');//プラグイン>プラグイン編集
//remove_menu_page('users.php');//ユーザー
//remove_submenu_page('users.php', 'users.php');//ユーザー>ユーザー一覧
//remove_submenu_page('users.php', 'user-new.php');//ユーザー>新規追加
//remove_submenu_page('users.php', 'profile.php');//ユーザー>あなたのプロフィール
//remove_menu_page('tools.php');//ツール
//remove_submenu_page('tools.php', 'tools.php');//ツール>利用可能なツール
//remove_submenu_page('tools.php', 'import.php');//ツール>インポート
//remove_submenu_page('tools.php', 'export.php');//ツール>エクスポート
//remove_menu_page('options-general.php');//設定
//remove_submenu_page('options-general.php', 'options-general.php');//設定>一般
//remove_submenu_page('options-general.php', 'options-writing.php');//設定>投稿設定
//remove_submenu_page('options-general.php', 'options-reading.php');//設定>表示設定
//remove_submenu_page('options-general.php', 'options-discussion.php');//設定>ディスカッション
//remove_submenu_page('options-general.php', 'options-media.php');//設定>メディア
//remove_submenu_page('options-general.php', 'options-permalink.php');//設定>パーマリンク設定
}
// 管理画面メニューの変更を行うフックを設定
add_action('admin_menu', 'remove_menus');

// body_classにスラッグ追加
add_filter( 'body_class', 'add_page_slug_class_name' );
function add_page_slug_class_name( $classes ) {
  if ( is_page() ) {
    $page = get_post( get_the_ID() );
    $classes[] = 'bodyclass__'.$page->post_name;
  }
  return $classes;
}

// アイキャッチサムネイル
add_theme_support( 'post-thumbnails' );
//add_image_size( 'st_thumb100', 100, 100, true );
//add_image_size( 'st_thumb16_9_1x', 600, 390, true );
//add_image_size( 'st_thumb16_9_2x', 1200, 780, true );
//add_image_size( 'st_thumbSilverSmall', 320, 198, true );
//add_image_size( 'st_thumbSilver', 640, 396, true );
//add_image_size( 'st_thumb1440', 1440, 1020, true );

// 自動リダイレクト無効
// function disable_redirect_canonical( $redirect_url ) {
//   if( is_404() ) {
//     return false;
//   }
//   return $redirect_url;
// }
// add_filter( 'redirect_canonical', 'disable_redirect_canonical' );

// wpのプリセットcssを消す
// add_action( 'wp_enqueue_scripts', 'remove_my_global_styles' );
// function remove_my_global_styles() {
//   wp_dequeue_style( 'global-styles' );
// }

// wpのブロックエディターcssを削除
// function dequeue_plugins_style() {
//   wp_dequeue_style('wp-block-library');
// }
// add_action( 'wp_enqueue_scripts', 'dequeue_plugins_style', 9999);

// wpのクラシックエディターcssを削除
// add_action( 'wp_enqueue_scripts', 'remove_classic_theme_style' );
// function remove_classic_theme_style() {
//   wp_dequeue_style( 'classic-theme-styles' );
// }

// 自動成形の無効化
// add_action('init', function() {
//   // タイトルの自動整形を無効化
//   remove_filter('the_title', 'wpautop');  

//   //本文エリアの自動整形を無効化
//   remove_filter('the_content', 'wpautop'); 

//   //コメント欄の自動整形を無効化
//   remove_filter('comment_text', 'wpautop'); 

//   //抜粋欄の自動整形を無効化
//   remove_filter('the_excerpt', 'wpautop'); 
// });

// 固定ページ勝手にpタグを付けない
// add_filter('the_content', 'wpautop_filter', 9);
// function wpautop_filter($content) {
//   global $post;
//   $remove_filter = false;
//   $arr_types = array('page'); //自動整形を無効にする投稿タイプを記述 ＝固定ページ
//   $post_type = get_post_type( $post->ID );
//   if (in_array($post_type, $arr_types)){
//                 $remove_filter = true;
//         }
//   if ( $remove_filter ) {
//     remove_filter('the_content', 'wpautop');
//     remove_filter('the_excerpt', 'wpautop');
//   }
//   return $content;
// }

// 記事スラッグをIDにする
// function auto_slug_setting( $slug, $post_ID, $post_status, $post_type ) {
//   // 記事IDからを記事情報を取得
//   $post = get_post($post_ID);
//   // 初回の記事保存時にのみスラッグを記事IDに設定
//   if ( $post_type && $post->post_date_gmt == '0000-00-00 00:00:00' ) {
//     $slug = $post_ID;
//   }
//   return $slug;
// }
// add_filter( 'wp_unique_post_slug', 'auto_slug_setting', 10, 4 );
