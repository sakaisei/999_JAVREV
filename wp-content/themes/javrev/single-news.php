<?php get_header(); ?>
<?php
if (function_exists('yoast_breadcrumb')) {
  yoast_breadcrumb('<nav class="list__breadcrumb margin-top">', '</nav>');
}
?>
<main class="main__common">
  <article class="article__wrap">
    <header class="article__header">
      <div class="ttl__article">
        <div class="inner-layout layout__article">
          <div class="stats">
            <div class="btn__small good">
              <!--検証中、本番環境で検証する-->
              <?php
              // 翻訳グループ内の「いいね」数を表示
              //display_likes_by_translation_group();
              ?>
            </div>
            <div class="btn__small good"><?php // echo do_shortcode('[wp_ulike]'); 
                                          ?></div>
            <div class="btn__small view"><?php // echo do_shortcode('[post-views]'); 
                                          ?></div>
            <!--END 検証中、本番環境で検証する-->
            <time datetime="<?php echo get_the_date('c'); ?>">
              <?php echo date_i18n(get_option('date_format'), strtotime(get_the_date())); ?>
            </time>
          </div>
          <div class="ttlblock">
            <h1 class="ttl border-bottom"><?php echo esc_html(get_the_title()); ?></h1>
          </div>
        </div>
      </div>
    </header>
    <section class="article__the_contents">
      <div class="inner-layout layout__article">
        <?php if (have_posts()) : ?>
          <?php while (have_posts()) : the_post(); ?>
            <?php the_content(); ?>
          <?php endwhile; ?>
        <?php endif; ?>
      </div>
    </section>
  </article>
</main>
<aside class="sidebar__layout">
  <div class="inner-layout layout__normal layout__padding">
    <div class="ttl__layout mb1em">
      <h2 class="ttl">関連記事</h2>
      <p class="text">この記事に関連する内容をピックアップしました。</p>
    </div>
    <div class="card__normalwrap col2">
      <?php get_template_part('assets/inc/dev/card1small'); ?>
      <?php get_template_part('assets/inc/dev/card2small'); ?>
      <?php get_template_part('assets/inc/dev/card3small'); ?>
      <?php get_template_part('assets/inc/dev/card4small'); ?>
      <?php get_template_part('assets/inc/dev/card5small'); ?>
      <?php get_template_part('assets/inc/dev/card6small'); ?>
      <?php get_template_part('assets/inc/dev/card1small'); ?>
      <?php get_template_part('assets/inc/dev/card2small'); ?>
      <?php get_template_part('assets/inc/dev/card3small'); ?>
      <?php get_template_part('assets/inc/dev/card4small'); ?>
      <?php get_template_part('assets/inc/dev/card5small'); ?>
      <?php get_template_part('assets/inc/dev/card6small'); ?>
    </div>
  </div>
  <?php get_template_part('assets/inc/parts/popular-tags'); ?>
  <?php get_template_part('assets/inc/parts/search-by-criteria'); ?>
</aside>
<?php get_footer(); ?>