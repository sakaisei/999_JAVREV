<?php
// スライダー画像取得関数
function get_slider_images($slider_class)
{
  $article_no = get_field('acf_article_no');
  $image_numbers = get_field('acf_slide_cardimg'); // カンマ区切りの画像番号
  $image_alts = get_field('acf_slide_alt'); // 改行区切りの alt テキスト
  $image_count = get_field('acf_slide_count'); // 画像の総枚数
  $image_directory = "/img/" . floor($article_no / 100) . "/" . $article_no . "/";

  if ($article_no && !empty($image_numbers) && !empty($image_alts) && !empty($image_count)) {
    $image_numbers = array_map('trim', explode(',', $image_numbers));
    $image_numbers = array_slice($image_numbers, 0, 3); // 最大3枚まで取得
    $alt_list = array_map('trim', explode("\n", $image_alts));

    if (count($alt_list) !== (int)$image_count) {
      error_log("⚠️ ALTの数と画像の総枚数が一致しません！ 処理をスキップします。");
      $alt_list = [];
    }

    $alt_mapping = [];
    foreach ($alt_list as $line_number => $alt_text) {
      $image_no = $line_number + 1;
      $alt_mapping[$image_no] = $alt_text;
    }

    error_log("[$slider_class] 画像番号とALTのマッピング: " . print_r($alt_mapping, true));

    foreach ($image_numbers as $image_no) {
      $image_path = $image_directory . $image_no . '.jpg';
      if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $image_path)) continue;

      $alt_text = $alt_mapping[$image_no] ?? "";
      error_log("[$slider_class] 画像 {$image_no} に対応する ALT: {$alt_text}");

      echo '<div class="swiper-slide ' . esc_attr($slider_class) . '-slide">';
      echo '<img src="' . esc_url($image_path) . '" alt="' . esc_attr($alt_text) . '" loading="lazy">';
      echo '</div>';
    }
  }
}
