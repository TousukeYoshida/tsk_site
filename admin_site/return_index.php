<?php
  session_start();
  require_once(__DIR__.'/../lib/function.php');

//
  unset($_SESSION['news_id']);
  unset($_SESSION['title']);
  unset($_SESSION['news']);
  unset($_SESSION['img_file']);
  unset($_SESSION['del']);
  unset($_SESSION['img_count_old']);
  unset($_SESSION['img_count_add']);
  unset($_SESSION['img_count_del']);
  unset($_SESSION['errors']);
  unset($_SESSION['msg']);

//upload_tmpフォルダクリア
  $path=__DIR__.'/../upload_tmp/';
  deleteData($path);

  header('Location: ./index.php');

?>

