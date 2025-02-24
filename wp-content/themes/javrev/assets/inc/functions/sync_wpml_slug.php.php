<?php

// function sync_wpml_slug($post_id, $post)
// {
//   // ゴミ箱に移動された場合はスキップ
//   if (get_post_status($post_id) === 'trash') return;

//   // WPMLのメイン言語の投稿を取得
//   $original_post_id = apply_filters('wpml_object_id', $post_id, get_post_type($post_id), false, wpml_get_default_language());

//   if ($original_post_id && $original_post_id != $post_id) {
//     $original_slug = get_post_field('post_name', $original_post_id);
//     if (!empty($original_slug)) {
//       // スラッグを更新
//       wp_update_post([
//         'ID'        => $post_id,
//         'post_name' => $original_slug,
//       ]);
//     }
//   }
// }

// add_action('save_post', 'sync_wpml_slug', 10, 2);
