<article class="card__normal">
  <a href="#" class="link">
    <div class="mainsliderwrap">
      <div class="swiper mainslider">
        <div class="swiper-wrapper">
          <div class="swiper-slide mainslider-slide">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/dev/2.jpg" alt="画像3">
          </div>
          <div class="swiper-slide mainslider-slide">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/dev/7.jpg" alt="画像2">
          </div>
          <div class="swiper-slide mainslider-slide">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/dev/4.jpg" alt="画像3">
          </div>
        </div>
        <div class="quality">
          <ul class="btn__tag radius-first-last">
            <li class="tag black alpha">HD</li>
          </ul>
        </div>
        <div class="playtime">
          <ul class="btn__tag radius-first-last">
            <li class="tag black alpha pointer-events-none" data-duration="PT1H26M12S">1時間26分</li>
          </ul>
        </div>
      </div>
    </div>
    <div class="inner-layout-card">
      <div class="layoutcol2">
        <div class="icon__ratesmall large">
          <div class="stars">
            <span class="star star-filled"></span>
          </div>
          <span class="average-score">3.6</span>
        </div>
        <time datetime="<?php echo get_the_date('c'); ?>">
          <?php echo date_i18n(get_option('date_format'), strtotime(get_the_date())); ?>
        </time>
      </div>
      <h3 class="ttl">ロックマンX8が最終作品で次の作品が出ない件について。</h3>
      <div class="tmbsliderwrap">
        <div class="swiper tmbslider">
          <div class="swiper-wrapper">
            <div class="swiper-slide tmbslider-slide">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/img/dev/2.jpg" alt="画像3">
            </div>
            <div class="swiper-slide tmbslider-slide">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/img/dev/7.jpg" alt="画像2">
            </div>
            <div class="swiper-slide tmbslider-slide">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/img/dev/4.jpg" alt="画像3">
            </div>
          </div>
        </div>
      </div>
      <nav class="tagwrap" aria-label="タグリスト">
        <ul class="btn__tagtext large">
          <li>炎上</li>
          <li>芸能人</li>
          <li>美人アスリート</li>
        </ul>
      </nav>
      <div class="btn__normal pri w87--sp">
        <button class="btn" type="button" onclick="location.href='#'">もっと見る</button>
      </div>
    </div>
  </a>
</article>