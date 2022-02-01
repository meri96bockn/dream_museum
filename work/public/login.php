<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');

$title = 'ログイン - ';
$this_css = 'form';
$index = '';
$dreams = '';
$howto = '';
$login = 'select';
include('../app/_parts/_header.php');

?>

  <div class="forms">
    <div class="form_title">
      <h1>ログイン</h1>
    </div>
    <div class="form">
      <form action="my_page.php" method="post">
        <div class="form_item">
          <label for="name">ユーザーネーム</label>
          <input type="text" id="name" maxlength="255" placeholder="（例）yume_miruko">
        </div>
        <div class="form_item">
          <label for="email">メールアドレス</label>
          <input type="email" id="email" maxlength="255" placeholder="（例）yumemiruko@gmail.com">
        </div>
        <div class="form_item">
          <label for="password">パスワード</label>
          <input type="password" id="password" maxlength="20" placeholder="半角英数8文字以上">
        </div>
        <button>ログイン</button>
      </form>
    </div>
  </div>

  <?php

  include('../app/_parts/_footer.php');

  ?>
  <script src="js/main.js"></script>
</body>
</html>