<?php
  require_once(__DIR__.'/../lib/access_db.php');
  $sql_cmd='SELECT * FROM news ORDER BY update_time DESC LIMIT 10';
  $stmt=$dbh->query($sql_cmd);
  $dbh=null;

  require_once(__DIR__.'/header.php');
?>

        <section id="window_name">
          <p>投稿一覧</p>
        </section>
    </header>

    <main>
      <button onclick="location.href='./new_input.php'">新規投稿する</button>
      <p class="char-red">記事を変更/削除する場合は下記一覧から</p>
      <table border="1" cellspacing="0" cellpadding="5">
        <tr>
          <th>記事No</th>
          <th>投稿日時</th>
          <th>タイトル</th>
          <th>記事の処理</th>
        </tr>
        <?php
          foreach($stmt as $val):
        ?>
        <tr>
          <td><?php print $val['news_id']; ?></td>
          <td><?php print $val['update_time']; ?></td>
          <td><?php print $val['title']; ?></td>
          <td>
            <form action="update.php" method="post">
              <input type="hidden" name="news_id" value=<?php print $val['news_id']; ?>>
              <input type="submit" value="変更">
            </form>
            <form action="delete.php" method="post">
              <input type="hidden" name="news_id" value=<?php print $val['news_id']; ?>>
              <input type="submit" value="削除">
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>
      
    </main>
    <footer>
      <section id=footer_cont>

      </section>
    </footer>
  </body>
</html>
