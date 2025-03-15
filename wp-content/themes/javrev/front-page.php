<?php get_header(); ?>
<main class="main__common">
  <section class="fv">
    <div class="inner-layout">
      <div class="mainttl">
        <h1>有償動画作品を<br>徹底レビューしました！</h1>
        <p>人気動画を真面目にレビューしました。</p>
      </div>
      <ul class="btnlayout">
        <li class="btn__normal pri">
          <a href="" class="icon__normal filter white">好きな条件で探す</a>
        </li>
        <li class="btn__normal cta">
          <a href="" class="icon__normal video white">おすすめ提供元</a>
        </li>
      </ul>
    </div>
    <div class="inner-layout2">
      <p class="text">広告フリーで快適な視聴体験をお楽しみください！</p>
      <div class="iconr18">R18</div>
      <div class="iconjr">
        <span class="hidden__visually">JR JAVREV</span>
      </div>
    </div>
    <div class="fvbglayer"></div>
    <div class="newslayout">
      <ul>
        <li>
          <a href="">
            <time datetime="">2024年11月3日</time>
            <span class="newtag">NEW</span>
            <span class="newstext">○○○○○が半額キャンペーン実施中！この機会をお見逃し無く！</span>
          </a>
        </li>
      </ul>
    </div>
  </section>
  <section class="contents">
    <div class="inner-layout layout__normal layout__padding">
      <div class="ttl__layout">
        <h2 class="ttl">ALL Reviews</h2>
        <p class="text">日本動画を真面目にレビューしました。</p>
        <?php
        // global $wp_query;
        // echo '<pre style="font-size:13px">';
        // //echo 'Template: archive.php' . PHP_EOL;
        // echo 'is_archive: ' . (is_archive() ? 'true' : 'false') . PHP_EOL;
        // echo 'is_tax: ' . (is_tax() ? 'true' : 'false') . PHP_EOL;
        // echo 'is_category: ' . (is_category() ? 'true' : 'false') . PHP_EOL;
        // echo 'is_tag: ' . (is_tag() ? 'true' : 'false') . PHP_EOL;
        // echo 'is_post_type_archive: ' . (is_post_type_archive() ? 'true' : 'false') . PHP_EOL;
        // echo 'Query Vars: ';
        // print_r($wp_query->query_vars);
        // echo '</pre>';
        ?>
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
    </div>
  </section>
  <?php // get_template_part('assets/inc/parts/list__pagination'); ?>
</main>
<aside class="sidebar__layout">
  <?php get_template_part('assets/inc/parts/popular-tags'); ?>
  <?php get_template_part('assets/inc/parts/search-by-criteria'); ?>
</aside>
<?php get_footer(); ?>