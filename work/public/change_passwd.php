<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');

if (!isset($_SESSION['name']) &&
!isset($_SESSION['id'])) {
  header('Location: login.php');
  exit;
} else {
  createToken();
  $id = $_SESSION['id'];
  $name = $_SESSION['name'];
  $password = '';
  $new_password = '';
  $re_password = '';
  $error = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type']) &&  $_POST['type'] === 'newpasswd' ) {
  validateToken();

  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  $stmt = $pdo->prepare(
    "SELECT password
    FROM members
    WHERE id = :id 
    limit 1"
  );
  $stmt->execute(
    [ 'id' => $id ]
  );
  $row = $stmt->fetch();
  if (!password_verify($password, $row["password"])) {
    $error['password'] = 'notmatch';
  }

  $new_password = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);
  if (!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $new_password)) {
    $error['new_password'] = 'alphanumeric';
    $new_password = '';
  } 

  $re_password = filter_input(INPUT_POST, 're_password', FILTER_SANITIZE_STRING);
  if ($new_password !== $re_password){
    $error['re_password'] = 'miss';
    $re_password = '';
  }

  
    if (empty($error)) {
      try {
      $stmt = $pdo->prepare(
        "UPDATE members SET password = :newpasswd WHERE id = :id"
      );
      $stmt->execute(
        [ 'newpasswd' => password_hash($new_password, PASSWORD_DEFAULT),
        'id' => $id ]
      );
      $password = "";
      $new_password = "";
      $re_password = "";
      unset($_SESSION['token']);
    } catch (PDOException $e) {
      $pdo->rollBack();
      $error['try'] = "failure";
      $error_message = 'Error:'. $e->getMessage();
      error_log($error_message, 1, "error@dreamuseum.com");
      die();
    }
  }
}


$title = 'パスワード変更 - ';
$this_css = 'form';
$setting = 'select';
include('../app/_parts/_header.php');

?>

<?php if (isset($error['try']) && $error['try'] === 'failure'): ?>
<div class="forms">
  <div class="form_title">
    <h1>パスワード変更</h1>
  </div>
  <div class="form">
    <div class="form_item">
      <div class="error">
        <p>* お手数ですが、もう一度やり直してください</p>
      </div>
    </div>
  </div>
</div>

<?php else: ?>
<div class="forms">
  <div class="form_title">
    <h1>パスワード変更</h1>
  </div>
  <div class="form">
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off" onsubmit="return settings()">
      <div class="form_item">
        <label for="password">現在のパスワード</label>
        <input type="password" name="password" id="password" value="<?= h($password); ?>">
      </div>
      <div class="error">
        <?php if (isset($error['password']) && $error['password'] === 'notmatch'): ?>
        <p>* 正しいパスワードを入力してください</p>
        <?php endif; ?>
      </div>
      <div class="form_item">
        <label for="new_password">新しいパスワード</label>
        <input type="password" name="new_password" id="new_password" placeholder="（例）yume36ko" value="<?= h($new_password); ?>">
      </div>
      <div class="error">
        <?php if (isset($error['new_password']) && $error['new_password'] === 'alphanumeric'): ?>
        <p>* パスワードは半角英数字を1文字ずつ組み合わせ、8文字以上で入力してください</p>
        <?php endif; ?>
      </div>
      <div class="form_item">
        <label for="re_password">新しいパスワード（再入力）</label>
        <input type="password" name="re_password" id="re_password" placeholder="（例）yume36ko" value="<?= h($re_password); ?>">
      </div>
      <div class="error">
        <?php if (isset($error['re_password']) && $error['re_password'] === 'miss'):?>
        <p>* 新しいパスワードが一致していません</p>
        <?php endif; ?>
      </div>
      
      <div class="button">
        <button>更新</button>
        <input type="hidden" name="type" value="newpasswd">
        <input type="hidden" name="token" value="<?=  h($_SESSION['token']); ?>">
      </div>
    </form>
  </div>
</div>
<?php endif; ?>


<?php

include('../app/_parts/_footer.php');

?>
<script>
  function settings() {
    const select = confirm('本当に更新しますか？');
    return select;
  }
</script>
<script src="js/main.js"></script>
</body>
</html>