<footer class="footer__global">
  <div class="inner-layout">
    <a href="/" class="logolayout">
      <div class="footer__logo">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/common/logo.svg" alt="JAVREV">
        <span class="lang"><?php echo lang('common.logo'); ?></span>
      </div>
      <p class="footer__lead">さあ、動画の世界を体験しよう</p>
    </a>
    <nav class="footer__listwrap" aria-label="フッターナビゲーション">
      <ul class="footer__list">
        <li><a href="/privacy-policy/">プライバシーポリシー</a></li>
        <li><a href="/terms-of-use/">利用規約</a></li>
        <li><a href="/sitemap/">サイトマップ</a></li>
        <li><a href="/contact/">お問い合わせ</a></li>
      </ul>
    </nav>
    <p class="footer__copyright">&copy; <?php echo date('Y'); ?> javrev.info</p>
  </div>
</footer>
<?php wp_footer();?>
<style>
  #query-monitor-main:not(.qm-show-right),
  .otgs-development-site-front-end {
    display: none !important;
  }
</style>
</body>
</html>
<?php// get_template_part('assets/inc/dev/dev'); ?>