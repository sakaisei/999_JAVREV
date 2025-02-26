<?php
add_filter('wpml_get_taxonomy_terms', function ($terms, $taxonomy) {
  global $wp_query;

  // ルートページ (`/jav/play/`) で `null` が出る場合の回避策
  if (is_tax($taxonomy) && empty($terms)) {
    error_log("⚠️ WPML: No terms found for taxonomy `$taxonomy`. Returning empty array.");
    return []; // `null` を WPML に渡さず、明示的に空の配列を渡す
  }

  return $terms;
}, 10, 2);

add_filter('wpml_current_taxonomy_term', function ($term) {
  global $wp_query;

  if (is_tax() && empty($term)) {
    $taxonomy = $wp_query->query_vars['taxonomy'] ?? null;

    if ($taxonomy) {
      error_log("✅ FIXED: Setting WPML term object for taxonomy `$taxonomy`.");
      return (object) [
        'taxonomy' => $taxonomy,
        'term_id' => 0, // `null` ではなく `0`
        'slug' => '',
        'name' => get_taxonomy($taxonomy)->labels->name ?? 'Unknown',
        'filter' => 'raw'
      ];
    }
  }

  return $term;
});
