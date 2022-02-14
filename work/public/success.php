<?php
require_once(__DIR__ . '/../app/config.php');
require('../app/functions.php');
var_dump($_SESSION['urltoken']);
var_dump($urltoken);
if (!isset($_SESSION['token']) || !isset($_SESSION['form'])) {
  header('Location: index.php');
  exit;
} else {
  //セッション変数を全て解除
  $_SESSION = array();
  //セッションクッキーの削除
  if (isset($_COOKIE["PHPSESSID"])) {
      setcookie("PHPSESSID", '', time() - 1800, '/');
  }
  //セッションを破棄する
  session_destroy();
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
var_dump($_SESSION['urltoken']);
var_dump($urltoken);
include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
</body>
</html>