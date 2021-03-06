<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');

if (isset($_SESSION['token']) && isset($_SESSION['form'])) {
  $_SESSION = array();
  if (isset($_COOKIE["PHPSESSID"])) {
    setcookie("PHPSESSID", '', time() - 1800, '/');
  }
  session_destroy();
} else {
  header('Location: index.php');
  exit;
}

$title = '登録完了 - ';
$this_css = 'form';
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
include(__DIR__ . '/../app/_parts/_footer.php');
?>
<script src="js/main.js"></script>
</body>
</html>