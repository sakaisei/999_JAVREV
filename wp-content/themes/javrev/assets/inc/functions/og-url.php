<?php

// タクソノミーページ & ページネーションの og:url を修正
add_action('template_redirect', function () {
  global $wp_query;

  ob_start(function ($buffer) use ($wp_query) {
    $taxonomies = get_all_taxonomies('jav');
    $paged = get_query_var('paged') ? get_query_var('paged') : 1;
    $current_url = home_url($_SERVER['REQUEST_URI']); // ここで `home_url()` を適切に適用

    // タクソノミートップページ (`jav/play/` など)
    if (
      isset($wp_query->query_vars['taxonomy']) &&
      in_array($wp_query->query_vars['taxonomy'], $taxonomies, true) &&
      empty($wp_query->query_vars['term']) // タクソノミーのトップページのみ対象
    ) {
      $taxonomy_slug = $wp_query->query_vars['taxonomy']; // 例: play, genre など
      $current_url = home_url("/jav/{$taxonomy_slug}/");
    }

    // ページネーションが適用されている場合
    if (is_paged()) {
      $current_url = get_pagenum_link($paged); // home_url() 不要
    }

    // `<meta property="og:url" content="...">` を修正
    $buffer = preg_replace_callback(
      '/<meta property="og:url"[^>]+content="([^"]+)"/i',
      function ($matches) use ($current_url) {
        return str_replace($matches[1], esc_url($current_url), $matches[0]);
      },
      $buffer
    );

    return $buffer;
  });
});

