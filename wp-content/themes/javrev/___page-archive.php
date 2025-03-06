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
          <a href="" class="icon__normal before filter white">好きな条件で探す</a>
        </li>
        <li class="btn__normal cta">
          <a href="" class="icon__normal before video white">おすすめ提供元</a>
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
      </div>
      <ul class="btn__query gray-light">
        <li>
          <a href="/filtering/" class="icon__normal before filter gray-light">好きな条件で探す</a>
        </li>
        <li>
          <a href="/tag/" class="icon__normal before tag gray-light">好きなタグで探す</a>
        </li>
        <li>
          <a href="/search/" class="icon__normal before search gray-light">キーワード検索</a>
        </li>
      </ul>
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
  <nav class="list__pagination layout__sub" aria-label="ページナビゲーション">
    <div class="inner-layout layout__normal">
      <ul class="pages">
        <li class="btnprev"><span class="prev is--disabled">前のページ</span></li>
        <li><span class="num is--current">1</span></li>
        <li><a href="/page/2/" class="num">2</a></li>
        <li><a href="/page/3/" class="num">3</a></li>
        <li><a href="/page/4/" class="num">4</a></li>
        <li><a href="/page/5/" class="num">5</a></li>
        <li><a href="/page/6/" class="num">6</a></li>
        <li><span class="pageitem-ellipsis">…</span></li>
        <li><a href="/page/89" class="num">89</a></li>
        <li class="btnnext"><a href="/page/2/" class="next">次のページ</a></li>
      </ul>
    </div>
  </nav>
</main>

<aside class="sidebar__layout">
  <?php get_template_part('assets/inc/parts/popular-tags'); ?>
  <?php get_template_part('assets/inc/parts/search-by-criteria'); ?>
</aside>
<?php get_footer(); ?>