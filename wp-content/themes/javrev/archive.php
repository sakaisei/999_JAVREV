<?php get_header(); ?>
<?php
if (function_exists('yoast_breadcrumb')) {
  yoast_breadcrumb('<nav class="list__breadcrumb margin-top">', '</nav>');
}
?>
<main class="main__common">
  <section class="contents">
    <div class="inner-layout layout__normal layout__padding">
      <div class="ttl__layout">
        <?php
        global $wp_query;
        echo '<h1 class="ttl">';
        if (isset($wp_query->query_vars['taxonomy']) && !empty($wp_query->query_vars['taxonomy'])) {
          // タクソノミー関連のリクエスト
          $taxonomy = $wp_query->query_vars['taxonomy'];
          $term = isset($wp_query->query_vars['term']) ? $wp_query->query_vars['term'] : '';

          if (!empty($term)) {
            // タームが指定されている場合
            echo esc_html(single_term_title('', false)); // ターム名を取得して表示
          } else {
            // タームが指定されていないタクソノミーページ
            echo esc_html(get_taxonomy($taxonomy)->label); // タクソノミー名を取得して表示
          }
        } elseif (is_post_type_archive('jav')) {
          // カスタム投稿タイプ「JAV」のアーカイブページ
          echo 'ALL Reviews'; // 固定文言
        } else {
          // その他のアーカイブページ
          echo 'アーカイブページ';
        }
        echo '</h1>';
        ?>
        <p class="text">各descriptionが挿入されます。</p>








        <p>
          <?php

if (is_archive()) {
    error_log('✅ is_archive() detected!');
}

if (is_post_type_archive('jav')) {
    error_log('✅ is_post_type_archive("jav") detected!');
}

if (is_tax('format')) {
    error_log('✅ is_tax("format") detected!');
}

error_log('Current Query Vars: ' . print_r($wp_query->query_vars, true));

          ?>
        </p>





      </div>
      <?php get_template_part('assets/inc/parts/btn__query'); ?>
      <div class="card__normalwrap">
        <?php get_template_part('assets/inc/dev/card1'); ?>
        <?php get_template_part('assets/inc/dev/card2'); ?>
        <?php get_template_part('assets/inc/dev/card3'); ?>
        <?php get_template_part('assets/inc/dev/card4'); ?>
        <?php get_template_part('assets/inc/dev/card5'); ?>
        <?php get_template_part('assets/inc/dev/card6'); ?>
      </div>
    </div>
  </section>
  <?php get_template_part('assets/inc/parts/list__pagination'); ?>
</main>
<aside class="sidebar__layout">
  <?php get_template_part('assets/inc/parts/popular-tags'); ?>
  <?php get_template_part('assets/inc/parts/search-by-criteria'); ?>
</aside>
<?php get_footer(); ?>