<?php
  session_start(); //セッション開始

//POST変数->$news_id
  $news_id=(isset($_SESSION['news_id'])) ? $_SESSION['news_id'] : $_POST['news_id'];

//DB処理 

//オブジェクト $dbh作成
  require_once(__DIR__.'/../lib/access_db.php');

//SQLコマンド 準備
  $sql_cmd='SELECT * FROM news WHERE news_id='.$news_id;

//DB読込処理 news
  $news_rec=$dbh-> query($sql_cmd);

//SQLコマンド 準備
  $sql_cmd='SELECT * FROM images WHERE news_id='.$news_id;
//DB読込処理 images
  $images_rec=$dbh-> query($sql_cmd);

/* debug用
  echo '<pre>';
  var_dump($news_rec);
  echo '</pre>';
  echo '<pre>';
  var_dump($images_rec);
  echo '</pre>';
*/
?>

<!-- ヘッダー呼び出し -->
  <?php require_once("./header.php"); ?>

        <section id="window_name">
          <p>投稿変更</p>
        </section>
    </header>

    <main>
<!-- エラー表示 -->
  <?php require_once("./error_display.php"); ?> 
      <form action="update_chk.php" method="post" enctype="multipart/form-data">
        <?php
          foreach($news_rec as $val):
        ?>
        <p>記事no:<strong><?php (isset($_SESSION['new_id'])) ? print $_SESSION['news_id'] : print $val['news_id']; ?></strong></p>
        <input type="hidden" name="news_id" value=<?php print $val['news_id']; ?>>
        <p class="char-red">※記事を変更する場合は各項目を修正し、<strong>記事を変更するボタン</strong>をクリックして下さい</p>

        <p><label>タイトル</label></p>
        <input type="text" name="title" size="100" value=<?php (isset($_SESSION['title'])) ? print $_SESSION['title'] : print $val['title']; ?> required>
        <p>記事</p>
        <textarea cols="80" rows="10" name="news" required><?php (isset($_SESSION['news'])) ? print $_SESSION['news'] : print $val['news']; ?></textarea>

        <p>画像ファイル：<?php ($val['img_flag']==='yes') ? print '有' : print '無'; ?></p>
        <p class="char-red">※画像を削除する場合は各画像の削除にチェックを入れる</p>
        <table border="1" cellpadding="5" bordercolor="black" cellspacing="0">
          <tr>
            <?php
              endforeach;
              $img_count_old=0;
              if ($val['img_flag']==='yes'):
                foreach($images_rec as $val):

//ファイル名と拡張子を取得
//                  $file_obj=new SplFileInfo($val['image_path']);
//                  $fileName=$file_obj->getBasename();
//                  $ext=$file_obj->getExtension();
//使用web-svにてSplFileInfo使用不可pathinfoに変更 2016.03.10
                  $pathinfo=pathinfo($val['image_path']);
                  $fileName=$pathinfo['basename'];
                  $ext=$pathinfo['extension'];
            ?>
            <td>
              <img width="128px" height="128px" alt="添付画像" src="show_image.php?img=<?php print $fileName; ?>&ext=<?php print $ext;?>&flag=img">
              <input type="checkbox" name="del[]" value=<?php print $val['img_id'].','.$val['image_path']; ?>>削除
            </td>
            <?php
                  $img_count_old=$img_count_old+1;
                endforeach;
              endif;
            ?>
          </tr>
        </table>
        <input type="hidden" name="img_count_old" value=<?php print $img_count_old; ?>>
        <p>画像ファイルを添付する場合は下記ボタンをクリックしてください</p>
        <input class="sel_files" type="file" name="img_file[]" multiple>
        <br>

        <br>
        <input type="submit" name="submit" value="記事を変更する">
      </form>
    </main>
    <footer>
      <section id=footer_cont>
        <button onclick="location.href='./return_index.php'">変更を中止する</button>
      </section>
    </footer>
  </body>
</html>

