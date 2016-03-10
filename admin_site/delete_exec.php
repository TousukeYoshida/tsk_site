<?php
  session_start();
  
//debug start
  echo '<pre>';
  print '<p>$_SESSION</p>';
  var_dump($_SESSION);
  echo '</pre>';
//debug end

//SESSION変数から各変数に代入
  $news_id=$_SESSION['news_id'];
  $img_count_del=$_SESSION['img_count_del'];
  $img_flag=$_SESSION['img_flag'];

//DB書込処理 

//オブジェクト $dbh作成
  require_once(__DIR__.'/../lib/access_db.php');

/* newsテーブルdelete処理 start */
  echo '<p>***news table 処理 start***</p>';

//SQLコマンドセット
  $sql_cmd='DELETE FROM news WHERE news_id=:id';

//execute用配列セット
  $args=array(
    'id' => $news_id
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

/* newsテーブルdelete処理 end */

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
  $_SESSION['msg']='削除しました';
  header('Location:./result.php');

?>
<!-- debug用 
<button onclick="location.href='./result.php'">ＯＫ</button>
-->
