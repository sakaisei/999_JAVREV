<?php

// Yoast SEO の canonical URL を修正
add_filter('wpseo_canonical', function($canonical) {
  global $wp, $wp_query;

  // 現在のページ情報を取得
  $current_url = home_url(add_query_arg([], $wp->request));
  $paged = get_query_var('paged') ? get_query_var('paged') : 1;

  // タクソノミーページかどうかを判定
  $is_tax_page = isset($wp_query->query_vars['taxonomy']) && !empty($wp_query->query_vars['taxonomy']);

  // `page/X/` がすでに含まれている場合は削除し、ベースURLを確定
  $canonical = preg_replace('/\/page\/\d+\/?$/', '', $current_url);

  // 🔹 タクソノミーアーカイブの canonical を修正
  if ($is_tax_page) {
    $taxonomy_slug = $wp_query->query_vars['taxonomy'];
    $canonical = home_url("/jav/{$taxonomy_slug}/");
  }

  // 🔹 ページネーション対応 (`page/X/` を追加)
  if ($paged > 1) {
    $canonical = trailingslashit($canonical) . "page/{$paged}/";
  }

  return esc_url($canonical);
});
