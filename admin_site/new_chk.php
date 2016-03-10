<?php
  session_start(); //セッション開始

//サニタイジング
  foreach($_POST as $key => $val):
    $_POST[$key] = htmlspecialchars($val,ENT_QUOTES,"UTF-8");
  endforeach;

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
  $_SESSION['title'] = $_POST['title'];
  $_SESSION['news'] = $_POST['news'];

//画像有無判定
  $img_flag=null;  //img_flag初期化（有:yes 無:no）
  if($_FILES['img_file']['name'][0]===''):
    $img_flag='no';  //画像無セット
  else:
    $img_flag='yes';  //画像有セット
    
//画像ファイル数取得
    $img_count=count($_FILES['img_file']['name']);

//画像ファイルチェック
    for($i=0;$i<$img_count;$i++):

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

//セッション関数に画像の有無をセット
  $_SESSION['img_flag']=$img_flag;
  $img_flag=null;

//エラーありの場合はリダイレクト
  if(is_array($err_msg)):
    $_SESSION['errors']=$err_msg;
    header('Location: ./new_init.php');
  endif;

  require_once("./header.php"); //ヘッダー呼び出し
 ?>

        <section id="window_name">
          <p>新規投稿確認</p>
        </section>
    </header>

    <main>
      <p>タイトル</p>
      <p><?php print $_POST["title"]; ?></p>
      <p>記事</p>
      <p><?php print $_POST["news"]; ?></p>
      <p>画像ファイル：<?php ($_SESSION['img_flag']==='yes') ? print '有' : print '無'; ?></p>
      <table border="1" cellpadding="5" bordercolor="black" cellspacing="0">
        <tr>
          <?php
            if(isset($_SESSION['img_flag'])):
              if($_SESSION['img_flag']==='yes'):
                for($i=0;$i<$img_count;$i++): 
//ファイル名と拡張子を取得
//                  $file_obj=new SplFileInfo($_SESSION['img_file'][$i]);
//                  $fileName=$file_obj->getBasename();
//                  $ext=$file_obj->getExtension();
//使用web-svにてSplFileInfo使用不可pathinfoに変更 2016.03.10
                  $pathinfo=pathinfo($_SESSION['img_file'][$i]);
                  $fileName=$pathinfo['basename'];
                  $ext=$pathinfo['extension'];

          ?>
          <td>
            <img width="128px" height="128px" alt="添付画像" src="show_image.php?img=<?php print $fileName; ?>&ext=<?php print $ext;?>&flag=tmp">
          </td>
        <?php endfor;endif;endif; ?>
      </table>
      <br>
      <button onclick="location.href='./new_exec.php'">投稿</button>
    </main>
    <footer>
      <section id=footer_cont>
        <button onclick="location.href='./new_init.php'">入力をやり直す</button>
      </section>
    </footer>
  </body>
</html>

