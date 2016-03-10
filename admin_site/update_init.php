<?php
  session_start();
  require_once(__DIR__.'/../lib/function.php');

//
  unset($_SESSION['img_file']);
  unset($_SESSION['del']);
  unset($_SESSION['img_count_add']);
  unset($_SESSION['img_count_del']);

//upload_tmpフォルダクリア
  $path=__DIR__.'/../upload_tmp/';
  deleteData($path);

  header('Location: ./update.php');

?>

