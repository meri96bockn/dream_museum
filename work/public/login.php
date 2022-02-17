<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');
createToken();

$email = '';
$error = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  validateToken();

  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  
  if ($email === '') {
    $error['email'] = 'blank';
  }
  if ($password === '') {
    $error['password'] = 'blank';
  }
  
  if (empty($error)) {
    $stmt = $pdo->prepare(
      "SELECT *
      FROM members
      WHERE email = :email 
      limit 1"
    );
    $stmt->bindvalue(
      ':email', $email,
      PDO::PARAM_STR
    );
    $stmt->execute();
    $row = $stmt->fetch();
    
    if (password_verify($password, $row["password"])) {
      session_regenerate_id();
      $_SESSION['id'] = $row['id'];
      $_SESSION['name'] = $row['username'];
      header('Location: my_page.php');
      exit;
    } else {
      $error['login'] = 'failed';
    }
  }
}

$title = 'ログイン - ';
$this_css = 'form';
include(__DIR__ . '/../app/_parts/_header.php');

?>
<div class="forms">
  <div class="form_title">
    <h1>ログイン</h1>
  </div>
  <div class="form">
    <form action="" method="post" enctype="multipart/form-data">
      <div class="form_item">
        <label for="email">メールアドレス</label>
        <input type="email" name="email" id="email" maxlength="255" placeholder="（例）yumemi@gmail.com" 
        value="<?= h($email); ?>">
      </div>
      <div class="error">
        <?php if (isset($error['email']) && $error['email'] === 'blank'):?>
            <p>* メールアドレスを入力してください</p>
        <?php endif; ?>
      </div>
      <div class="form_item">
        <label for="password">パスワード</label>
        <input type="password" name="password" id="password" placeholder="（例）yume36ko">
      </div>
      <div class="error">
        <?php if (isset($error['password']) && $error['password'] === 'blank'):?>
          <p>* パスワードを入力してください</p>
        <?php endif; ?>
      </div>
      <div class="error">
        <?php if (isset($error['login']) && $error['login'] === 'failed'):?>
          <p>* メールアドレスまたはパスワードを正しく入力してください</p>
        <?php endif; ?>
      </div>
      <div class="button">
        <button>ログイン</button>
        <input type="hidden" name="token" value="<?=  h($_SESSION['token']); ?>">
      </div>
        <a href="forget_passwd.php" class="re_passwd">パスワードを忘れた方へ</a>
    </form>
  </div>
</div>

<?php

include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
</body>
</html>