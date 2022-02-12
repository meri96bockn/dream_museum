<?php
require_once(__DIR__ . '/../app/config.php');
require('../app/functions.php');

if (!isset($_SESSION['token'])) {
  header('Location: index.php');
  exit;
} else {
  unset($_SESSION['token']);
}

$title = '登録完了 - ';
$this_css = 'form';
$index = '';
$dreams = '';
$howto = '';
$my_page = '';
include(__DIR__ . '/../app/_parts/_header.php');

?>

<div class="forms">
  <div class="form_title">
    <h1>新規登録</h1>
  </div>
  <div class="form">
    <p class="success">登録が完了しました。</p>
    <p class="success">ログインできます。</p>
    <div class="button">
      <button type="button" onclick=location.href="login.php">ログイン</button>
    </div>
  </div>
</div>


<?php

include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
</body>
</html>