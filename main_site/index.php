<?php
  require_once(__DIR__.'/../lib/access_db.php');
//SQLコマンド準備
  $sql_cmd='SELECT * from news ORDER BY update_time DESC  LIMIT 10';
//SQL実行
  $stmt=$dbh->query($sql_cmd);
//DB-obj clear
  $dbh=null;
  
?>

<?php require_once(__DIR__.'/header.php'); //header読込 ?>
<?php require_once(__DIR__.'/navi.php'); //navi読込 ?>

      <section id="top_image">
        
      </section>
    <main>
      <div id="blog-back" class="b-shadow bc-linen">
        <p class="f-1_5em">新着記事</p>
        <p class="char-red">記事Noをクリックすると詳細が表示されます</p>
        <div id="blog-box">
          <table border="3" cellspacing="0" cellpadding="5" boder-color="#4169e1" width="100%">
            <tr>
              <th>記事No</th>
              <th>投稿日時</th>
              <th>タイトル</th>
            </tr>
            <?php foreach($stmt as $val): ?>
            <tr>
              <td><?php print $val['news_id']; ?></td>
              <td><?php print $val['update_time']; ?></td>
              <td><?php print $val['title']; ?></td>
            </tr>
            <?php endforeach; ?>
          </table>
        </div>
      </div>
    </main>
    <footer>
      <section id=footer_cont>
      </section>
    </footer>
  </body>

</html>

