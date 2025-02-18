<?php

// function get_post_taxonomies($post_id)
// {
//   $post_type      = get_post_type($post_id);
//   $taxonomy_slugs = get_object_taxonomies($post_type, 'names');
//   $taxonomy_data  = [];

//   foreach ($taxonomy_slugs as $taxonomy) {
//     $terms = get_the_terms($post_id, $taxonomy);

//     if (!empty($terms) && !is_wp_error($terms)) {
//       $taxonomy_data[$taxonomy] = []; // 空の配列を用意
//       foreach ($terms as $term) {
//         $taxonomy_data[$taxonomy][] = $term->name; // ターム名のみ格納
//       }
//     } else {
//       error_log("DEBUG: 投稿ID {$post_id} のタクソノミー '{$taxonomy}' にタームなし or エラー");
//     }
//   }

//   error_log("DEBUG: 投稿ID {$post_id} のタクソノミーデータ：" . print_r($taxonomy_data, true));
//   return $taxonomy_data;
// }

?>