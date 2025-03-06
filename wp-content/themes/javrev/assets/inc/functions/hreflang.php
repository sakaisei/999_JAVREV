<?php

//　ページネーションのページにhreflangを設定
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
      return $buffer;
    });
  }
});


//　ページネーションのページにhreflangを設定
function add_hreflang_tags_for_pagination() {
  if (!function_exists('icl_get_languages')) {
    return; // WPMLが有効でない場合は何もしない
  }

  global $wp, $wp_query;
  $languages = icl_get_languages('skip_missing=0&orderby=code');
  if (empty($languages)) return;

  // 言語コードのマッピング (WPMLの `en` を `en-us` に変換)
  $mapped_languages = [];
  foreach ($languages as $code => $lang) {
    $mapped_code = ($code === 'en') ? 'en-us' : $code;
    $mapped_languages[$mapped_code] = $lang;
  }

  // 出力順を統一
  $lang_order = ['en-us', 'ja', 'nl'];

  // 現在のURL
  $current_url = home_url(add_query_arg([], $wp->request));
  $page_number = get_query_var('paged') ? get_query_var('paged') : 1;

  // `page/X/` がすでに含まれている場合は削除
  $current_url = preg_replace('/\/page\/\d+\/?$/', '', $current_url);

  // タクソノミーページの判定
  $is_tax_page = isset($wp_query->query_vars['taxonomy']) && !empty($wp_query->query_vars['taxonomy']);

  // hreflang を出力する条件：
  // 1. **ページネーション (`is_paged()`) のみ**
  // 2. **タクソノミーページ (`jav/play/` など) では出力しない**
  if (is_paged()) {
    echo "\n<!-- hreflang start (pagination only) -->\n";

    foreach ($lang_order as $lang_code) {
      if (isset($mapped_languages[$lang_code])) {
        $lang_url = $mapped_languages[$lang_code]['active'] ? $current_url : $mapped_languages[$lang_code]['url'];

        // ページネーションがある場合のみ `page/2/` を追加
        if ($page_number > 1) {
          $lang_url = trailingslashit($lang_url) . 'page/' . $page_number . '/';
        }

        echo '  <link rel="alternate" hreflang="' . esc_attr($lang_code) . '" href="' . esc_url($lang_url) . '" />' . "\n";
      }
    }

    // x-default もページネーションに対応
    $default_lang = isset($mapped_languages['en-us']) ? $mapped_languages['en-us']['url'] : home_url('/');
    if ($page_number > 1) {
      $default_lang = trailingslashit($default_lang) . 'page/' . $page_number . '/';
    }
    echo '  <link rel="alternate" hreflang="x-default" href="' . esc_url($default_lang) . '" />' . "\n";

    echo "<!-- hreflang end -->\n\n";
  }
}

add_action('wp_head', 'add_hreflang_tags_for_pagination', 1);
