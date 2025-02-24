<?php

// カスタムタクソノミーの root ページの hreflang のurlを正しいものへと置き換える
add_action('template_redirect', function () {
  global $wp_query;

  // `jav` のすべてのカスタムタクソノミーを取得（共通関数を利用）
  $taxonomies = get_all_taxonomies('jav');

  // `playtime` や `format` などのトップページ（英語・多言語どちらも対象）
  if (
    isset($wp_query->query_vars['taxonomy']) &&
    in_array($wp_query->query_vars['taxonomy'], $taxonomies, true) &&
    empty($wp_query->query_vars['term']) // タクソノミーのトップページのみ対象
  ) {
    ob_start(function ($buffer) use ($wp_query) {
      $current_taxonomy = $wp_query->query_vars['taxonomy']; // 例: playtime, format など

      // `<link rel="alternate" hreflang="..." href="...">` の `href` にタクソノミースラッグを追加
      $buffer = preg_replace_callback(
        '/<link rel="alternate"[^>]+href="([^"]+)"/i',
        function ($matches) use ($current_taxonomy) {
          $corrected_url = untrailingslashit($matches[1]) . '/' . $current_taxonomy . '/';
          return str_replace($matches[1], esc_url($corrected_url), $matches[0]);
        },
        $buffer
      );

      // `<link rel="canonical" href="...">` の `href` にタクソノミースラッグを追加
      $buffer = preg_replace_callback(
        '/<link rel="canonical"[^>]+href="([^"]+)"/i',
        function ($matches) use ($current_taxonomy) {
          $corrected_url = untrailingslashit($matches[1]) . '/' . $current_taxonomy . '/';
          return str_replace($matches[1], esc_url($corrected_url), $matches[0]);
        },
        $buffer
      );

      // 言語切り替えメニューの `<a href="...">` にタクソノミースラッグを追加
      $buffer = preg_replace_callback(
        '/<a href="([^"]+)"[^>]*>\s*<img[^>]+country_flag_url[^>]+>\s*([^<]+)<\/a>/i',
        function ($matches) use ($current_taxonomy) {
          $corrected_url = untrailingslashit($matches[1]) . '/' . $current_taxonomy . '/';
          return str_replace($matches[1], esc_url($corrected_url), $matches[0]);
        },
        $buffer
      );

      // `<link rel="alternate" type="application/rss+xml" ...>` の `RSS フィード` を削除し、削除したことを示すコメントを追加
      $buffer = preg_replace_callback(
        '/<link rel="alternate" type="application\/rss\+xml"[^>]+>/i',
        function ($matches) {
          return "<!-- RSS feed removed: " . $matches[0] . " -->";
        },
        $buffer
      );


      return $buffer;
    });
  }
});
