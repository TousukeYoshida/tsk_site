<?php
  session_start();
  
//debug start
  echo '<pre>';
  print '<p>img_flag='.$_SESSION['img_flag'].'</p>';
  print '<p>title='.$_SESSION['title'].'</p>';
  print '<p>news='.$_SESSION['news'].'</p>';
  echo '</pre>';

  echo '<pre>';
  if ($_SESSION['img_flag']==='yes'):
    foreach($_SESSION['img_file'] as $val):
      var_dump($val);
    endforeach;
  endif;
  echo '</pre>';
//debug end

//SESSION変数から各変数に代入
$img_flag=$_SESSION['img_flag'];
$title=$_SESSION['title'];
$news=$_SESSION['news'];

//DB書込処理 

//オブジェクト $dbh作成
  require_once(__DIR__.'/../lib/access_db.php');

/* newsテーブル書込み処理 start */
  echo '<p>***news table 処理 start***</p>';

//SQLコマンド
  $sql_cmd='INSERT INTO news (create_time,update_time,title,news,img_flag) VALUES(now(),now(),:title,:news,:img_f)';

//execute用配列
  $args=array(
    'title' => $title,
    'news' => $news,
    'img_f' => $img_flag
  );

//DB処理
  $stmt=null;
  $stmt=$dbh-> prepare($sql_cmd);
  $stmt->execute($args);
//インサートしたIDを取得
  $news_id= $dbh->lastInsertID();

//変数初期化
  $sql_cmd=null;
  $args=null;
  
  echo '<pre>';
  var_dump($news_id);
  echo '</pre>';
  echo '<p>***news table 処理 end***</p>';
/* newsテーブル書込み処理 end */

//image file 処理
  if ($img_flag==='yes'):
    $dstDir=__DIR__.'/../upload_img/';
    foreach($_SESSION['img_file'] as $key => $val):
      $srcFile= $val;
//      $file_obj=new SplFileInfo($val);
//      $fileName=$file_obj->getBasename();
//使用web-svにてSplFileInfo使用不可pathinfoに変更 2016.03.10
      $pathinfo=pathinfo($val);
      $fileName=$pathinfo['basename'];
      $dstFile=$dstDir.$fileName;

//      print '<p>'.$srcFile.'</p>'; //debug
//      print '<p>'.$dstFile.'</p>'; //debug
      

// image files move upload_tmp -> upload_img
      rename($srcFile,$dstFile);
      echo '<p>***imageFile'.$key.'move end***</p>';

// DB処理 imagesテーブル
      $sql_cmd='INSERT INTO images (image_path,news_id) VALUES(:path,:id)';

//execute用配列
      $args=array(
        'path' => $dstFile,
        'id' => $news_id,
      );

  echo '<pre>';
  var_dump($args);
  echo '</pre>';

//DB処理
      $stmt=null;
      $stmt=$dbh-> prepare($sql_cmd);
      $stmt->execute($args);

//変数初期化
      $sql_cmd=null;
      $args=null;

    endforeach;
  endif;


//処理結果画面へリダイレクト
  $_SESSION['msg']='投稿しました';
  header('Location:./result.php');

?>
<!-- debug用 
<button onclick="location.href='./result.php'">ＯＫ</button>
-->
