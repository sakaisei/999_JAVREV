<?php

function get_card_loop($args = [], $class = '', $wrap_class = '')
{
  $defaults = [
    'post_type'      => 'jav',
    'posts_per_page' => 12,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'paged'          => get_query_var('paged', 1)
  ];

  // もしタクソノミーが指定されていたら `tax_query` を作成
  if (is_tax()) {
    $taxonomy = get_queried_object()->taxonomy; // 現在のタクソノミー
    $term_id  = get_queried_object()->term_id; // 現在のタームID

    $defaults['tax_query'] = [
      [
        'taxonomy' => $taxonomy,
        'field'    => 'term_id',
        'terms'    => $term_id,
      ]
    ];
  }

  $query_args = wp_parse_args($args, $defaults);
  $query      = new WP_Query($query_args);

  if ($query->have_posts()) :
    echo '<div class="card__normalwrap ' . esc_attr($wrap_class) . '">';
    while ($query->have_posts()) : $query->the_post();
      $taxonomy_data = get_post_taxonomies_and_terms(get_the_ID());

      $args = [
        'class'         => $class,
        'taxonomy_data' => $taxonomy_data
      ];
      include locate_template('assets/inc/parts/card__normal.php');

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