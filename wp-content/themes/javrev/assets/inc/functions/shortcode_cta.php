<?php

function render_affiliate_cta() {
  // 関数を呼び出してデータを取得
  $affiliate_info = get_affiliate_info();

  ob_start(); // 出力をバッファリング
  ?>
  <div class="cta__small">
    <div class="inner-layout layout__article">
      <div class="btn__normal pri iconright max-width--small margin-inline-auto fixedsizenormall--pc">
        <a href="<?php echo esc_url($affiliate_info['aff_url']); ?>" target="_blank" rel="noopener" class="icon__normal before external-link white" aria-label="<?php echo esc_attr($affiliate_info['btn_aria']); ?>">
          <?php echo esc_html($affiliate_info['btn_text']); ?>
        </a>
      </div>
      <div class="details js--acmenu">
        <div class="click">
          <p class="text icon__normal after add white"><?php echo esc_html($affiliate_info['membership_info']); ?></p>
        </div>
        <?php if ($affiliate_info['selected_site']) : ?>
          <div class="js--acmenu-content">
            <div class="btn__normal cta twoline iconright max-width--small margin-inline-auto fixedsizenormall--pc">
              <a href="<?php echo esc_url($affiliate_info['main_url']); ?>" target="_blank" rel="noopener" class="icon__normal before external-link white fixedsizenormall--pc" aria-label="<?php echo esc_attr($affiliate_info['btn_aria']); ?>">
                <span class="twolinelarge fixedsize--pc"><?php echo esc_html(lang("aff.{$affiliate_info['selected_site']}-btn-cta-text")); ?></span>
                <span class="twolinesmall fixedsize--pc"><?php echo esc_html(lang("aff.{$affiliate_info['selected_site']}-btn-cta-lang")); ?></span>
              </a>
            </div>
            <?php
            // サブリンクの動的生成（sub1, sub2, sub3, ...）
            $sub_index = 1;
            while (true) {
              $sub_url = lang("aff.{$affiliate_info['selected_site']}-url-sub{$sub_index}");
              $sub_text = lang("aff.{$affiliate_info['selected_site']}-text-sub{$sub_index}");
              $sub_aria = lang("aff.{$affiliate_info['selected_site']}-text-sub{$sub_index}--aria");

              // subX が存在しなかったらループを終了
              if (empty($sub_url)) break;
            ?>
              <a href="<?php echo esc_url($sub_url); ?>" class="linkother icon__normal after external-link gray inline-block" aria-label="<?php echo esc_attr($sub_aria); ?>"><?php echo esc_html($sub_text); ?></a>
            <?php
              $sub_index++; // 次の subX へ
            }
            ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php
  return ob_get_clean(); // バッファの内容を返す
}

// ショートコードを登録
function aff_cta_shortcode() {
  return render_affiliate_cta();
}
add_shortcode('aff_cta', 'aff_cta_shortcode');

?>