<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');

if (isset($_SESSION['token'])) {
  $_SESSION = array();
  if (isset($_COOKIE["PHPSESSID"])) {
    setcookie("PHPSESSID", '', time() - 1800, '/');
  }
  session_destroy();
} else {
  header("Location: forget_passwd.php");
}


$title = 'パスワード再設定 - ';
$this_css = 'form';
include(__DIR__ . '/../app/_parts/_header.php');
?>

<div class="forms">
  <div class="form_title">
    <h1>メール送信完了</h1>
  </div>
  <div class="form">
    <div class="form_item">
      <p>
      パスワード再設定用のご案内メールをお送りいたしました。
      <br>
      24時間以内にメールに記載されたURLから再設定してください。
      </p>
    </div>
  </div>
</div>

<?php

include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
</body>
</html>