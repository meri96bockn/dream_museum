<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');

if (isset($_SESSION['token']) && isset($_SESSION['url'])) {
  $message = $_SESSION['message'];
  $url = $_SESSION['url'];
} else {
  header("Location: change_preemail.php");
}


$title = 'メールアドレス仮変更 - ';
$this_css = 'form';
include(__DIR__ . '/../app/_parts/_header.php');
?>

<div class="forms">
  <div class="form_title">
    <h1>メール送信完了</h1>
  </div>
  <div class="form">
    <div class="form_item">
        <!-- 登録完了画面 -->
        <p><?=$message?></p>
        <p>↓TEST用(後ほど削除)：このURLが記載されたメールが届きます。</p>
        <a href="<?=$url?>"><?=$url?></a>
      </div>
    </div>
  </div>

<?php

include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
</body>
</html>