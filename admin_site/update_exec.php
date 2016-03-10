<?php
  session_start();
  
//debug start
  echo '<pre>';
  print '<p>news_id</p>';
  var_dump($_SESSION['news_id']);
  print '<p>title</p>';
  var_dump($_SESSION['title']);
  print '<p>news</p>';
  var_dump($_SESSION['news']);
  echo '</pre>';

  echo '<pre>';
  if ($_SESSION['img_count_add']!==0):
    foreach($_SESSION['img_file'] as $val):
      var_dump($val);
    endforeach;
  endif;
  echo '</pre>';
  
  echo '<pre>';
  if ($_SESSION['img_count_del']!==0):
    foreach($_SESSION['del'] as $val):
      var_dump($val);
    endforeach;
  endif;
  echo '</pre>';
  
//debug end

//SESSION変数から各変数に代入
  $news_id=$_SESSION['news_id'];
  $title=$_SESSION['title'];
  $news=$_SESSION['news'];
  $img_count_old=$_SESSION['img_count_old'];
  $img_count_add=$_SESSION['img_count_add'];
  $img_count_del=$_SESSION['img_count_del'];
  
  $img_chk=$img_count_old - $img_count_del + $img_count_add;
  ($img_chk===0) ? $img_flag='no' : $img_flag='yes' ;

//DB書込処理 

//オブジェクト $dbh作成
  require_once(__DIR__.'/../lib/access_db.php');

/* newsテーブルupdate処理 start */
  echo '<p>***news table 処理 start***</p>';

//SQLコマンド
  $sql_cmd='UPDATE news SET update_time=now(),title=:title,news=:news,img_flag=:img_f WHERE news_id=:news_id';

//execute用配列
  $args=array(
    'title' => $title,
    'news' => $news,
    'img_f' => $img_flag,
    'news_id' => $news_id
  );

//DB処理
  $stmt=null;
  $stmt=$dbh-> prepare($sql_cmd);
  $stmt->execute($args);

//変数初期化
  $sql_cmd=null;
  $args=null;
  
  echo '<pre>';
  echo '<p>$news_id</p>';
  var_dump($news_id);
  echo '</pre>';
  echo '<p>***news table 処理 end***</p>';
/* newsテーブルupdate処理 end */

/* imagesテーブル書込処理 start */
//add image file 処理 start
  if ($img_count_add!==0):
    $dstDir=__DIR__.'/../upload_img/';
    foreach($_SESSION['img_file'] as $key => $val):
      $srcFile= $val;
//      $file_obj=new SplFileInfo($val);
//      $filename=$file_obj->getBasename();
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
      echo '<p>***image file'.$key.'added ***</p>';

//変数初期化
      $sql_cmd=null;
      $args=null;

    endforeach;
  endif;

//*** add image file 処理 end ***

//*** del image file 処理 start ***

  if ($img_count_del!==0):
    foreach($_SESSION['del'] as $key => $val):
      $img_data=explode(',', $val);
      $img_id=$img_data[0];
      $img_path=$img_data[1];

// DB処理 imagesテーブル delete
      $sql_cmd='DELETE FROM images WHERE img_id=:id';

//execute用配列
      $args=array(
        'id' => $img_id
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

//ファイル削除
      unlink($img_path);
      
    endforeach;
  endif;

//*** del image file 処理 end ***


/* imagesテーブル書込処理 end */


//処理結果画面へリダイレクト
  $_SESSION['msg']='更新しました';
  header('Location:./result.php');

?>
<!-- debug用 
<button onclick="location.href='./result.php'">ＯＫ</button>
-->
