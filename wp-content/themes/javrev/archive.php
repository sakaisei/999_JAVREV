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
        <p class="text"><?php
                        global $wp_query;
                        echo '<pre style="font-size:13px">';
                        //echo 'Template: archive.php' . PHP_EOL;
                        echo 'is_archive: ' . (is_archive() ? 'true' : 'false') . PHP_EOL;
                        echo 'is_tax: ' . (is_tax() ? 'true' : 'false') . PHP_EOL;
                        echo 'is_category: ' . (is_category() ? 'true' : 'false') . PHP_EOL;
                        echo 'is_tag: ' . (is_tag() ? 'true' : 'false') . PHP_EOL;
                        echo 'is_post_type_archive: ' . (is_post_type_archive() ? 'true' : 'false') . PHP_EOL;
                        echo 'Query Vars: ';
                        print_r($wp_query->query_vars);
                        echo '</pre>';
                        ?>
        </p>
        <p><?php
            // デバッグログへ書き込み
            // error_log("=== DEBUG: /jav/play/ ===");

            // // 現在のクエリ情報を取得
            // global $wp_query;
            // error_log("Query Vars: " . print_r($wp_query->query_vars, true));

            // // クエリオブジェクトを取得
            // $queried_object = get_queried_object();
            // error_log("Queried Object: " . print_r($queried_object, true));

            // // カスタムタクソノミー情報を取得
            // $taxonomy = isset($wp_query->query_vars['taxonomy']) ? $wp_query->query_vars['taxonomy'] : null;
            // error_log("Taxonomy Slug: " . print_r($taxonomy, true));

            // // ターム情報を取得（ある場合）
            // $term_slug = isset($wp_query->query_vars['term']) ? $wp_query->query_vars['term'] : null;
            // $term = !empty($term_slug) ? get_term_by('slug', $term_slug, $taxonomy) : null;
            // error_log("Term Slug: " . print_r($term_slug, true));
            // error_log("Term Object: " . print_r($term, true));

            // // クエリがタクソノミーアーカイブかどうか
            // $is_tax = is_tax();
            // error_log("Is Taxonomy Archive: " . ($is_tax ? "true" : "false"));

            // // もし `$term` が null の場合の追加デバッグ
            // if (!$term) {
            //   error_log("⚠️ WARNING: `get_term_by` returned null. The taxonomy or term might not exist.");
            // }

            // // デバッグ終了
            // error_log("=== DEBUG END ===");
            ?>
          <?php
          // $queried_object = get_queried_object();
          // error_log("✅ DEBUG: Final queried object");
          // error_log(print_r($queried_object, true));
          ?>
        </p>
      </div>
      <?php get_template_part('assets/inc/parts/btn__query'); ?>

      <?php
      $paged = max(1, get_query_var('paged', 1));

      if (isset($wp_query->query_vars['taxonomy']) && !empty($wp_query->query_vars['taxonomy'])) {
        // `/jav/play/` の場合 → タクソノミー関連のリクエスト
        $taxonomy = $wp_query->query_vars['taxonomy'];
        if (isset($wp_query->query_vars['term']) && !empty($wp_query->query_vars['term'])) {
          // `/jav/タクソノミー/ターム/` の場合
          $query_args = [
            'post_type'      => 'jav',
            'posts_per_page' => 2,
            'paged'          => $paged,
            'tax_query'      => [
              [
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $wp_query->query_vars['term'],
              ]
            ]
          ];
          $base_url = untrailingslashit(get_term_link($wp_query->query_vars['term'], $taxonomy));
        } else {
          // `/jav/タクソノミー/` の場合
          echo '何かしらのテンプレートや関数化した何かを読み込むようにする';
        }
      } elseif (is_post_type_archive('jav')) {
        // `/jav/` の場合
        $query_args = [
          'post_type'      => 'jav',
          'posts_per_page' => 2,
          'paged'          => $paged
        ];
        $base_url = untrailingslashit(get_pagenum_link(1));
      } else {
        // その他のケース
      }

      // `get_card_loop()` を実行
      get_card_loop($query_args);

      // WP_Query の max_num_pages を取得
      // $custom_query = new WP_Query($query_args);
      // $total_pages = (isset($custom_query) && $custom_query->max_num_pages) ? intval($custom_query->max_num_pages) : 1;
      // wp_reset_postdata();
      ?>
    </div>
  </section>
  <?php include get_template_directory() . '/assets/inc/parts/list__pagination.php'; ?>
</main>
<aside class="sidebar__layout">
  <?php get_template_part('assets/inc/parts/popular-tags'); ?>
  <?php get_template_part('assets/inc/parts/search-by-criteria'); ?>
</aside>
<?php get_footer(); ?>