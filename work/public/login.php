<?php
session_start();
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');

$title = 'ログイン - ';
$this_css = 'form';
$index = '';
$dreams = '';
$howto = '';
$login = 'select';
include(__DIR__ . '/../app/_parts/_header.php');

?>

<div class="forms">
  <div class="form_title">
    <h1>ログイン</h1>
  </div>
  <div class="form">
    <form action="" method="post" enctype="multipart/form-data">
      <div class="form_item">
        <label for="email">メールアドレス</label>
        <input type="email" name="email" id="email" maxlength="255" placeholder="（例）yumemi@gmail.com" 
        value="">

      </div>
      <div class="error">
          <p>* メールアドレスを入力してください</p>
      </div>
      <div class="form_item">
        <label for="password">パスワード</label>
        <input type="password" name="password" id="password" placeholder="（例）yume36ko">
        <div class="error">

          <p>* パスワードを入力してください</p>

        </div>
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