<?php

add_filter('wpseo_breadcrumb_links', function ($links) {
  global $wp_query;

  if (isset($wp_query->query_vars['taxonomy']) && !empty($wp_query->query_vars['taxonomy'])) {
    $taxonomy_slug = $wp_query->query_vars['taxonomy'];
    $term_slug = isset($wp_query->query_vars['term']) ? $wp_query->query_vars['term'] : '';

    // 🔹 1. `http://seisei.local:8999/jav/play/` のみJAVを追加
    if (empty($term_slug)) { // ルートタクソノミーページ
      $jav_link = [
        'url' => home_url("/jav/"),
        'text' => 'JAV',
      ];
      array_splice($links, 1, 0, [$jav_link]); // Home の次にJAVを挿入
    }

    // 🔹 2. タクソノミーのルートページ (jav/play/)
    $taxonomy_label = get_taxonomy($taxonomy_slug)->labels->name;
    $taxonomy_link = [
      'url' => home_url("/jav/{$taxonomy_slug}/"),
      'text' => $taxonomy_label,
    ];

    if (empty($term_slug)) {
      array_splice($links, -1, 1, [$taxonomy_link]);
    } else {
      $term_link_url = get_term_link($term_slug, $taxonomy_slug);
      if (is_wp_error($term_link_url) || empty($term_link_url)) {
        error_log("⚠️ WARNING: `get_term_link()` returned null for taxonomy `$taxonomy_slug`. Using fallback.");
        $term_link_url = home_url("/jav/{$taxonomy_slug}/"); // ルートページの代替URL
      }

      $term_link = [
        'url' => $term_link_url,
        'text' => single_term_title('', false),
      ];

      // **YoastがすでにJAVを追加しているので、重複しないように修正**
      $links = array_values(array_filter($links, function ($link) use ($term_link) {
        return isset($link['url'], $term_link['url']) && $link['url'] !== $term_link['url'];
      }));

      // **パンくずの順序を統一（「プレイ内容」→「マダイ」）**
      array_splice($links, -1, 1, [$taxonomy_link, $term_link]);

      // 🔹 4. 「play」タクソノミーでは親タームを削除
      if ($taxonomy_slug === 'play') {
        $term = get_term_by('slug', $term_slug, $taxonomy_slug);
        if ($term && $term->parent) {
          $parent_term = get_term($term->parent, $taxonomy_slug);
          if ($parent_term) {
            // 親タームのURLに一致する要素を削除
            $links = array_values(array_filter($links, function ($link) use ($parent_term, $taxonomy_slug) {
              return $link['url'] !== get_term_link($parent_term->term_id, $taxonomy_slug);
            }));
          }
        }
      }

      // 🔹 5. ページネーション (page/X/)
      if (get_query_var('paged') > 1) {
        $page_number = get_query_var('paged');
        $pagination_link = [
          'url' => home_url("/jav/{$taxonomy_slug}/{$term_slug}/page/{$page_number}/"),
          'text' => "Page {$page_number}",
        ];
        array_push($links, $pagination_link); // 最後にページ番号を追加
      }
    }
  }

  return $links;
});


// Yoast SEO の構造化データを最適化
add_filter('wpseo_schema_webpage', function ($data) {
  global $wp_query;

  // 🔹 0. トップページの場合
  if (is_front_page()) {
    // `@type` を `WebPage` に変更
    $data['@type'] = 'WebPage';

    // `BreadcrumbList` を削除
    // if (isset($data['breadcrumb'])) {
    //   unset($data['breadcrumb']);
    // }
  }

  // 🔹 1. 記事ページ
  if (is_singular('jav') || is_singular('news')) {
    $data['@type'] = 'Article'; // 記事として登録
    $data['headline'] = get_the_title();
    $data['author'] = [
      '@type' => 'Person',
      'name' => get_the_author(),
    ];
    $data['datePublished'] = get_the_date('c');
    $data['dateModified'] = get_the_modified_date('c');

    // 🔹 mainEntityOfPage を追加
    $data['mainEntityOfPage'] = [
      '@type' => 'WebPage',
      '@id' => get_permalink()
    ];

    // 🔹 画像を設定（無ければロゴ）
    $image_url = has_post_thumbnail() ? get_the_post_thumbnail_url() : get_template_directory_uri() . '/assets/img/common/logo.png';
    $data['image'] = [
        '@type' => 'ImageObject',
        'url' => $image_url
      ];

    // 🔹 publisher（組織情報）
    $data['publisher'] = [
      '@type' => 'Organization',
      'name' => 'JAVREV',
      'logo' => [
        '@type' => 'ImageObject',
        'url' => get_template_directory_uri() . '/assets/img/common/logo.png'
      ]
    ];
  }

  // 🔹 2. カスタム投稿ルート（JAV アーカイブ or タクソノミー）
  if (is_post_type_archive('jav') || is_tax()) {
    unset($data['mainEntity']); // mainEntity を削除

    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $archive_url = home_url('/jav/');
    $current_page_url = ($paged > 1) ? trailingslashit($archive_url) . "page/$paged/" : $archive_url;

    // `@id` と `url` を統一
    $data['@id'] = $current_page_url;
    $data['url'] = $current_page_url;
  }

  // 🔹 タクソノミー条件分岐
  if (isset($wp_query->query_vars['taxonomy']) && !empty($wp_query->query_vars['taxonomy'])) {
    $taxonomy_slug = $wp_query->query_vars['taxonomy'];
    $taxonomy_url = home_url("/jav/{$taxonomy_slug}/");
    $term_slug = isset($wp_query->query_vars['term']) ? $wp_query->query_vars['term'] : '';

    // 🔹 4 タクソノミーのルートページ (`/jav/play/` など)
    $data['@type'] = 'WebPage';
    $data['@id'] = $taxonomy_url;
    $data['url'] = $taxonomy_url;
    $data['name'] = get_taxonomy($taxonomy_slug)->labels->name . ' - JAVREV';
    $data['breadcrumb'] = ['@id' => $taxonomy_url . '#breadcrumb'];

    // 🔹 5 タームページ (`/jav/play/fi1-en/` など)
    if (!empty($term_slug)) {
      $term = get_term_by('slug', $term_slug, $taxonomy_slug);
      if ($term) {
        $term_url = get_term_link($term);
        if (!is_wp_error($term_url)) {
          $data['@type'] = 'CollectionPage';
          $data['@id'] = $term_url;
          $data['url'] = $term_url;
          $data['name'] = $term->name . ' - JAVREV';
          $data['breadcrumb'] = ['@id' => $term_url . '#breadcrumb'];
        }
      }
    }
  }

  //　 🔹 6 タームのページ送りページに対応
  if (is_tax()) {
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $taxonomy = get_queried_object();

    if ($taxonomy) {
      $term_url = get_term_link($taxonomy);
      if (!is_wp_error($term_url)) {
        $current_page_url = ($paged > 1) ? trailingslashit($term_url) . "page/$paged/" : $term_url;

        $data['@id'] = $current_page_url;
        $data['url'] = $current_page_url;

        // 🔹 `breadcrumb.@id` もページ番号付きに修正
        if (isset($data['breadcrumb'])) {
          $data['breadcrumb']['@id'] = $current_page_url . '#breadcrumb';
        }
      }
    }
  }

  // 実行
  return $data;
});

//　collectionpage @id を統一する処理
// add_filter('wpseo_schema_collectionpage', function ($data) {
//   if (is_post_type_archive('jav')) {
//     $paged = get_query_var('paged') ? get_query_var('paged') : 1;
//     $archive_url = home_url('/jav/');
//     $current_page_url = ($paged > 1) ? trailingslashit($archive_url) . "page/$paged/" : $archive_url;

//     $data['@id'] = $current_page_url; // ✅ これで `@id` を修正
//     $data['url'] = $current_page_url;
//   }
//   return $data;
// });


// 構造化データの、パン屑関連
add_filter('wpseo_schema_breadcrumb', function ($data) {
  global $wp_query;
  $paged = get_query_var('paged') ? get_query_var('paged') : 1;

  if (is_post_type_archive('jav') || is_tax()) {
    $archive_url = home_url('/jav/');

    // `JAV` の `item`（URL）がない場合に追加
    foreach ($data['itemListElement'] as &$item) {
      if ($item['name'] === 'JAV' && !isset($item['item'])) {
        $item['item'] = home_url('/jav/');
      }
    }

    // ページネーションがある場合、「Page X」を追加（**itemなし**）
    if ($paged > 1) {
      $data['itemListElement'][] = [
        '@type'    => 'ListItem',
        'position' => count($data['itemListElement']) + 1,
        'name'     => "Page $paged"
      ];
    }

    // 🔹 **`Page X` の `item` を削除**
    foreach ($data['itemListElement'] as &$item) {
      if (strpos($item['name'], 'Page ') !== false) {
        unset($item['item']); // `item` を削除
      }
    }

    // 🔹 `is_tax()` の場合は適切なタクソノミーURLを設定
    if (is_tax()) {
      $taxonomy = get_queried_object();
      if ($taxonomy) {
        $archive_url = get_term_link($taxonomy);
      }
    }

    // 🔹 ページネーションの処理
    $current_page_url = ($paged > 1) ? trailingslashit($archive_url) . "page/$paged/" : $archive_url;

    // 🔹 `breadcrumb` の `@id` 修正
    if (isset($data['@id'])) {
      $data['@id'] = $current_page_url . '#breadcrumb';
    }

    // // 🔹 パンくずリストの `item` URL 修正
    foreach ($data['itemListElement'] as &$item) {
      if ($item['name'] === 'JAV') {
        $item['item'] = home_url('/jav/');
      }
    }
  }

  // 🔹 タクソノミールート or タームページの場合
  if (isset($wp_query->query_vars['taxonomy']) && !empty($wp_query->query_vars['taxonomy'])) {
    $taxonomy_slug = $wp_query->query_vars['taxonomy'];
    $taxonomy_url = home_url("/jav/{$taxonomy_slug}/");
    $term_slug = isset($wp_query->query_vars['term']) ? $wp_query->query_vars['term'] : '';

    // 🔹 タクソノミーの `@id`
    $data['@id'] = $taxonomy_url . '#breadcrumb';

    // 🔹 パンくずの `itemListElement` 修正
    foreach ($data['itemListElement'] as &$item) {
      if ($item['name'] === 'JAV') {
        $item['item'] = home_url('/jav/');
      }
      if ($item['name'] === $taxonomy_slug) {
        $item['item'] = $taxonomy_url;
      }
    }

    // 🔹 タームページの場合、`@id` をタームの URL に変更
    if (!empty($term_slug)) {
      $term = get_term_by('slug', $term_slug, $taxonomy_slug);
      if ($term) {
        $term_url = get_term_link($term);
        if (!is_wp_error($term_url)) {
          $data['@id'] = $term_url . '#breadcrumb';
          // パンくずリストも修正
          foreach ($data['itemListElement'] as &$item) {
            if ($item['name'] === $term->name) {
              $item['item'] = $term_url;
            }
          }
        }
      }
    }
  }

  // タームのページ送りページ
  if (is_tax()) {
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $taxonomy = get_queried_object();

    if ($taxonomy) {
      $term_url = get_term_link($taxonomy);
      if (!is_wp_error($term_url)) {
        $current_page_url = ($paged > 1) ? trailingslashit($term_url) . "page/$paged/" : $term_url;

        // 🔹 `@id` にページネーション情報を追加
        $data['@id'] = $current_page_url . '#breadcrumb';

        // 🔹 `BreadcrumbList` に「Page X」を追加（**URLなし**）
        // if ($paged > 1) {
        //   $data['itemListElement'][] = [
        //     '@type' => 'ListItem',
        //     'position' => count($data['itemListElement']) + 1,
        //     'name' => "Page $paged"
        //   ];
        // }
      }
    }
  }

  return $data;
});









// スキーマデータにArticleにする
// add_filter('wpseo_schema_webpage', function ($data) {
//   if (is_singular('jav') || is_singular('news')) {
//     $data['@type'] = 'Article'; // または 'NewsArticle'
//     $data['headline'] = get_the_title();
//     $data['author'] = [
//       '@type' => 'Person',
//       'name' => get_the_author(),
//     ];
//     $data['datePublished'] = get_the_date('c');
//     $data['dateModified'] = get_the_modified_date('c');
//   }
//   return $data;
// });
