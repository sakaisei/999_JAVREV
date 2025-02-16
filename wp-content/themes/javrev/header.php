<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header class="gnav__modal">
  <div class="inner-layout">
    <div class="gnav__logo">
      <a href="/">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/common/logo.svg" alt="JAVREV" width="220" height="43">
        <span class="lang"><?php echo lang('common.logo'); ?></span>
      </a>
    </div>
    <button class="gnav__hamburger" aria-label="グローバルナビゲーションを表示">
      <div class="border"></div>
    </button>
    <nav class="gnav__nav" aria-label="グローバルナビゲーション">
      <a href="/" class="logo" aria-label="トップページへ戻る">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/common/logo.svg" alt="JAVREV" width="220" height="43">
        <span class="lang"><?php echo lang('common.logo'); ?></span>
      </a>
      <ul class="mainnav">
        <li class="filtering btn">
          <a href="/filtering/" class="icon__normal before filter pri">好きな条件で探す</a>
        </li>
        <li class="tag btn">
          <a href="/tag/" class="icon__normal before tag pri">好きなタグで探す</a>
        </li>
        <li class="search">
          <a href="/search/" class="icon__normal before search pri">キーワード検索</a>
        </li>
        <li class="video-hub btn">
          <a href="/video-hub/" class="icon__normal before video cta">動画の提供元を探す</a>
        </li>
        <li class="review">
          <a href="/video/">最新レビュー</a>
        </li>
        <li class="news">
          <a href="#">お知らせ<span class="time">2024.11.27</span><span class="new">NEW</span></a>
        </li>
        <li class="lang">
          <button class="acmenu" aria-label="言語メニューを開く" aria-expanded="false">
            <span class="text">Language</span>
          </button>
          <?php
            $languages = apply_filters('wpml_active_languages', null, 'skip_missing=0');
            if (!empty($languages)) {
              echo '<ul class="custom-language-switcher lang-menu">';
              foreach ($languages as $language) {
                echo '<li>';
                echo '<a href="' . esc_url($language['url']) . '">';
                echo '<img src="' . esc_url($language['country_flag_url']) . '" alt="' . esc_attr($language['native_name']) . ' flag" style="width:20px; height:auto; margin-right:5px;">';
                echo esc_html($language['native_name']);
                echo '</a>';
                echo '</li>';
              }
              echo '</ul>';
            }
          ?>
        </li>
      </ul>
    </nav>
    <button class="gnav__searchbtn" aria-label="サブナビゲーションを表示">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/img/common/icon-search.svg" alt="検索アイコン" class="hidden__visually" width="24" height="24">
    </button>
    <nav class="gnav__search" aria-label="サブナビゲーション">
      <div class="layouttop">
        <ul class="btn__query gnav">
          <li>
            <a href="/filtering/" class="icon__normal before filter white">好きな条件で探す</a>
          </li>
          <li>
            <a href="/tag/" class="icon__normal before tag white">好きなタグで探す</a>
          </li>
        </ul>
        <button class="closebtn" aria-label="サブナビゲーションを閉じる"></button>
      </div>
      <div class="layoutbottom" aria-label="サイト内を検索">
        <?php
          set_query_var('search_form_class', 'gnav');
          get_template_part('assets/inc/parts/search-form');
        ?>
      </div>
    </nav>
    <div class="gnav__bg"></div>
  </div>
</header>