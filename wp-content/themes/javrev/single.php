<?php get_header(); ?>
<?php
// AFFの関数を呼び出してデータを取得
$affiliate_info = get_affiliate_info();
// 動画の再生時間を取得
$playtime_data = get_playtime(get_the_ID());
// 現在の投稿IDのタクソノミーを取得
$taxonomy_data = get_post_taxonomies_and_terms(get_the_ID());
// 任意のタクソノミーループを作成
$ordered_taxonomies = ['censor', 'format', 'playtime'];
// 任意のタクソノミーループを作成　2
$ordered_taxonomies2 = ['censor', 'play', 'scene', 'rel', 'body', 'girl', 'guy', 'outfit', 'genre', 'cast'];
?>
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
            <h1 class="ttl"><?php echo esc_html(get_the_title()); ?></h1>
            <p class="lead"><?php echo nl2br(esc_html(get_field('acf_lead_text'))); ?></p>
            <!-- <p class="rating icon__normal before users good">この記事を84%の人が高評価！</p> -->
          </div>
        </div>
      </div>
      <?php echo do_shortcode('[aff_cta]'); ?>
      <?php
      $article_no = get_field('acf_article_no'); // 記事番号
      $image_count = get_field('acf_slide_count'); // 画像の総枚数
      $image_alt_texts = explode("\n", get_field('acf_slide_alt')); // 改行で区切って配列化

      // 画像の総数とaltテキストの数が一致しない場合、altを無効化
      if (count($image_alt_texts) !== (int)$image_count) {
        $image_alt_texts = array_fill(0, $image_count, ''); // 全てのaltを空文字に
        // 管理者のみエラーメッセージを表示
        if (is_user_logged_in() && current_user_can('manage_options')) {
          echo '<div style="width: 220px;height: 150px;position: fixed;right: 20px;bottom: 20px; background: red;color: white;display: flex;align-items: center; justify-content: center;padding: 20px;font-size: 13px;line-height: 1.5; box-shadow: 0 2px 10px rgba(0,0,0,0.3);z-index: 9999;">
          記事NO：' . esc_html($article_no) . '<br>
          画像とaltの数が一致しません。
          </div>';
        }
      }

      // 記事番号から画像ディレクトリのパスを生成
      $image_directory = "/img/" . floor($article_no / 100) . "/" . $article_no . "/";

      if ($article_no && $image_count > 0):
      ?>
        <div class="swiper__fv inner-layout">
          <div class="swiper article__mainslider" aria-label="<?php echo lang('article.main-slider--aria'); ?>">
            <div class="swiper-wrapper">
              <?php for ($i = 1; $i <= $image_count; $i++):
                $image_path = $image_directory . $i . '.jpg'; // 画像パス
                if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $image_path)) continue; // 画像が存在しない場合はスキップ
              ?>
                <div class="swiper-slide js--contain-img">
                  <img src="<?php echo esc_url($image_path); ?>" alt="<?php echo esc_attr($image_alt_texts[$i - 1] ?? ''); ?>" loading="lazy">
                </div>
              <?php endfor; ?>
            </div>
            <div class="swiper-pagination-counter">
              <span class="swiper-pagination-current">1</span> / <span class="swiper-pagination-total">0</span>
            </div>
          </div>
          <div class="swiper article__tmbslider" aria-label="<?php echo lang('article.tmb-slider--aria'); ?>">
            <div class="swiper-wrapper">
              <?php for ($i = 1; $i <= $image_count; $i++):
                $image_path = $image_directory . $i . '.jpg'; // 画像パス
                if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $image_path)) continue; // 画像が存在しない場合はスキップ
              ?>
                <div class="swiper-slide js--contain-img">
                  <img src="<?php echo esc_url($image_path); ?>" alt="<?php echo esc_attr($image_alt_texts[$i - 1] ?? ''); ?>" aria-hidden="true" loading="lazy">
                </div>
              <?php endfor; ?>
            </div>
          </div>
          <div class="swiper-button-prev article__mainslider-prev"></div>
          <div class="swiper-button-next article__mainslider-next"></div>
        </div>
      <?php endif; ?>
      <section class="list__meta">
        <?php
        // global $wp_query;
        // echo '<pre>';
        // echo 'Template: single.php' . PHP_EOL;
        // echo 'is_single: ' . (is_single() ? 'true' : 'false') . PHP_EOL;
        // echo 'is_singular: ' . (is_singular() ? 'true' : 'false') . PHP_EOL;
        // echo 'Post Type: ' . get_post_type() . PHP_EOL;
        // echo 'Query Vars: ';
        // print_r($wp_query->query_vars);
        // echo '</pre>';
        ?>


        <div class="list__metabox list__metabox-overview">
          <div class="inner-layout">
            <h2 class="ttlmetasmall icon__normal before gray-light video mb0"><?php echo lang('article.meta-sec-ttl'); ?></h2>
          </div>
        </div>
        <div class="list__metabox list__metabox-title">
          <div class="inner-layout">
            <div class="collayoutwrap">
              <div class="collayout js--applyleftwidth">
                <div class="left"><?php echo lang('article.meta-title'); ?></div>
                <div class="right">
                  <h2 class="ttlmetalarge"><?php echo esc_html(get_field('acf_video_title')); ?></h2>
                </div>
              </div>
              <dl class="collayout js--getleftwidth">
                <dt class="left"><?php echo lang('article.meta-rating'); ?></dt>
                <dd class="right">
                  <div class="icon__ratesmall">
                    <div class="stars">
                      <span class="star star-filled"></span>
                    </div>
                    <span class="average-score"><?php echo esc_html(get_field('acf_video_rating')); ?></span>
                  </div>
                  <span class="textrate"><?php echo lang('article.meta-rating-source'); ?></span>
                </dd>
                <dt class="left"><?php echo lang('article.meta-provider'); ?></dt>
                <dd class="right">
                  <a href="<?php echo esc_url($affiliate_info['main_url']); ?>" class="provider-link">
                    <img src="<?php echo esc_url(get_template_directory_uri() . $affiliate_info['logo_src']); ?>" alt="<?php echo esc_attr($affiliate_info['selected_label']); ?>" class="provider-logo">
                  </a>
                  <p class="icon__helpwrap texthelp js--tooltip">
                    <?php echo lang('article.provider-info'); ?>
                    <button type="button" class="icon" aria-label="<?php echo lang('article.provider-info--aria'); ?>"></button>
                    <span class="tooltip">
                      <?php echo ($affiliate_info['provider_description']); ?>
                      <button type="button" class="tooltip-close" aria-label="<?php echo lang('article.tooltip-close--aria'); ?>"></button>
                    </span>
                  </p>
                </dd>
              </dl>
            </div>
            <script>
              const copyMessages = {
                success: "<?php echo lang('article.copy-success'); ?>",
                error: "<?php echo lang('article.copy-error'); ?>"
              };
            </script>
            <?php
            // 固定フィールドのタイトルを取得
            $video_titles = [
              'ja' => get_field('acf_video_title_ja'),
              'en' => get_field('acf_video_title_en')
            ];

            foreach ($video_titles as $lang_code => $title) :
              if (!empty($title)) :
            ?>
                <div class="ttl-localization js--copy">
                  <span class="language-label" lang="<?php echo esc_attr($lang_code); ?>">
                    <?php echo lang("article.language-label-{$lang_code}"); ?>
                  </span>
                  <h3 class="ttl-localized js--copybtn icon__normal copy after gray-3 small"
                    aria-label="<?php echo esc_html($title); ?>"
                    lang="<?php echo esc_attr($lang_code); ?>">
                    <?php echo esc_html($title); ?>
                  </h3>
                  <div class="toast__content js--copycontent"></div>
                </div>
            <?php
              endif;
            endforeach;
            ?>
          </div>
        </div>
        <div class="list__metabox list__metabox-videoinfo">
          <div class="inner-layout">
            <h4 class="ttlmetasmall"><?php echo lang('article.meta-video-info'); ?></h4>
            <nav class="tagwrap" aria-label="<?php echo lang('article.meta-video-info-tags'); ?>">
              <ul class="btn__tag fixedsize--pc">
                <?php foreach ($ordered_taxonomies as $taxonomy) : ?>
                  <?php if (!empty($taxonomy_data[$taxonomy])) : ?>
                    <?php foreach ($taxonomy_data[$taxonomy] as $term) : ?>
                      <li><a href="<?php echo esc_url($term['link']); ?>" class="tag tag-<?php echo esc_html($term['slug']); ?>"><?php echo esc_html($term['name']); ?></a></li>
                    <?php endforeach; ?>
                  <?php endif; ?>
                <?php endforeach; ?>
              </ul>
            </nav>
            <time class="playtime" datetime="<?php echo esc_attr($playtime_data['iso']); ?>">
              <?php echo lang('article.meta-playtime'); ?> : <?php echo esc_html($playtime_data['formatted']); ?>
            </time>
          </div>
        </div>
        <div class="list__metabox .list__metabox-cast">
          <div class="inner-layout">
            <h4 class="ttlmetasmall"><?php echo lang('article.meta-cast'); ?></h4>
            <nav class="tagwrap" aria-label="<?php echo lang('article.meta-cast-list--aria'); ?>">
              <ul class="btn__tag fixedsize--pc">
                <?php foreach ($taxonomy_data['cast'] as $term) : ?>
                  <li>
                    <a href="<?php echo esc_url($term['link']); ?>" class="tag"><?php echo esc_html($term['name']); ?></a>
                  </li>
                <?php endforeach; ?>
              </ul>
            </nav>
          </div>
        </div>
        <div class="list__metabox list__metabox-price">
          <div class="inner-layout">
            <h4 class="ttlmetasmall"><?php echo lang('article.meta-pricing'); ?></h4>
            <?php if (!empty($affiliate_info['pricing_list'])) : ?>
              <dl class="list__dlcolon">
                <?php foreach ($affiliate_info['pricing_list'] as $pricing) : ?>
                  <dt><?php echo esc_html($pricing['label']); ?></dt>
                  <dd<?php echo !empty($pricing['class']) ? ' class="' . $pricing['class'] . '"' : ''; ?>><?php echo esc_html($pricing['price']); ?></dd>
                  <?php endforeach; ?>
              </dl>
            <?php endif; ?>
            <?php if (!empty($affiliate_info['pricing_links'])) : ?>
              <?php foreach ($affiliate_info['pricing_links'] as $link) : ?>
                <p class="price">
                  <a href="<?php echo esc_url($link['url']); ?>" target="_blank" class="icon__normal after external-link gray-3" rel="noopener">
                    <?php echo esc_html($link['text']); ?>
                  </a>
                </p>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
        <div class="list__metabox list__metabox-tags">
          <div class="inner-layout">
            <h4 class="ttlmetasmall"><?php echo lang('article.meta-tags'); ?></h4>
            <nav class="tagwrap" aria-label="<?php echo lang('article.meta-tags--aria'); ?>">
              <ul class="btn__tag fixedsize--pc">
                <?php foreach ($ordered_taxonomies2 as $taxonomy) : ?>
                  <?php if (!empty($taxonomy_data[$taxonomy])) : ?>
                    <?php foreach ($taxonomy_data[$taxonomy] as $term) : ?>
                      <li>
                        <a href="<?php echo esc_url($term['link']); ?>" class="tag"><?php echo esc_html($term['name']); ?></a>
                      </li>
                    <?php endforeach; ?>
                  <?php endif; ?>
                <?php endforeach; ?>
              </ul>
            </nav>
          </div>
        </div>
      </section>
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
    <footer class="article__footer layout__padding">
      <div class="cta__large inner-layout layout__article">
        <div class="ttllayout">
          <h2><?php echo lang('article.footer-heading'); ?></h2>
          <ul>
            <li class="logo-dev">
              <img src="<?php echo esc_url(get_template_directory_uri() . $affiliate_info['logo_src']); ?>" alt="<?php echo esc_attr($affiliate_info['selected_label']); ?>">
            </li>
            <li class="logo-javrev">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/img/common/logo.svg" alt="JAVREV">
            </li>
          </ul>
        </div>
        <?php if (!empty($affiliate_info['benefits_list'])) : ?>
          <ul class="list__benefits">
            <?php foreach ($affiliate_info['benefits_list'] as $benefit) : ?>
              <li><?php echo $benefit; ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
        <div class="highlighttext">
          <p><?php echo lang('article.footer-highlighttext-1'); ?></p>
          <p><?php echo lang('article.footer-highlighttext-2'); ?></p>
        </div>
        <?php if (!empty($affiliate_info['cta_main'])) : ?>
          <div class="btn__normal cta twoline js--acmenu-content iconright max-width--normal margin-inline-auto fixedsizenormall--pc">
            <a href="<?php echo esc_url($affiliate_info['cta_main']['url']); ?>" target="_blank" rel="noopener" class="icon__normal before external-link white fixedsizenormall--pc" aria-label="<?php echo esc_attr($affiliate_info['cta_main']['aria']); ?>">
              <span class="twolinelarge fixedsize--pc"><?php echo esc_html($affiliate_info['cta_main']['text']); ?></span>
              <span class="twolinesmall fixedsize--pc"><?php echo esc_html($affiliate_info['cta_main']['lang']); ?></span>
            </a>
          </div>
        <?php endif; ?>
        <?php if (!empty($affiliate_info['cta_sub_links'])) : ?>
          <?php foreach ($affiliate_info['cta_sub_links'] as $sub_link) : ?>
            <a href="<?php echo esc_url($sub_link['url']); ?>" class="linkother icon__normal after external-link gray inline-block" aria-label="<?php echo esc_attr($sub_link['aria']); ?>">
              <?php echo esc_html($sub_link['text']); ?>
            </a>
          <?php endforeach; ?>
        <?php endif; ?>
        <div class="box">
          <h3 class="ctaboxlogo logo-dev">
            <img src="<?php echo esc_url(get_template_directory_uri() . $affiliate_info['logo_src']); ?>" alt="<?php echo esc_attr($affiliate_info['selected_label']); ?>">
          </h3>
          <p><?php echo lang('aff.caribbeancom-provider-description'); ?></p>
        </div>
        <?php if (!empty($affiliate_info['asterisk_list'])) : ?>
          <ul class="list__asterisk gray">
            <?php foreach ($affiliate_info['asterisk_list'] as $asterisk) : ?>
              <li><?php echo esc_html($asterisk); ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>
    </footer>
  </article>
  <?php get_template_part('assets/inc/parts/list__postnav'); ?>
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