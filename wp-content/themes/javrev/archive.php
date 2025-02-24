<?php get_header(); ?>
<?php
if (function_exists('yoast_breadcrumb')) {
  yoast_breadcrumb('<nav class="list__breadcrumb margin-top">', '</nav>');
}

// global $wp_queryの宣言
global $wp_query;
?>
<main class="main__common">
  <section class="contents">
    <div class="inner-layout layout__normal layout__padding">
      <div class="ttl__layout">
        <?php
        echo '<h1 class="ttl">';
        if (isset($wp_query->query_vars['taxonomy']) && !empty($wp_query->query_vars['taxonomy'])) {
          // `/jav/play/` の場合 → タクソノミー関連のリクエスト
          $taxonomy = $wp_query->query_vars['taxonomy'];
          if (isset($wp_query->query_vars['term']) && !empty($wp_query->query_vars['term'])) {
            // `/jav/タクソノミー/ターム/` の場合
            echo esc_html(single_term_title('', false));
          } else {
            // `/jav/タクソノミー/` の場合
            echo esc_html(get_taxonomy($taxonomy)->label) . ' タグ一覧';
          }
        } elseif (is_post_type_archive('jav')) {
          // `/jav/` の場合
          echo 'すべての記事一覧';
        } else {
          // その他のケース
          echo 'アーカイブページ';
        }
        echo '</h1>';
        ?>
        <p class="text">各descriptionが挿入されます。</p>
      </div>
      <?php get_template_part('assets/inc/parts/btn__query'); ?>
      <?php
      // if (isset($wp_query->query_vars['taxonomy']) && !empty($wp_query->query_vars['taxonomy'])) {
      //   // `/jav/play/` の場合 → タクソノミー関連のリクエスト
      //   $taxonomy = $wp_query->query_vars['taxonomy'];
      //   if (isset($wp_query->query_vars['term']) && !empty($wp_query->query_vars['term'])) {
      //     // `/jav/タクソノミー/ターム/` の場合
      //     get_card_loop([
      //       'post_type'      => 'jav',
      //       'posts_per_page' => 3,
      //       'paged'          => get_query_var('paged', 1)
      //     ]);
      //   } else {
      //     // `/jav/タクソノミー/` の場合
      //     echo '何かしらのテンプレートや関数化した何かを読み込むようにする';
      //   }
      // } elseif (is_post_type_archive('jav')) {
      //   // `/jav/` の場合
      //   get_card_loop([
      //     'post_type'      => 'jav',
      //     'posts_per_page' => 3,
      //     'paged'          => get_query_var('paged', 1)
      //   ]);
      // } else {
      //   // その他のケース
      // }
      ?>


      <?php
      $paged = max(1, get_query_var('paged', 1));

      // カスタムクエリで3件取得
      $args = array(
        'post_type'      => 'jav',
        'posts_per_page' => 3,
        'paged'          => $paged,
      );

      $custom_query = new WP_Query($args);
      ?>

      <?php if ($custom_query->have_posts()) : ?>
        <ul class="post-list">
          <?php while ($custom_query->have_posts()) : $custom_query->the_post(); ?>
            <li>
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </li>
          <?php endwhile; ?>
        </ul>

        <!-- ページネーション -->
        <?php
        $big = 999999999;
        $total_pages = $custom_query->max_num_pages;

        if ($total_pages > 1) :
        ?>
          <nav class="pagination">
            <ul>
              <?php
              if ($paged > 1) {
                echo '<li><a href="' . get_pagenum_link($paged - 1) . '">« Prev</a></li>';
              } else {
                echo '<li><span class="disabled">« Prev</span></li>';
              }

              $pagination_links = paginate_links(array(
                'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format'    => '/page/%#%/',
                'current'   => $paged,
                'total'     => $total_pages,
                'mid_size'  => 2,
                'end_size'  => 1,
                'prev_next' => false,
                'type'      => 'array',
              ));

              if (!empty($pagination_links)) {
                foreach ($pagination_links as $link) {
                  echo '<li>' . $link . '</li>';
                }
              }

              if ($paged < $total_pages) {
                echo '<li><a href="' . get_pagenum_link($paged + 1) . '">Next »</a></li>';
              } else {
                echo '<li><span class="disabled">Next »</span></li>';
              }
              ?>
            </ul>
          </nav>
        <?php endif; ?>

      <?php else : ?>
        <p>No posts found.</p>
      <?php endif; ?>

      <?php wp_reset_postdata(); ?>







    </div>
  </section>
  <?php // get_template_part('assets/inc/parts/list__pagination'); 
  ?>
</main>
<aside class="sidebar__layout">
  <?php get_template_part('assets/inc/parts/popular-tags'); ?>
  <?php get_template_part('assets/inc/parts/search-by-criteria'); ?>
</aside>
<?php get_footer(); ?>