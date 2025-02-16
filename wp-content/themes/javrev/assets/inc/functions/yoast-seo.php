<?php

// YoastSEOのパンくずをカスタム
add_filter('wpseo_breadcrumb_links', function ($links) {
  global $wp_query;

  $post_type = 'jav';
  $post_type_archive = get_post_type_archive_link($post_type);

  if (isset($wp_query->query_vars['taxonomy']) && !empty($wp_query->query_vars['taxonomy'])) {
    $taxonomy_slug = $wp_query->query_vars['taxonomy'];
    $term_slug = isset($wp_query->query_vars['term']) ? $wp_query->query_vars['term'] : '';

    //error_log("【DEBUG】Taxonomy: {$taxonomy_slug}, Term: {$term_slug}");

    $jav_link = [
      'url' => $post_type_archive,
      'text' => 'JAV',
    ];
    array_splice($links, 1, 0, [$jav_link]);

    if (empty($term_slug)) {
      $taxonomy_link = [
        'url' => home_url("/jav/{$taxonomy_slug}/"),
        'text' => get_taxonomy($taxonomy_slug)->labels->name,
      ];
      array_splice($links, -1, 1, [$taxonomy_link]);
    } else {
      $taxonomy_link = [
        'url' => home_url("/jav/{$taxonomy_slug}/"),
        'text' => get_taxonomy($taxonomy_slug)->labels->name,
      ];
      $term_link = [
        'url' => get_term_link($term_slug, $taxonomy_slug),
        'text' => single_term_title('', false),
      ];

      array_splice($links, -1, 1, [$taxonomy_link, $term_link]);

      // 「play」タクソノミーでは親タームをパンくずから削除
      if ($taxonomy_slug === 'play') {
        $term = get_term_by('slug', $term_slug, $taxonomy_slug);
        if ($term && $term->parent) {
          $parent_term = get_term($term->parent, $taxonomy_slug);
          if ($parent_term) {
            //error_log("【DEBUG】Removing Parent Term: {$parent_term->name} ({$parent_term->term_id})");
            // 親タームの URL に一致する要素を削除
            $links = array_values(array_filter($links, function ($link) use ($parent_term, $taxonomy_slug) {
              return $link['url'] !== get_term_link($parent_term->term_id, $taxonomy_slug);
            }));
          }
        }
      }
    }
  }

  // 追加: 修正後のパンくずを確認
  //error_log("【DEBUG】Fixed Breadcrumbs: " . print_r($links, true));

  return $links; // インデックスをリセットした配列を返す
});

// Yoast SEO の構造化データを最適化
add_filter('wpseo_schema_webpage', function ($data) {
  global $wp_query;

  // `/jav/` の場合（トップのアーカイブ）
  if (is_post_type_archive('jav')) {
    $data['@type'] = 'CollectionPage';
    $data['@id'] = home_url('/jav/');
    $data['url'] = home_url('/jav/');
    $data['name'] = 'JAV Articles - JAVREV'; // "Archive" → "Articles" に変更
  }

  // `/news/` の場合（ニュース記事一覧ページ）
  if (is_post_type_archive('news')) {
    $data['@type'] = 'CollectionPage';
    $data['@id'] = home_url('/news/');
    $data['url'] = home_url('/news/');
    $data['name'] = 'News Articles - JAVREV'; // "Archive" → "Articles" に変更
    $data['breadcrumb'] = [
      '@id' => home_url('/news/#breadcrumb'),
    ];
  }

  // `/jav/カスタムタクソノミー/` の場合
  if (isset($wp_query->query_vars['taxonomy']) && !empty($wp_query->query_vars['taxonomy'])) {
    $taxonomy_slug = $wp_query->query_vars['taxonomy'];

    // `/jav/カスタムタクソノミー/` はタームの一覧を出す記事リストページ
    if (empty($wp_query->query_vars['term'])) {
      $data['@type'] = 'CollectionPage';
      $data['@id'] = home_url("/jav/{$taxonomy_slug}/");
      $data['url'] = home_url("/jav/{$taxonomy_slug}/");
      $data['name'] = get_taxonomy($taxonomy_slug)->labels->name . ' Articles - JAVREV';
      $data['breadcrumb'] = [
        '@id' => home_url("/jav/{$taxonomy_slug}/#breadcrumb"),
      ];
    }
  }

  // `/jav/カスタムタクソノミー/ターム/` の場合
  if (isset($wp_query->query_vars['term']) && !empty($wp_query->query_vars['taxonomy'])) {
    $taxonomy_slug = $wp_query->query_vars['taxonomy'];
    $term_slug = $wp_query->query_vars['term'];
    $term = get_term_by('slug', $term_slug, $taxonomy_slug);

    if ($term) {
      $data['@type'] = 'CollectionPage';
      $data['@id'] = home_url("/jav/{$taxonomy_slug}/{$term_slug}/");
      $data['url'] = home_url("/jav/{$taxonomy_slug}/{$term_slug}/");
      $data['name'] = "{$term->name} Articles - JAVREV";
      $data['breadcrumb'] = [
        '@id' => home_url("/jav/{$taxonomy_slug}/{$term_slug}/#breadcrumb"),
      ];
    }
  }

  return $data;
});








// ✅ `/jav/tags/` の構造化データを `ItemList` に修正
// 📌 あとで！
// add_filter('wpseo_schema_graph_pieces', function ($pieces, $context) {
//   if (is_page('tags')) { // 固定ページ `/jav/tags/`
//     foreach ($pieces as &$piece) {
//       if ($piece['@type'] === 'CollectionPage') {
//         $piece['@type'] = 'ItemList'; // `CollectionPage` → `ItemList` に修正
//         $piece['@id'] = home_url('/jav/tags/');
//         $piece['url'] = home_url('/jav/tags/');
//         $piece['name'] = 'Tags List - JAVREV';
//       }
//     }
//   }
//   return $pieces;
// }, 10, 2);








// スキーマデータにArticleにする
add_filter('wpseo_schema_webpage', function ($data) {
  if (is_singular('jav') || is_singular('news')) {
    $data['@type'] = 'Article'; // または 'NewsArticle'
    $data['headline'] = get_the_title();
    $data['author'] = [
      '@type' => 'Person',
      'name' => get_the_author(),
    ];
    $data['datePublished'] = get_the_date('c');
    $data['dateModified'] = get_the_modified_date('c');
  }
  return $data;
});

?>
