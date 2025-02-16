<?php

// 記事内でテーマのurlを取得
function shortcode_template_directory_uri() {
  return get_template_directory_uri();
}
add_shortcode('tpl', 'shortcode_template_directory_uri');

?>