<?php
  session_start();
  require_once(__DIR__.'/../lib/function.php');

//
  unset($_SESSION['img_file']);

//upload_tmpフォルダクリア
  $path=__DIR__.'/../upload_tmp/';
  deleteData($path);

  header('Location: ./new_input.php');

?>

