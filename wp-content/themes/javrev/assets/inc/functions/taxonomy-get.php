<?php

function get_all_taxonomies($post_id)
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
