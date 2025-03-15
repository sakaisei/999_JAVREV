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
      <div class="ttl__layout layout__marginbottom">
        <h1 class="ttl">Error 404</h1>
        <p class="text">指定のURLは存在しません。<br>ページが削除されたか、URLが変更になった可能性があります。</p>
      </div>
      <?php get_template_part('assets/inc/parts/btn__query'); ?>
      <?php
      get_card_loop([
        'post_type'      => 'jav',
        'posts_per_page' => 6,
        'orderby'        => 'date',
        'order'          => 'DESC',
      ]);
      ?>
  </section>
</main>
<aside class="sidebar__layout">
  <?php get_template_part('assets/inc/parts/popular-tags'); ?>
  <?php get_template_part('assets/inc/parts/search-by-criteria'); ?>
</aside>
<?php get_footer(); ?>