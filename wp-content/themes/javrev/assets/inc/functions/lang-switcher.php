<?php

// 言語スイッチャーのメイン関数
function custom_language_switcher()
{
  if (!function_exists('icl_get_languages')) {
    return; // WPMLが有効でない場合は何もしない
  }

  global $wp, $wp_query;
  $languages = apply_filters('wpml_active_languages', null, 'skip_missing=0');
  if (empty($languages)) return;

  // 現在のURLを取得
  $current_url = home_url(add_query_arg([], $wp->request));
  $current_url = preg_replace('/\/page\/\d+\/?$/', '', $current_url); // `page/X/` の二重登録を防ぐ

  // ページネーションの取得
  $page_number = get_query_var('paged') ? get_query_var('paged') : 1;

  // タクソノミー情報を取得
  $is_tax_page = isset($wp_query->query_vars['taxonomy']) && !empty($wp_query->query_vars['taxonomy']);
  $taxonomy_slug = $is_tax_page ? $wp_query->query_vars['taxonomy'] : '';

  echo '<ul class="custom-language-switcher lang-menu" aria-label="' . esc_attr(lang('common.switcher-list--aria')) . '">';

  foreach ($languages as $lang) {
    $lang_code = $lang['language_code']; // 言語コード取得
    $lang_prefix = ($lang_code === 'en') ? '' : "/{$lang_code}"; // 英語は `/en/` をつけない
    $lang_url = trailingslashit($lang['url']); // **末尾 `/` を強制**

    // 1. **ページネーションの処理**
    if ($page_number > 1) {
      $lang_url = untrailingslashit($lang_url) . "/page/{$page_number}/";
    }

    // 2. **タクソノミーの親ページの処理 (`/jav/play/` など)**
    if ($is_tax_page && empty($wp_query->query_vars['term'])) {
      // `/jav/` がすでにあるかチェックして重複を防ぐ
      $lang_url = rtrim($lang_url, '/'); // 末尾 `/` を削除
      if (basename($lang_url) !== 'jav') { // すでに `/jav` が最後なら追加しない
        $lang_url .= "/jav";
      }
      $lang_url .= "/{$taxonomy_slug}/"; // タクソノミースラッグを追加

      // **デバッグログ出力**
      error_log("🔍 FIXED: Updated URL for Taxonomy Parent Page ({$lang_code}): " . $lang_url);
    }

    // 3. **最終的なURLの調整**
    $lang_url = esc_url(trailingslashit($lang_url)); // **再度末尾 `/` を確実に**

    // 言語スイッチャーのHTML出力
    echo '<li>';
    echo '<a href="' . $lang_url . '">';
    echo '<img src="' . esc_url($lang['country_flag_url']) . '" alt="">';
    echo esc_html($lang['native_name']);
    echo '</a>';
    echo '</li>';
  }

  echo '</ul>';
}



// 言語スイッチャーの処理を読み込む
require_once get_template_directory() . '/assets/inc/functions/lang-switcher.php';
