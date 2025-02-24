<?php
/////////////////////////////////////////////////////////
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
// タクソノミーの登録
/////////////////////////////////////////////////////////
function register_custom_taxonomies()
{
  $taxonomies = array(
    'censor'   => '検閲・規制',
    'format'   => '映像形式',
    'playtime' => '再生時間',
    'cast'     => '出演',
    'value'    => '価格帯 / 商品性質',
    'genre'    => '作品ジャンル',
    'outfit'   => '衣装・コスチューム',
    'girl'     => '女性のタイプ',
    'guy'      => '男性のタイプ',
    'body'     => '体の特徴',
    'rel'      => '関係性',
    'scene'    => 'シチュエーション',
    'play'     => 'プレイ内容'
  );

  foreach ($taxonomies as $slug => $label) {
    register_taxonomy($slug, 'jav', array(
      'hierarchical' => true,
      'labels' => array(
        'name'          => $label,
        'singular_name' => $label,
        'search_items'  => "{$label}を検索",
        'all_items'     => "すべての{$label}",
        'edit_item'     => "{$label}を編集",
        'update_item'   => "{$label}を更新",
        'add_new_item'  => "{$label}を追加",
        'new_item_name' => "新しい{$label}の名前",
        'menu_name'     => $label
      ),
      'public'       => true,
      'show_ui'      => true,
      'show_admin_column' => true, // 管理画面のカラムに追加
      'show_in_rest' => true,
      'has_archive'  => true,
      'rewrite'      => array('slug' => "jav/$slug", 'with_front' => false),
    ));
  }
}
add_action('init', 'register_custom_taxonomies');

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
// タクソノミーのURLリライトルールを追加
/////////////////////////////////////////////////////////
function add_custom_taxonomy_rewrite_rules()
{
  $taxonomies = array(
    'censor',
    'format',
    'playtime',
    'cast',
    'value',
    'genre',
    'outfit',
    'girl',
    'guy',
    'body',
    'rel',
    'scene',
    'play'
  );

  foreach ($taxonomies as $taxonomy) {
    add_rewrite_rule("jav/{$taxonomy}/?$", "index.php?post_type=jav&taxonomy={$taxonomy}", 'top');
    add_rewrite_rule("jav/{$taxonomy}/([^/]+)/?$", "index.php?{$taxonomy}=\$matches[1]", 'top');
    add_rewrite_rule("jav/{$taxonomy}/([^/]+)/page/([^/]+)/?$", "index.php?{$taxonomy}=\$matches[1]&paged=\$matches[2]", 'top');
  }
}
add_action('init', 'add_custom_taxonomy_rewrite_rules');

/////////////////////////////////////////////////////////
// `pre_get_posts` で `paged` を確実に適用
/////////////////////////////////////////////////////////
add_action('pre_get_posts', function ($query) {
  if (!is_admin() && $query->is_main_query()) {

    // `jav` のカスタムタクソノミー一覧
    $jav_taxonomies = [
      'censor',
      'format',
      'playtime',
      'cast',
      'value',
      'genre',
      'outfit',
      'girl',
      'guy',
      'body',
      'rel',
      'scene',
      'play'
    ];

    // すべての `jav` に関連するタクソノミーを適用
    if (is_post_type_archive('jav') || is_tax($jav_taxonomies)) {
      $paged = max(1, (int) get_query_var('paged', 1));

      $query->set('post_type', 'jav');
      $query->set('posts_per_page', 2);
      $query->set('paged', $paged);
      $query->set('orderby', 'date');
      $query->set('order', 'DESC');

      // デバッグログ出力
      //error_log("【DEBUG】pre_get_posts: `paged` を適用 → " . $paged);
      //error_log("【DEBUG】対象のタクソノミー → " . get_queried_object()->taxonomy);
    }
  }
});



/////////////////////////////////////////////////////////
// `rewrite_rules` のフラッシュ (テーマ変更時のみ)
/////////////////////////////////////////////////////////
// function custom_flush_rewrite_rules()
// {
//   flush_rewrite_rules();
// }
// add_action('after_switch_theme', 'custom_flush_rewrite_rules');

// // 一時的なデバッグ用（確認後に削除）
// add_action('init', 'custom_flush_rewrite_rules');

/////////////////////////////////////////////////////////
// `is_post_type_archive('jav')` のデバッグログ出力
/////////////////////////////////////////////////////////
// add_action('template_redirect', function () {
//   if (is_post_type_archive('jav')) {
//     //error_log("【DEBUG】is_post_type_archive('jav') は TRUE");
//   } else {
//     //error_log("【DEBUG】is_post_type_archive('jav') は FALSE");
//   }
// });

/////////////////////////////////////////////////////////
// `wp_rewrite_rules` のデバッグ出力
/////////////////////////////////////////////////////////
// add_action('init', function () {
//   global $wp_rewrite;
//   //error_log(print_r($wp_rewrite->wp_rewrite_rules(), true));
// });

/////////////////////////////////////////////////////////
// 一度だけ `flush_rewrite_rules()` を実行
/////////////////////////////////////////////////////////
// add_action('init', function () {
//   if (get_option('my_custom_rewrite_flush') !== '1') {
//     flush_rewrite_rules();
//     update_option('my_custom_rewrite_flush', '1');
//   }
// });

