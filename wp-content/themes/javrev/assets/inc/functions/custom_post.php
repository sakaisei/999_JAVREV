<?php
///////////////////////////////////////////////////////1//
// カスタム投稿タイプ
/////////////////////////////////////////////////////////
function register_custom_post_types()
{
  $post_types = array(
    'news' => array(
      'name' => 'ニュース',
      'menu_position' => 5,
      'slug' => 'news',
      'has_archive' => true // 明示的にアーカイブを有効化
    ),
    'jav' => array(
      'name' => 'JAV',
      'menu_position' => 6,
      'slug' => 'jav',
      'has_archive' => true // 明示的にアーカイブを有効化
    )
  );

  foreach ($post_types as $post_type => $data) {
    register_post_type($post_type, array(
      'labels' => array(
        'name' => $data['name'],
        'all_items' => '投稿一覧'
      ),
      'public' => true,
      'menu_position' => $data['menu_position'],
      'menu_icon' => 'dashicons-admin-post',
      'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
      'has_archive' => $data['has_archive'], // ここで明示的に適用
      'rewrite' => array('slug' => $data['slug'], 'with_front' => false),
      'show_in_rest' => true,
    ));
  }
}
add_action('init', 'register_custom_post_types');

/////////////////////////////////////////////////////////
// クエリ変数に `paged` を追加
/////////////////////////////////////////////////////////
function add_custom_query_vars($vars)
{
  $vars[] = "paged";
  return $vars;
}
add_filter('query_vars', 'add_custom_query_vars');

/////////////////////////////////////////////////////////
// ページネーション用のリライトルールを追加
/////////////////////////////////////////////////////////
function custom_rewrite_rules()
{
  add_rewrite_rule('^jav/page/([0-9]+)/?$', 'index.php?post_type=jav&paged=$matches[1]', 'top');
}
add_action('init', 'custom_rewrite_rules');

/////////////////////////////////////////////////////////
// `pre_get_posts` で `paged` を確実に適用
/////////////////////////////////////////////////////////
function fix_pagination_for_jav($query)
{
  if (!is_admin() && $query->is_main_query()) {
    if (is_post_type_archive('jav') || $query->get('post_type') === 'jav') {
      $paged = max(1, get_query_var('paged', 1));
      $query->set('post_type', 'jav');
      $query->set('posts_per_page', 3);
      $query->set('paged', $paged);
      error_log("【DEBUG】pre_get_posts: `paged` を適用 → " . $paged);
      error_log("【DEBUG】is_post_type_archive('jav') → " . (is_post_type_archive('jav') ? 'TRUE' : 'FALSE'));
    }
  }
}
add_action('pre_get_posts', 'fix_pagination_for_jav');


/////////////////////////////////////////////////////////
// `rewrite_rules` のフラッシュ (テーマ変更時のみ)
/////////////////////////////////////////////////////////
function custom_flush_rewrite_rules()
{
  flush_rewrite_rules();
}
add_action('after_switch_theme', 'custom_flush_rewrite_rules');

// 一時的なデバッグ用（確認後に削除）
add_action('init', 'custom_flush_rewrite_rules');

/////////////////////////////////////////////////////////
// `is_post_type_archive('jav')` のデバッグログ出力
/////////////////////////////////////////////////////////
add_action('template_redirect', function () {
  if (is_post_type_archive('jav')) {
    error_log("【DEBUG】is_post_type_archive('jav') は TRUE");
  } else {
    error_log("【DEBUG】is_post_type_archive('jav') は FALSE");
  }
});


add_action('init', function () {
  global $wp_rewrite;
  error_log(print_r($wp_rewrite->wp_rewrite_rules(), true));
});

add_action('init', function () {
  if (get_option('my_custom_rewrite_flush') !== '1') {
    flush_rewrite_rules();
    update_option('my_custom_rewrite_flush', '1');
  }
});


