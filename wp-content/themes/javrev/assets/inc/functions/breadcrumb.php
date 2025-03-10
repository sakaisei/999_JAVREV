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
