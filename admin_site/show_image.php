<?php
//画像ファイル表示用php
  if($_REQUEST['flag']=='img'):
    $dir = '../upload_img'; // 画像保存ディレクトリ
  else:
    $dir = '../upload_tmp'; // 画像保存ディレクトリ
  endif;
  $imgName = $dir.'/'.basename( $_REQUEST['img'] );

  header('Content-Length: '.filesize($imgName));
  header('Content-Type: image/' . $_REQUEST['ext']);

  if( is_file( $imgName ) && file_exists( $imgName ) ):
    print( file_get_contents( $imgName ) );
  endif;
?>