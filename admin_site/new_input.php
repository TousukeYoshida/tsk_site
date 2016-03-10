<?php
  session_start(); //セッション開始
  require_once("./header.php"); //ヘッダー読込
?>

        <section id="window_name">
          <p>新規投稿</p>
        </section>
    </header>
    <main>
    <!-- エラー表示 -->
      <?php require_once("./error_display.php"); ?> 
      <form action="new_chk.php" method="post" enctype="multipart/form-data">
        <p><label>タイトル</label>
        <input class="input_title" type="text" name="title" size="100" required <?php (isset($_SESSION['title'])) ? print 'value="'.$_SESSION['title'].'"': print ''; ?>></p>
        <p><label>記事</label></p>
        <textarea class="input_news" name="news" cols="80" rows="10" required><?php (isset($_SESSION['news'])) ? print $_SESSION['news'] : print '' ; ?></textarea><br>
        <p>画像ファイルを添付する<input class="sel_files" type="file" name="img_file[]" multiple></p>
        <br>
        <input type="submit" name="submit" value="投稿">
      </form>
    </main>
    <footer>
      <section id=footer_cont>
        <button onclick="location.href='./index.php'">処理を中止し、管理画面に戻る</button>
      </section>
    </footer>
  </body>
</html>
