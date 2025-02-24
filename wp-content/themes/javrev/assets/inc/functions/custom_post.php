<?php
/////////////////////////////////////////////////////////
// カスタム投稿タイプ
/////////////////////////////////////////////////////////
function register_custom_post_types()
{
  // 投稿タイプ一覧
  $post_types = array(
    'news' => array(
      'name' => 'ニュース',
      'menu_position' => 5,
      'slug' => 'news'
    ),
    'jav' => array(
      'name' => 'JAV',
      'menu_position' => 6,
      'slug' => 'jav'
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
      'has_archive' => true,
      'rewrite' => array('slug' => $data['slug'], 'with_front' => false),
      'show_in_rest' => true,
    ));
  }

  // タクソノミー一覧（JAV専用）
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
      'label' => $label,
      'public' => true,
      'show_ui' => true,
      'show_in_rest' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => "jav/$slug", 'with_front' => false),
    ));
  }
}

add_action('init', 'register_custom_post_types');

// カスタムタクソノミーのURLを変更する
//=====================================================
function add_custom_rewrite_rules()
{
  $taxonomies = array(
    'censor',   // 検閲・規制
    'format',   // 映像形式
    'playtime', // 再生時間
    'cast',     // 出演
    'value',    // 価格帯 / 商品性質
    'genre',    // 作品ジャンル
    'outfit',   // 衣装・コスチューム
    'girl',     // 女性のタイプ
    'guy',      // 男性のタイプ
    'body',     // 体の特徴
    'rel',      // 関係性
    'scene',    // シチュエーション
    'play'      // プレイ内容
  );

  foreach ($taxonomies as $taxonomy) {
    // タクソノミーのトップページ
    add_rewrite_rule("jav/$taxonomy/?$", "index.php?post_type=jav&taxonomy=$taxonomy", 'top');
    // タームページ
    add_rewrite_rule("jav/$taxonomy/([^/]+)/?$", "index.php?$taxonomy=\$matches[1]&taxonomy=$taxonomy", 'top');
    // ページネーション
    add_rewrite_rule("jav/$taxonomy/([^/]+)/page/([^/]+)/?$", "index.php?$taxonomy=\$matches[1]&taxonomy=$taxonomy&paged=\$matches[2]", 'top');
  }
}
add_action('init', 'add_custom_rewrite_rules');

// カスタムタクソノミーのUIを右から記事下に移動
//=====================================================
function move_jav_taxonomy_meta_boxes()
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
    // 右サイドバーからタクソノミーメタボックスを削除
    remove_meta_box("{$taxonomy}div", 'jav', 'side');

    // 記事本文の下に再配置
    add_meta_box("{$taxonomy}div", esc_html(get_taxonomy($taxonomy)->labels->name), 'post_categories_meta_box', 'jav', 'normal', 'high', array('taxonomy' => $taxonomy));
  }
}
add_action('add_meta_boxes', 'move_jav_taxonomy_meta_boxes');
