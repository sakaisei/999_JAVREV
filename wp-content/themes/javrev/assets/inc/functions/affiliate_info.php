<?php

function get_affiliate_info()
{
  // ACFの値を取得
  $aff_url = get_field('acf_aff_url');
  $selected_site = get_field('acf_aff_site');

  // ラジオボタンの「表示ラベル（label）」を取得
  $field = get_field_object('acf_aff_site');
  $selected_label = $field['choices'][$selected_site] ?? '';

  // 料金プランを動的に取得
  $pricing_list = [];
  $index = 1;
  while (true) {
    $dl = lang("aff.{$selected_site}-pricing-dt{$index}");
    $dd = lang("aff.{$selected_site}-pricing-dd{$index}");
    $class = lang("aff.{$selected_site}-pricing-dd{$index}--class");

    // 料金プランが存在しない場合はループを終了
    if (empty($dl) || empty($dd)) break;

    // 配列に格納（クラスがない場合は空文字）
    $pricing_list[] = [
      'label' => $dl,
      'price' => $dd,
      'class' => !empty($class) ? esc_attr($class) : ''
    ];

    $index++; // 次のプランへ
  }

  // 料金プランの詳細リンクを動的に取得
  $pricing_links = [];
  $index = 1;
  while (true) {
    $url = lang("aff.{$selected_site}-pricing-url{$index}");
    $text = lang("aff.{$selected_site}-pricing-text{$index}");

    // URL または テキストが存在しない場合はループ終了
    if (empty($url) || empty($text)) break;

    $pricing_links[] = ['url' => esc_url($url), 'text' => esc_html($text)];

    $index++; // 次のリンクへ
  }

  // 特典（benefits）の取得
  $benefits_list = [];
  $index = 1;
  while (true) {
    $benefit = lang("aff.{$selected_site}-benefit-{$index}");

    // 特典が存在しない場合はループを終了
    if (empty($benefit)) break;

    $benefits_list[] = esc_html($benefit);
    $index++;
  }

  // メインCTAボタンの情報を取得
  $cta_main = [
    'url'  => lang("aff.{$selected_site}-btn-cta-url"),
    'text' => lang("aff.{$selected_site}-btn-cta-text"),
    'lang' => lang("aff.{$selected_site}-btn-cta-lang"),
    'aria' => lang("aff.{$selected_site}-btn-cta--aria")
  ];

  // **CTAサブリンクの取得**（ここが修正点）
  $cta_sub_links = [];
  $index = 1;
  while (true) {
    $sub_url  = lang("aff.{$selected_site}-url-sub{$index}");
    $sub_text = lang("aff.{$selected_site}-text-sub{$index}");
    $sub_aria = lang("aff.{$selected_site}-text-sub{$index}--aria");

    // URLとテキストがない場合は終了
    if (empty($sub_url) || empty($sub_text)) break;

    $cta_sub_links[] = [
      'url'  => esc_url($sub_url),
      'text' => esc_html($sub_text),
      'aria' => esc_attr($sub_aria)
    ];
    $index++;
  }

  // asterisk（補足情報）の取得
  $asterisk_list = [];
  $index = 1;
  while (true) {
    $asterisk = lang("aff.{$selected_site}-asterisk-{$index}");

    // asterisk が存在しない場合はループを終了
    if (empty($asterisk)) break;

    $asterisk_list[] = esc_html($asterisk);
    $index++;
  }

  // 言語データを取得（必須のため、デフォルト値は不要）
  return [
    'aff_url' => $aff_url,
    'selected_site' => $selected_site,
    'selected_label' => $selected_label,
    // ショートコードのためにArticle.キーを追加
    'btn_text' => lang('article.btn-sumple-video'),
    'btn_aria' => lang('article.btn-sumple-video--aria'),
    'membership_info' => lang('article.btn-membership-info'),
    // aff.キーを追加
    'main_url' => lang("aff.{$selected_site}-url-main"),
    'logo_src' => lang("aff.{$selected_site}-logo-src"),
    'provider_description' => lang("aff.{$selected_site}-provider-description"),
    'pricing_list' => $pricing_list, // 料金プランを追加
    'pricing_links' => $pricing_links, // 料金プラン詳細リンクを追加
    'benefits_list' => $benefits_list, // 特典リストを追加
    'cta_main' => $cta_main, // メインCTAボタン
    'cta_sub_links' => $cta_sub_links, // サブリンク（入会案内）
    'asterisk_list' => $asterisk_list, // asterisk（補足情報）を追加
  ];
}
