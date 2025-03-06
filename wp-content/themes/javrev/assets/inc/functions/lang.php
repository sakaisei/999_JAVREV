<?php

function lang($key) {
  static $translations = null;

  if ($translations === null) {
    $lang = defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : 'en';
    $cache_key = "translations_{$lang}";

    // キャッシュから取得
    if ($cached = apcu_fetch($cache_key)) {
      $translations = $cached;
    } else {
    // JSONファイルを読み込む
      $json_path = get_template_directory() . "/assets/json/lang-{$lang}.json";
        if (file_exists($json_path)) {
          $translations = json_decode(file_get_contents($json_path), true);
          apcu_store($cache_key, $translations, 3600); // 1時間キャッシュ
        } else {
          $translations = [];
        }
    }
  }

  return $translations[$key] ?? '';
}

// 実装時にコメントアウト
apcu_delete("translations_en"); // 英語のキャッシュ削除
apcu_delete("translations_ja"); // 日本語のキャッシュ削除
