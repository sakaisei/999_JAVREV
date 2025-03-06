<?php get_header(); ?>
<?php
if (function_exists('yoast_breadcrumb')) {
  yoast_breadcrumb('<nav class="list__breadcrumb margin-top">', '</nav>');
}
?>
<main class="main__common">
  <section class="fv">
    
  </section>
</main>

<aside class="sidebar__layout">
  <?php get_template_part('assets/inc/parts/popular-tags'); ?>
  <?php get_template_part('assets/inc/parts/search-by-criteria'); ?>
</aside>
<?php get_footer(); ?>