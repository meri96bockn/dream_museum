<?php
require_once(__DIR__ . '/../app/config.php');
require('../app/functions.php');

if (!isset($_SESSION['token'])) {
  header('Location: index.php');
  exit;
} else {
  unset($_SESSION['token']);
}

$title = 'お問い合わせ完了 - ';
$this_css = 'form';
include(__DIR__ . '/../app/_parts/_header.php');

?>

<div class="forms">
  <div class="form_title">
    <h1>お問い合わせ</h1>
  </div>
  <div class="form">
    <p class="success">お問い合わせいただき、ありがとうございました。</p>
    <p class="success">トップページにお戻りください。</p>
    <div class="button">
      <button type="button" onclick=location.href="index.php">DreaMuseum</button>
    </div>
  </div>
</div>


<?php

include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
</body>
</html>