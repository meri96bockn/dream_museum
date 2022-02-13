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
  $error = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type']) &&  $_POST['type'] === 'logout' ) {
  $_SESSION = array();
  if (isset($_COOKIE["PHPSESSID"])) {
    setcookie("PHPSESSID", '', time() - 1800, '/');
  }
  session_destroy();
  header('Location: index.php');
}


$title = 'ログアウト - ';
$this_css = 'form';
$setting = 'select';
include('../app/_parts/_header.php');

?>

<div class="forms">
  <div class="form_title">
    <h1>ログアウト</h1>
  </div>
  <div class="form">
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off" onsubmit="return settings()">
      <div class="form_item">
      <p>ログアウトしてよろしければ、<br>下のボタンをタップしてください。</p>
      </div>
      
      <div class="button">
        <button>ログアウト</button>
        <input type="hidden" name="type" value="logout">
      </div>
    </form>
  </div>
</div>


<?php

include('../app/_parts/_footer.php');

?>
<script>
  function settings() {
    const select = confirm('本当にログアウトしますか？');
    return select;
  }
</script>
<script src="js/main.js"></script>
</body>
</html>