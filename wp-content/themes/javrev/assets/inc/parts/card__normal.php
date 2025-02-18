<?php
// カードのバージョン（例：small）
$class = $args['class'] ?? '';
// 動画の再生時間を取得
$playtime_data = get_playtime(get_the_ID());
// 定義したtaxonomy_dataを取得
$taxonomy_data  = $args['taxonomy_data'] ?? [];
// 任意のタクソノミーループを作成
$ordered_taxonomies = ['censor', 'play', 'scene', 'rel', 'body', 'girl', 'guy', 'outfit', 'genre', 'cast'];
?>
<article class="card__normal <?php echo esc_attr($class); ?>">
  <a href="<?php the_permalink(); ?>" target="_blank" class="link">
    <div class="mainsliderwrap">
      <div class="swiper mainslider">
        <div class="swiper-wrapper">
          <?php get_slider_images('mainslider'); ?>
        </div>
        <?php if (!empty($taxonomy_data['format'])) : ?>
          <div class="quality">
            <ul class="btn__tag radius-first-last">
              <?php foreach ($taxonomy_data['format'] as $term) : ?>
                <li class="tag black alpha"><?php echo esc_html($term['name']); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
        <div class="playtime">
          <ul class="btn__tag radius-first-last">
            <li class="tag black alpha pointer-events-none" data-duration="<?php echo esc_attr($playtime_data['iso']); ?>">
              <?php echo esc_html($playtime_data['formatted']); ?>
            </li>
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
          <span class="average-score"><?php echo esc_html(get_field('acf_video_rating')); ?></span>
        </div>
        <time datetime="<?php echo get_the_date('c'); ?>">
          <?php echo date_i18n(get_option('date_format'), strtotime(get_the_date())); ?>
        </time>
      </div>
      <h3 class="ttl"><?php the_title(); ?></h3>
      <div class="tmbsliderwrap">
        <div class="swiper tmbslider">
          <div class="swiper-wrapper">
            <?php get_slider_images('tmbslider'); ?>
          </div>
        </div>
      </div>
      <nav class="tagwrap" aria-label="タグリスト">
        <ul class="btn__tagtext large">
          <?php foreach ($ordered_taxonomies as $taxonomy) : ?>
            <?php if (!empty($taxonomy_data[$taxonomy])) : ?>
              <?php foreach ($taxonomy_data[$taxonomy] as $term) : ?>
                <li><?php echo esc_html($term['name']); ?></li>
              <?php endforeach; ?>
            <?php endif; ?>
          <?php endforeach; ?>
        </ul>
      </nav>
      <div class="btn__normal pri w87--sp">
        <button class="btn" type="button" onclick="location.href='#'">もっと見る</button>
      </div>
    </div>
  </a>
</article>