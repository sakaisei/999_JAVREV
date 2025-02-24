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
        <h1 class="ttl">404</h1>
        <p class="text">各descriptionが挿入されます。</p>
      </div>







      <div class="content">



<?php

// 404.php内でのデバッグコード例
if (is_404()) {
  error_log('404ページが表示されました');
  error_log('リクエストURL: ' . $_SERVER['REQUEST_URI']);
  error_log('クエリ: ' . print_r($wp_query, true));
}

?>



        

        







      </div>
  </section>
  <?php //get_template_part('assets/inc/parts/list__pagination'); 
  ?>
</main>
<aside class="sidebar__layout">
  <?php get_template_part('assets/inc/parts/popular-tags'); ?>
  <?php get_template_part('assets/inc/parts/search-by-criteria'); ?>
</aside>
<?php get_footer(); ?>