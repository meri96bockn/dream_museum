<?php
require_once(__DIR__ . '/../app/config.php');
require('../app/functions.php');

if (!isset($_SESSION['token'])) {
  header('Location: index.php');
  exit;
} else {
  unset($_SESSION['token']);
}

$title = '記録完了 - ';
$this_css = 'form';
$my_page = 'select';
include(__DIR__ . '/../app/_parts/_header.php');

?>

<div class="forms">
  <div class="form_title">
    <h1>マイページ</h1>
  </div>
  <div class="form">
    <p class="success">夢日記を記録しました。</p>
    <p class="success">マイページにもどります。</p>
    <div class="button">
      <button type="button" onclick=location.href="my_page.php">マイページ</button>
    </div>
  </div>
</div>


<?php

include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
</body>
</html>