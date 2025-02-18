<?php

function get_card_loop($args = [], $class = '', $wrap_class = '')
{
  $defaults = [
    'post_type'      => 'jav', // 投稿タイプを明示的に指定
    'posts_per_page' => 6,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'lang'           => ICL_LANGUAGE_CODE, // WPML対応
  ];

  $query_args = wp_parse_args($args, $defaults);
  $query      = new WP_Query($query_args);

  if ($query->have_posts()) :
    echo '<div class="card__normalwrap ' . esc_attr($wrap_class) . '">';
    while ($query->have_posts()) : $query->the_post();
      $taxonomy_data = jav_get_post_taxonomies(get_the_ID()); // タクソノミー取得

      get_template_part('assets/inc/parts/card__normal', null, [
        'class'         => $class,
        'taxonomy_data' => $taxonomy_data
      ]);

    endwhile;
    echo '</div>';
  else :
    echo '<p>記事が見つかりません。</p>';
  endif;

  wp_reset_postdata();
}




// 使い方 => 各テンプレートで以下のように使用する
///////////////////////
// get_card_loop([
//   'post_type' => 'jav',
//   'posts_per_page' => 6,
//   'orderby' => 'date',
//   'order' => 'DESC',
// ]);

// small クラス付きのカード（V2）+ col2 のラップ
// get_card_loop([
//   'post_type' => 'jav',
//   'posts_per_page' => 6,
//   'orderby' => 'date',
//   'order' => 'DESC',
// ], 'small', 'col2');

?>