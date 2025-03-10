<?php

// タクソノミーページの `next` を削除
add_action('template_redirect', function () {
  global $wp_query;

  // `jav` のすべてのカスタムタクソノミーを取得（共通関数を利用）
  $taxonomies = get_all_taxonomies('jav');

  if (
    isset($wp_query->query_vars['taxonomy']) &&
    in_array($wp_query->query_vars['taxonomy'], $taxonomies, true) &&
    empty($wp_query->query_vars['term']) // タクソノミートップページのみ対象
  ) {
    ob_start(function ($buffer) {
      // `<link rel="next" href="...">` を削除
      $buffer = preg_replace('/<link rel="next"[^>]+>/i', '', $buffer);
      return $buffer;
    });
  }
});
