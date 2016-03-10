<?php
  session_start(); //セッション開始

//POST変数->$news_id
  $news_id=$_POST['news_id'];

//DB処理 

//オブジェクト $dbh作成
  require_once(__DIR__.'/../lib/access_db.php');

//news table SQLコマンド 準備
  $sql_cmd='SELECT * FROM news WHERE news_id='.$news_id;

//DB読込処理 news
  $news_rec=$dbh-> query($sql_cmd);

//images table SQLコマンド 準備
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
          <p>削除確認</p>
        </section>
    </header>

    <main>
        <?php
          foreach($news_rec as $val):
            $_SESSION['news_id']=$val['news_id'];
            $_SESSION['img_flag']=$val['img_flag'];
        ?>
        <p class="char-red">※記事を削除する場合は<strong>削除ボタン</strong>をクリックして下さい</p>
        <p>記事no:<strong><?php print $val['news_id']; ?></strong></p>
        <p><label>タイトル</label></p>
        <p><?php print $val['title']; ?></p>
        <p>記事内容</p>
        <p><?php print $val['news']; ?></p>
        <p>画像ファイル：<?php ($val['img_flag']==='yes') ? print '有' : print '無'; ?></p>
        <table border="1" cellpadding="5" bordercolor="black" cellspacing="0">
          <tr>
            <?php
              endforeach;
              $img_count_del=0;
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
                  $_SESSION['del'][]=$val['img_id'].','.$val['image_path'];
            ?>
            <td>
              <img width="128px" height="128px" alt="添付画像" src="show_image.php?img=<?php print $fileName; ?>&ext=<?php print $ext;?>&flag=img">
            </td>
            <?php
                  $img_count_del=$img_count_del+1;
                endforeach;
              endif;
            ?>
          </tr>
        </table>
        <br>
        <button onclick="location.href='./delete_exec.php'">削除</button>
    </main>
    <footer>
      <section id=footer_cont>
        <button onclick="location.href='./update_return_index.php'">削除を中止する</button>
      </section>
    </footer>
  </body>
</html>

