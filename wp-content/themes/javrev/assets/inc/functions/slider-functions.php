<?php

function get_slider_images_data()
{
  $article_no    = get_post_meta(get_the_ID(), 'acf_article_no', true);
  $image_numbers = get_post_meta(get_the_ID(), 'acf_slide_cardimg', true);
  $image_alts    = get_post_meta(get_the_ID(), 'acf_slide_alt', true);
  $image_count   = get_post_meta(get_the_ID(), 'acf_slide_count', true);
  $image_directory = "/img/" . floor($article_no / 100) . "/" . $article_no . "/";

  $slider_images = [];

  if ($article_no && !empty($image_numbers) && !empty($image_alts) && !empty($image_count)) {
    $image_numbers = array_map('trim', explode(',', $image_numbers));
    $image_numbers = array_slice($image_numbers, 0, 3); // 最大3枚まで取得
    $alt_list = array_map('trim', explode("\n", $image_alts));

    // ALT の数と画像数が一致しない場合の処理
    if (count($alt_list) !== (int)$image_count) {
      $article_no = $article_no ?? '不明';
      $lang_code = ICL_LANGUAGE_CODE ?? 'N/A'; // WPMLの言語コード

      // ログインしている管理者にエラーメッセージを表示
      if (is_user_logged_in() && current_user_can('manage_options')) {
        ob_start();
      ?>
        <div style="width: 220px;height: 150px;position: fixed;right: 20px;bottom: 20px;
                            background: red;color: white;display: flex;align-items: center;
                            justify-content: center;padding: 20px;font-size: 13px;line-height: 1.5;
                            box-shadow: 0 2px 10px rgba(0,0,0,0.3);z-index: 9999;">
          記事NO：<?php echo esc_html($article_no); ?><br>
          言語：<?php echo esc_html($lang_code); ?><br>
          画像とALTの数が一致しません。
        </div>
    <?php
        echo ob_get_clean();
      }

      // ALTリストを空にする（ズレを防ぐ）
      $alt_list = [];
    }

    // ALT のマッピング
    $alt_mapping = [];
    foreach ($alt_list as $line_number => $alt_text) {
      $image_no = $line_number + 1;
      $alt_mapping[$image_no] = $alt_text;
    }

    // 存在する画像を事前にリスト化
    foreach ($image_numbers as $image_no) {
      $image_path = $_SERVER['DOCUMENT_ROOT'] . $image_directory . $image_no . '.jpg';
      if (file_exists($image_path)) {
        $slider_images[] = [
          'path' => $image_directory . $image_no . '.jpg',
          'alt'  => $alt_mapping[$image_no] ?? ""
        ];
      }
    }
  }

  return $slider_images;
}
