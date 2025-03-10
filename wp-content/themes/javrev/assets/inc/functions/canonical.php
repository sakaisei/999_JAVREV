<?php

// タクソノミーページの canonical URL を修正
add_action('template_redirect', function () {
  global $wp_query;

  // `jav` のすべてのカスタムタクソノミーを取得（共通関数を利用）
  $taxonomies = get_all_taxonomies('jav');

  // `playtime` や `format` などのタクソノミートップページ（英語・多言語どちらも対象）
  if (
    isset($wp_query->query_vars['taxonomy']) &&
    in_array($wp_query->query_vars['taxonomy'], $taxonomies, true) &&
    empty($wp_query->query_vars['term']) // タクソノミーのトップページのみ対象
  ) {
    ob_start(function ($buffer) use ($wp_query) {
      $taxonomy_slug = $wp_query->query_vars['taxonomy']; // 例: playtime, format など
      $corrected_url = home_url("/jav/{$taxonomy_slug}/");

      // `<link rel="canonical" href="...">` を修正
      $buffer = preg_replace_callback(
        '/<link rel="canonical"[^>]+href="([^"]+)"/i',
        function ($matches) use ($corrected_url) {
          return str_replace($matches[1], esc_url($corrected_url), $matches[0]);
        },
        $buffer
      );
      return $buffer;
    });
  }
});
