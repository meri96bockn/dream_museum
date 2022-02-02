<?php
session_start();
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');

if (isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
  var_dump($form['email']);
}


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
        <label for="email">メールアドレス</label>
        <input type="email" id="email" maxlength="255" placeholder="（例）yumemi@gmail.com" 
        value="
        <?php 
          if (isset($_SESSION['form'])) {
            echo h($form['email']);
          }
        ?>">
        <div class="error">
          <?php if(isset($error['email']) && $error['email'] === 'blank'): ?>
          <p class="error">* メールアドレスを入力してください</p>
          <?php endif; ?>
          </div>
      </div>

      <div class="form_item">
        <label for="password">パスワード</label>
        <input type="password" id="password" maxlength="20" placeholder="（例）yume36ko">
        <div class="error">
          <?php if (isset($error['password']) && $error['password'] === 'alphanumeric'):?>
          <p class="error">* パスワードを入力してください</p>
          <?php endif; ?>
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