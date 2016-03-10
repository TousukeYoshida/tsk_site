<!-- エラー表示 -->
<!-- bodyタグ内に挿入すること -->
<?php
  if( isset($_SESSION['errors'])):
?>
<p class="char-red"><strong>入力エラー！！</strong></p>
<?php
    foreach($_SESSION['errors'] as $val):
?>
<p class="char-red"><?php echo $val; ?></p>
<?php
    endforeach;
  endif;
  $_SESSION['errors']=null;
?>
<hr>
