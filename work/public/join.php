<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');
createToken();


if (isset($_GET['action']) && $_GET['action'] === 'rewrite' && isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
} else {
  $form = [
    'name' => '',
    'email' => '',
    'password' => ''
  ];
}
$re_password = '';
$error = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
  validateToken();

  $form['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  if (!preg_match('/\A[a-z\d]{1,100}+\z/i', $form['name'])) {
    $error['name'] = 'alphanumeric';
  }

  $form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  if ($form['email'] === '') {
    $error['email'] = 'blank';
  } else {
    $stmt = $pdo->prepare(
      "SELECT COUNT(*)
      FROM members
      WHERE email = :email"
      );
      $stmt->bindvalue(
        ':email', $form['email'], PDO::PARAM_STR
      );
      $stmt->execute();
      $counts = $stmt->fetch();

      if ($counts['COUNT(*)'] > 0) {
        $error['email'] = 'duplicate';
      }
    }

  $form['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  if (!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $form['password'])) {
    $error['password'] = 'alphanumeric';
    $form['password'] = '';
  } 
  
  $re_password = filter_input(INPUT_POST, 're_password', FILTER_SANITIZE_STRING);
  if ($form['password'] !== $re_password){
    $error['re_password'] = 'miss';
    $re_password = '';
  }

  if (empty($error)) {
    $_SESSION['form'] = $form;
    header('Location: check.php');
    exit();
  }
}


if ($form['name'] === '') {
  $error['name'] = 'alphanumeric';
}
if ($form['password'] === '') {
  $error['password'] = 'alphanumeric';
}


$title = '新規登録 - ';
$this_css = 'form';
$index = '';
$dreams = '';
$howto = '';
$my_page = '';
include(__DIR__ . '/../app/_parts/_header.php');
?>

<div class="forms">
  <div class="form_title">
    <h1>新規登録</h1>
  </div>
  <div class="form">
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
      <div class="form_item">
        <label for="name">ユーザーネーム</label>
        <input type="text" name="name" id="name" maxlength="255" placeholder="（例）yume3" value="<?= h($form['name']); ?>">
      </div>
      <div class="error">
        <?php if (isset($error['name']) && $error['name'] === 'alphanumeric'):?>
          <p>* ユーザーネームは半角英数字で入力してください</p>
        <?php endif; ?>
      </div>
      <div class="form_item">
        <label for="email">メールアドレス</label>
        <input type="email" name="email" id="email" maxlength="255" placeholder="（例）yumemi@gmail.com" value="<?= h($form['email']); ?>">
      </div>
      <div class="error">
        <?php if (isset($error['email']) && $error['email'] === 'blank'): ?>
        <p>* メールアドレスを入力してください</p>
        <?php endif; ?>
        <?php if (isset($error['email']) && $error['email'] === 'duplicate'):?>
        <p>* ご指定のメールアドレスはすでに登録されています</p>
        <?php endif; ?>
      </div>
      <div class="form_item">
        <label for="password">パスワード</label>
        <input type="password" name="password" id="password" placeholder="（例）yume36ko" value="<?= h($form['password']); ?>">
      </div>
      <div class="error">
        <?php if (isset($error['password']) && $error['password'] === 'alphanumeric'): ?>
        <p>* パスワードは半角英数字を1文字ずつ組み合わせ、8文字以上で入力してください</p>
        <?php endif; ?>
      </div>
      <div class="form_item">
        <label for="re_password">パスワード（再入力）</label>
        <input type="password" name="re_password" id="re_password" placeholder="（例）yume36ko" value="<?= h($re_password); ?>">
      </div>
      <div class="error">
        <?php if (isset($error['re_password']) && $error['re_password'] === 'miss'):?>
        <p>* パスワードが一致していません</p>
        <?php endif; ?>
      </div>
      <button>入力内容を確認する</button>
      <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
    </form>
  </div>
</div>

<?php

include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
</body>
</html>