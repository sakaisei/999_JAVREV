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
      if (isset($wp_query->query_vars['taxonomy']) && !empty($wp_query->query_vars['taxonomy'])) {
        // `/jav/play/` の場合 → タクソノミー関連のリクエスト
        $taxonomy = $wp_query->query_vars['taxonomy'];
        if (isset($wp_query->query_vars['term']) && !empty($wp_query->query_vars['term'])) {
          // `/jav/タクソノミー/ターム/` の場合
          get_card_loop([
            'post_type'      => 'jav',
            'posts_per_page' => 3,
            'paged'          => get_query_var('paged', 1)
          ]);
        } else {
          // `/jav/タクソノミー/` の場合
          echo '何かしらのテンプレートや関数化した何かを読み込むようにする';
        }
      } elseif (is_post_type_archive('jav')) {
        // `/jav/` の場合
        get_card_loop([
          'post_type'      => 'jav',
          'posts_per_page' => 3,
          'paged'          => get_query_var('paged', 1)
        ]);
      } else {
        // その他のケース
      }
      ?>
    </div>
  </section>
  <?php // get_template_part('assets/inc/parts/list__pagination'); ?>
</main>
<aside class="sidebar__layout">
  <?php get_template_part('assets/inc/parts/popular-tags'); ?>
  <?php get_template_part('assets/inc/parts/search-by-criteria'); ?>
</aside>
<?php get_footer(); ?>