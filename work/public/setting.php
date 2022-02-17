<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');

if (!isset($_SESSION['name']) &&
!isset($_SESSION['id'])) {
  header('Location: login.php');
  exit;
} else {
  $id = $_SESSION['id'];
  $name = $_SESSION['name'];
}

$title = '各種設定一覧 - ';
$this_css = 'tab';
$setting = 'select';
include('../app/_parts/_header.php');

?>

<div class="container">
  <div class="container_title">
    <h1>各種設定一覧</h1>
  </div>
  <div class="settings_container">
    <h2>設定したい項目をお選びください</h2>
    <div class="settings">
      <ul>
        <li><a href="change_name.php"><i class="bi bi-person-circle"></i>ユーザーネーム変更</a></li>
        <li><a href="change_preemail.php"><i class="bi bi-envelope"></i>メールアドレス変更</a></li>
        <li><a href="change_passwd.php"><i class="bi bi-key"></i>パスワード変更</a></li>
        <li><a href="logout.php"><i class="bi bi-box-arrow-left"></i>ログアウト</a></li>
        <li><a href="leave.php"><i class="bi bi-person-x-fill"></i>退会</a></li>
      </ul>
    </div>
  </div>
</div>

<?php

include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
</body>
</html>