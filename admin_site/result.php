<?php session_start(); ?>
<?php require_once("./header.php"); ?>

        <section id="window_name">
          <p>結果画面</p>
        </section>
    </header>

    <main>
      <p class="result"><?php print $_SESSION['msg']; ?></p>
    </main>
    <footer>
      <section id=footer_cont>
        <button onclick="location.href='./return_index.php'">ＯＫ</button>
      </section>
    </footer>
  </body>
</html>
