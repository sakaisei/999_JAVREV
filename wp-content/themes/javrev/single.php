<?php get_header(); ?>
<?php
// 関数を呼び出してデータを取得
$affiliate_info = get_affiliate_info();
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
              <?php
              // 翻訳グループ内の「いいね」数を表示
              //display_likes_by_translation_group();
              ?>
            </div>
            <div class="btn__small good"><?php // echo do_shortcode('[wp_ulike]'); 
                                          ?></div>
            <div class="btn__small view"><?php // echo do_shortcode('[post-views]'); 
                                          ?></div>
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
        <div class="list__metabox">
          <div class="inner-layout">
            <h2 class="ttlmetasmall icon__normal before gray-light video mb0"><?php echo lang('article.meta-sec-ttl'); ?></h2>
          </div>
        </div>
        <div class="list__metabox">
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
        <div class="list__metabox">
          <div class="inner-layout">
            <h4 class="ttlmetasmall"><?php echo lang('article.meta-video-info'); ?></h4>
            <nav class="tagwrap" aria-label="<?php echo lang('article.meta-video-info-tags'); ?>">
              <?php
              // 投稿のカスタムタクソノミータームを取得する関数
              function get_taxonomy_terms_list($post_id, $taxonomy)
              {
                $terms = get_the_terms($post_id, $taxonomy);
                if ($terms && !is_wp_error($terms)) {
                  return array_map(function ($term) {
                    return [
                      'name' => esc_html($term->name),
                      'slug' => esc_attr($term->slug),
                      'url' => get_term_link($term) // タームのURL
                    ];
                  }, $terms);
                }
                return [];
              }

              // 投稿IDを取得
              $post_id = get_the_ID();

              // 3つのカスタムタクソノミーのタームを取得
              $censor_terms = get_taxonomy_terms_list($post_id, 'censor'); // 検閲・規制
              $format_terms = get_taxonomy_terms_list($post_id, 'format'); // 映像形式
              $playtime_terms = get_taxonomy_terms_list($post_id, 'playtime'); // 再生時間

              // すべてのタームを結合（表示順: censor → format → playtime）
              $all_terms = array_merge($censor_terms, $format_terms, $playtime_terms);
              ?>

              <?php if (!empty($all_terms)) : ?>
                <ul class="btn__tag fixedsize--pc">
                  <?php foreach ($all_terms as $term) : ?>
                    <li><a href="<?php echo esc_url($term['url']); ?>" class="tag tag-<?php echo $term['slug']; ?>"><?php echo $term['name']; ?></a></li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
            </nav>
            <?php
            // ACF から再生時間を取得（例: "01:00:20" または "00:45:00"）
            $playtime = get_field('acf_playtime');

            if ($playtime) {
              // 時間・分・秒を分解
              list($hours, $minutes, $seconds) = explode(':', $playtime);

              $hours = (int)$hours;
              $minutes = (int)$minutes;
              $seconds = (int)$seconds;

              // `datetime` 用 ISO 8601 フォーマット（PTxxHxxMxxS）
              $iso_playtime = 'PT';
              if ($hours > 0) $iso_playtime .= "{$hours}H";
              if ($minutes > 0) $iso_playtime .= "{$minutes}M";
              if ($seconds > 0) $iso_playtime .= "{$seconds}S";

              // もし全てゼロだったら、デフォルトで `PT0S` にする
              if ($iso_playtime === 'PT') {
                $iso_playtime = 'PT0S';
              }

              // `HH:MM:SS` 形式で統一
              $localized_playtime = sprintf("%d:%02d:%02d", $hours, $minutes, $seconds);
            }
            ?>
            <time class="playtime" datetime="<?php echo esc_attr($iso_playtime); ?>"><?php echo lang('article.meta-playtime'); ?> : <?php echo esc_html($localized_playtime); ?></time>
          </div>
        </div>
        <div class="list__metabox">
          <div class="inner-layout">
            <h4 class="ttlmetasmall"><?php echo lang('article.meta-cast'); ?></h4>
            <nav class="tagwrap" aria-label="<?php echo lang('article.meta-cast-list--aria'); ?>">
              <ul class="btn__tag fixedsize--pc">
                <?php
                // 出演者（cast）のタームを取得
                $terms = get_the_terms(get_the_ID(), 'cast');
                if ($terms && !is_wp_error($terms)) :
                  foreach ($terms as $term) :
                    $term_link = get_term_link($term);
                ?>
                    <li><a href="<?php echo esc_url($term_link); ?>" class="tag"><?php echo esc_html($term->name); ?></a></li>
                <?php
                  endforeach;
                endif;
                ?>
              </ul>
            </nav>
          </div>
        </div>
        <div class="list__metabox">
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
        <div class="list__metabox">
          <div class="inner-layout">
            <h4 class="ttlmetasmall"><?php echo lang('article.meta-tags'); ?></h4>
            <nav class="tagwrap" aria-label="<?php echo lang('article.meta-tags--aria'); ?>">
              <?php
              // 対象のカスタムタクソノミー
              $taxonomies = ['genre', 'outfit', 'girl', 'guy', 'body', 'rel', 'scene', 'play'];
              // 空の配列を用意して、タームを格納
              $terms_list = [];
              foreach ($taxonomies as $taxonomy) {
                // 投稿に紐づいているタームを取得
                $terms = get_the_terms(get_the_ID(), $taxonomy);
                // 取得できた場合、リストに追加
                if ($terms && !is_wp_error($terms)) {
                  foreach ($terms as $term) {
                    $terms_list[] = [
                      'name' => $term->name,
                      'slug' => $term->slug,
                      'link' => get_term_link($term)
                    ];
                  }
                }
              }
              // タームが存在する場合のみ出力
              if (!empty($terms_list)) :
              ?>
                <ul class="btn__tag fixedsize--pc">
                  <?php foreach ($terms_list as $term) : ?>
                    <li><a href="<?php echo esc_url($term['link']); ?>" class="tag"><?php echo esc_html($term['name']); ?></a></li>
                  <?php endforeach; ?>
                </ul>
              <?php endif; ?>
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


        <!-- the_content 中身 後で記事に入力-->
        <!--<section class="sec">
          <h2>吾輩は猫である</h2>
          <p>吾輩わがはいは猫である。名前はまだ無い。どこで生れたかとんと見当けんとうがつかぬ。何でも薄暗いじめじめした所でニャーニャー泣いていた事だけは記憶している。</p>
          <section class="conversation">
            <div class="dialog">
              <figure>
                <span class="iconwrap">
                  <img src="/img/common/icon-actor.png" alt="男優のアイコン">
                </span>
                <figcaption>男優</figcaption>
              </figure>
              <p>リリちゃん素敵です！めちゃくちゃ可愛い発言連発ですね！</p>
            </div>
            <div class="dialog">
              <figure>
                <span class="iconwrap">
                  <img src="/img/0/1/icon.jpg" alt="リリちゃんのアイコン">
                </span>
                <figcaption>リリちゃん</figcaption>
              </figure>
              <p>わー！ありがとう😊</p>
            </div>
          </section>
          <p>吾輩はここで始めて人間というものを見た。しかもあとで聞くとそれは書生という人間中で一番獰悪どうあくな種族であったそうだ。この書生というのは時々我々を捕つかまえて煮にて食うという話である。</p>
          <div class="img js--contain-img">
            <img src="/img/0/1/7.jpg" alt="">
          </div>
          [aff_cta]
        </section>
        <section class="sec">
          <h2>しかしその当時は何という考もなかったから別段恐しい</h2>
          <p>ただ彼の掌てのひらに載せられてスーと持ち上げられた時何だかフワフワした感じがあったばかりである。掌の上で少し落ちついて書生の顔を見たのがいわゆる人間というものの見始みはじめであろう。</p>
          <section class="conversation">
            <div class="dialog">
              <figure>
                <span class="iconwrap">
                  <img src="/img/common/icon-actor.png" alt="男優のアイコン">
                </span>
                <figcaption>男優</figcaption>
              </figure>
              <p>どーしてそんなに可愛いの？？笑</p>
            </div>
            <div class="dialog">
              <figure>
                <span class="iconwrap">
                  <img src="/img/0/1/icon.jpg" alt="リリちゃんのアイコン">
                </span>
                <figcaption>リリちゃん</figcaption>
              </figure>
              <p>可愛いですか…？<br>なんでしょーね🤔</p>
            </div>
            <div class="dialog">
              <figure>
                <span class="iconwrap">
                  <img src="/img/common/icon-actor.png" alt="男優のアイコン">
                </span>
                <figcaption>男優</figcaption>
              </figure>
              <p>自覚なしかよッ！笑笑笑</p>
            </div>
            <div class="dialog">
              <figure>
                <span class="iconwrap">
                  <img src="/img/0/1/icon.jpg" alt="リリちゃんのアイコン">
                </span>
                <figcaption>リリちゃん</figcaption>
              </figure>
              <p>そういうお兄さんも格好良いです😊❤️</p>
            </div>
            <div class="dialog">
              <figure>
                <span class="iconwrap">
                  <img src="/img/common/icon-actor.png" alt="男優のアイコン">
                </span>
                <figcaption>男優</figcaption>
              </figure>
              <p>ぐはッ……！！！</p>
            </div>
          </section>
          <p>ただ彼の掌てのひらに載せられてスーと持ち上げられた時何だかフワフワした感じがあったばかりである。掌の上で少し落ちついて書生の顔を見たのがいわゆる人間というものの見始みはじめであろう。</p>
          <div class="img col2 js--contain-img">
            <figure>
              <img src="/img/0/1/9.jpg" alt="">
              <figcaption>キャプションは必ず存在します</figcaption>
            </figure>
            <figure>
              <img src="/img/0/1/5.jpg" alt="">
              <figcaption>キャプションは必ず存在します</figcaption>
            </figure>
          </div>
        </section>-->
        <!-- END the_content 中身 後で記事に入力-->



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