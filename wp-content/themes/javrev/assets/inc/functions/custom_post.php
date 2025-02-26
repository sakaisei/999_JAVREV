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
// カスタムタクソノミーのリストを取得
/////////////////////////////////////////////////////////
function get_custom_taxonomies()
{
  return array(
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
}

/////////////////////////////////////////////////////////
// タクソノミーの登録
/////////////////////////////////////////////////////////
function register_custom_taxonomies()
{
  $taxonomies = get_custom_taxonomies();

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
      'rewrite'      => array('slug' => "$slug", 'with_front' => false),
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
// カスタム投稿タイプ用リライトルールを追加
/////////////////////////////////////////////////////////
function custom_rewrite_rules()
{
  add_rewrite_rule('^jav/page/([0-9]+)/?$', 'index.php?post_type=jav&paged=$matches[1]', 'top');
}
add_action('init', 'custom_rewrite_rules');

/////////////////////////////////////////////////////////
// タクソノミーのURLリライトルールを追加
/////////////////////////////////////////////////////////
// [A] まずはタクソノミーのリライトルール
function add_custom_taxonomy_rewrite_rules()
{
  $taxonomies = array_keys(get_custom_taxonomies());

  foreach ($taxonomies as $taxonomy) {
    add_rewrite_rule("^{$taxonomy}/?$", "index.php?taxonomy={$taxonomy}&post_type=jav", 'top');
    add_rewrite_rule("^{$taxonomy}/([^/]+)/?$", "index.php?taxonomy={$taxonomy}&term=\$matches[1]&post_type=jav", 'top');
    add_rewrite_rule("^{$taxonomy}/([^/]+)/page/([0-9]+)/?$", "index.php?taxonomy={$taxonomy}&term=\$matches[1]&paged=\$matches[2]&post_type=jav", 'top');
  }
}
add_action('init', 'add_custom_taxonomy_rewrite_rules');

// [B] タクソノミーアーカイブとして認識させる
// function fix_taxonomy_root_query($query)
// {
//   if ($query->is_main_query() && !is_admin()) {
//     $taxonomies = array_keys(get_custom_taxonomies());

//     // すべてのタクソノミーを対象に `is_tax()` を適用
//     foreach ($taxonomies as $taxonomy) {
//       if (get_query_var('taxonomy') == $taxonomy) {
//         $query->is_tax = true;
//         $query->set('post_type', ''); // カスタム投稿アーカイブとして扱わないようにする
//       }
//     }
//   }
// }
// add_action('pre_get_posts', 'fix_taxonomy_root_query');

// function fix_taxonomy_root_query($query)
// {
//   if ($query->is_main_query() && !is_admin()) {
//     $taxonomies = array_keys(get_custom_taxonomies());

//     foreach ($taxonomies as $taxonomy) {
//       if (get_query_var('taxonomy') == $taxonomy) {
//         //error_log("✅ DEBUG: Fixing taxonomy root query for `$taxonomy`");

//         // タクソノミーアーカイブとして正しく認識させる
//         $query->is_tax = true;
//         $query->is_post_type_archive = false; // 投稿アーカイブの誤認識を防ぐ

//         // `post_type` をクリアするのをやめる
//         // $query->set('post_type', ''); // コメントアウト
//       }
//     }
//   }
// }

// [C] AとBだけだと、ポストタイプのアーカイブとしても認識してるから、それをfalseにする
// function fix_post_type_archive_flag($query)
// {
//   if ($query->is_main_query() && !is_admin() && is_tax()) {
//     $query->is_post_type_archive = false;
//   }
// }
// add_action('pre_get_posts', 'fix_post_type_archive_flag');





/////////////////////////////////////////////////////////
// `pre_get_posts` で `paged` を適用
/////////////////////////////////////////////////////////
add_action('pre_get_posts', function ($query) {
  if (!is_admin() && $query->is_main_query()) {

    // `jav` の投稿タイプアーカイブ
    if ($query->is_post_type_archive('jav')) {
      $paged = max(1, (int) get_query_var('paged', 1));

      $query->set('post_type', 'jav');
      $query->set('posts_per_page', 2);
      $query->set('paged', $paged);
      $query->set('orderby', 'date');
      $query->set('order', 'DESC');

      // デバッグログ
      //error_log("【DEBUG】投稿タイプアーカイブ: JAV");
    }

    // `jav` に関連するカスタムタクソノミーのアーカイブ
    elseif ($query->is_tax()) {
      $paged = max(1, (int) get_query_var('paged', 1));

      $query->set('posts_per_page', 2);
      $query->set('paged', $paged);
      $query->set('orderby', 'date');
      $query->set('order', 'DESC');

      // デバッグログ
      //error_log("【DEBUG】タクソノミーアーカイブ: " . get_queried_object()->taxonomy);
    }
  }
});

