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
  header("Location: pre_join.php");
}


$title = '仮会員登録 - ';
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
        登録ご案内のメールをお送りいたしました。
        <br>  
        24時間以内にメールに記載されたURLからご登録ください
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