<?php

/**
 * 指定した投稿タイプのすべてのタクソノミーを取得
 * 
 * @param string $post_type 投稿タイプ（例: 'jav'）
 * @return array タクソノミーリスト（タクソノミー名の配列）
 */
function get_all_taxonomies($post_type = '')
{
  if (empty($post_type)) {
    return [];
  }
  return get_object_taxonomies($post_type, 'names');
}

/**
 * 指定した投稿のタクソノミーとターム情報を取得
 * 
 * @param int $post_id 投稿ID
 * @return array タクソノミーとターム情報（`name`, `slug`, `link`）
 */
function get_post_taxonomies_and_terms($post_id)
{
  $post_type      = get_post_type($post_id);
  $taxonomy_slugs = get_object_taxonomies($post_type, 'names');
  $taxonomy_data  = [];

  if (empty($taxonomy_slugs) || !is_array($taxonomy_slugs)) {
    return [];
  }

  foreach ($taxonomy_slugs as $taxonomy) {
    $terms = get_the_terms($post_id, $taxonomy);
    $taxonomy_data[$taxonomy] = [];

    if (!empty($terms) && !is_wp_error($terms)) {
      foreach ($terms as $term) {
        $taxonomy_data[$taxonomy][] = [
          'name' => $term->name,
          'slug' => $term->slug,
          'link' => get_term_link($term->term_id, $taxonomy),
        ];
      }
    }
  }

  return $taxonomy_data;
}
