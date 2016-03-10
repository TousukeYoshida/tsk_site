<?php
  session_start(); //セッション開始

/* debug用 
  echo '<pre>';
  var_dump($_POST['news_id']);
  echo '</pre>';
  echo '<pre>';
  var_dump($_POST['title']);
  echo '</pre>';
  echo '<pre>';
  var_dump($_POST['news']);
  echo '</pre>';
  echo '<pre>';
  var_dump($_POST['del']);
  echo '</pre>';
  echo '<pre>';
  var_dump($_POST['img_count_old']);
  echo '</pre>';
  echo '<pre>';
  var_dump($_FILES['img_file']);
  echo '</pre>';
  echo '<pre>';
  var_dump($_POST);
  echo '</pre>';
*/

//サニタイジング
  foreach($_POST as $key => $val):
    (is_array($_POST[$key])) ? : $_POST[$key] = htmlspecialchars($val,ENT_QUOTES,"UTF-8");
  endforeach;

/* title,news input check start */

//文字列の最初と最後の空白を取り除く
  $_POST['title']=trim($_POST['title']);
  $_POST['news']=trim($_POST['news']);

//未入力、空白文字列チェック
  $err_msg=null;
  foreach($_POST as $key=>$val):
    if($val==false):
      if($key==='title'){
        $err_msg[$key]='タイトルがありません';
      }elseif($key==='news'){
        $err_msg[$key]='記事がありません';
      }
    endif;
  endforeach;

// セッションにデータを保存
  $_SESSION['news_id'] = $_POST['news_id'];
  $_SESSION['title'] = $_POST['title'];
  $_SESSION['news'] = $_POST['news'];
  $_SESSION['img_count_old'] = $_POST['img_count_old'];

/* title,news input check end */

/* add image check start */
  if($_FILES['img_file']['name'][0]===''):
    $img_count_add=0;
  else:
    $img_count_add=count($_FILES['img_file']['name']);

//画像ファイルチェック
    for($i=0;$i<$img_count_add;$i++):

//$_FILES -> 変数に代入
      $name=$_FILES['img_file']['name'][$i];
      $tmp_name=$_FILES['img_file']['tmp_name'][$i];
      
//画像の拡張子->$chk_ext
//      $file_obj=new SplFileInfo($name);
//      $chk_ext=$file_obj->getExtension();
//使用web-svにてSplFileInfo使用不可pathinfoに変更 2016.03.10
      $pathinfo=pathinfo($name);
      $chk_ext=$pathinfo['extension'];

//拡張子より画像ファイルを判定
      if($chk_ext==='jpg' || $chk_ext==='jpeg' || $chk_ext==='gif' || $chk_ext==='png'):

//tmpファイルの拡張子を除いたファイル名を取得
//        $file_obj=new SplFileInfo($tmp_name);
//        $file_base=$file_obj->getBasename('.tmp');
//使用web-svにてSplFileInfo使用不可pathinfoに変更 2016.03.10
        $pathinfo=pathinfo($tmp_name);
        $file_base=$pathinfo['filename'];

//保存するファイル名をセット
        $dstFile=$file_base.'.'.$chk_ext;

//移動先パスつきファイル名をセット
        $dstFile=__DIR__.'/../upload_tmp/'.$dstFile;

//tmpファイルをupload_tmpフォルダに移動
        move_uploaded_file($tmp_name,$dstFile);


//移動後の画像ファイルのパスをセッションに格納
        $_SESSION['img_file'][$i] = $dstFile;

//エラーメッセージ格納
      else:
        $err_msg[$label]='許可された画像ファイルではありません';
      endif;
    endfor;
  endif;

//セッション関数に追加画像の数をセット
  $_SESSION['img_count_add']=$img_count_add;

/* add image check end */

/* del image check start */

  if(isset($_POST['del'])):
    $img_count_del=count($_POST['del']);
    $_SESSION['del']=$_POST['del'];
  else:
    $img_count_del=0;
    $_SESSION['del']=NULL;
  endif;
  $_SESSION['img_count_del']=$img_count_del;
  
/* del image check end */

//エラーありの場合はリダイレクト
  if(is_array($err_msg)):
    $_SESSION['errors']=$err_msg;
    header('Location: ./update_init.php');
  endif;




?>

<!-- ヘッダー呼び出し -->
<?php require_once("./header.php"); ?>

        <section id="window_name">
          <p>投稿変更確認</p>
        </section>
    </header>

    <main>
      <table border="1">
        <tr><th>記事No</th><td><?php print $_SESSION['news_id']; ?></td></tr>
        <tr><th>タイトル</th><td><?php print $_SESSION['title']; ?></td></tr>
        <tr><th>記事内容</th><td><pre><?php print $_SESSION['news']; ?></pre></td></tr>
        <tr>
          <th>削除画像:<?php print $img_count_del; ?>個</th>
      
        <?php
          for($i=0;$i<$img_count_del;$i++):
            $img_data=explode(',', $_POST['del'][$i]);
            $file=pathinfo($img_data[1]);
        ?>
          <td>
            <img width="128px" height="128px" src="show_image.php?img=<?php print $file['basename']; ?>&ext=<?php print $file['extension']; ?>&flag=img">
          </td>
          <?php endfor; ?>
        </tr>
        <tr>
          <th>追加画像：<?php print $img_count_add; ?>個</th>
          <?php for($i=0;$i<$img_count_add;$i++):
            $file=pathinfo($_SESSION['img_file'][$i]);
          ?>
          <td>
            <img width="128px" height="128px" src="show_image.php?img=<?php print $file['basename']; ?>&ext=<?php print $file['extension']; ?>&flag=tmp">
          </td>
          <?php endfor; ?>
        </tr>
      </table>
      <br>
      <button onclick="location.href='./update_exec.php'">変更する</button>
    </main>
    <footer>
      <section id=footer_cont>
        <button onclick="location.href='./update_init.php'">投稿変更画面に戻る。</button>
      </section>
    </footer>
  </body>
</html>

