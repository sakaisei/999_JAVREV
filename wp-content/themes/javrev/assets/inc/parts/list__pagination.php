<?php
// 現在のページ番号を取得
$paged = max(1, get_query_var('paged'));

// クエリオブジェクトを取得（メインクエリまたはカスタムクエリ）
global $wp_query;
$total_pages = isset($wp_query->max_num_pages) ? $wp_query->max_num_pages : 1;

// ページネーションのベースURLを設定
$base_url = get_pagenum_link(1);

if ($total_pages > 1) : ?>
  <nav class="list__pagination layout__sub" aria-label="ページナビゲーション">
    <div class="inner-layout layout__normal">
      <ul class="pages">
        <?php
        // **前のページ**
        if ($paged > 1) {
          echo '<li class="btnprev"><a href="' . trailingslashit($base_url) . 'page/' . ($paged - 1) . '/" class="prev">前のページ</a></li>';
        } else {
          echo '<li class="btnprev"><span class="prev is--disabled">前のページ</span></li>';
        }

        // **ページナビゲーション**
        $pagination_links = paginate_links([
          'base'      => trailingslashit($base_url) . 'page/%#%/',
          'format'    => 'page/%#%/',
          'current'   => $paged,
          'total'     => $total_pages,
          'mid_size'  => 2,
          'end_size'  => 1,
          'prev_next' => false,
          'type'      => 'array',
        ]);

        if (!empty($pagination_links)) {
          foreach ($pagination_links as $link) {
            if (strpos($link, 'current') !== false) {
              echo '<li><span class="num is--current">' . strip_tags($link) . '</span></li>';
            } elseif (strpos($link, '…') !== false) {
              echo '<li><span class="pageitem-ellipsis">…</span></li>';
            } else {
              echo '<li>' . str_replace('<a', '<a class="num"', $link) . '</li>';
            }
          }
        }

        // **次のページ**
        if ($paged < $total_pages) {
          echo '<li class="btnnext"><a href="' . trailingslashit($base_url) . 'page/' . ($paged + 1) . '/" class="next">次のページ</a></li>';
        } else {
          echo '<li class="btnnext"><span class="next is--disabled">次のページ</span></li>';
        }
        ?>
      </ul>
    </div>
  </nav>
<?php endif; ?>