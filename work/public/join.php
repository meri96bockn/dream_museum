<?php
session_start();
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');

$form = [
  'name' => '',
  'email' => '',
  'password' => ''
];
$re_password = '';
$error = [];

$form['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
if (!preg_match('/\A[a-z\d]{1,100}+\z/i', $form['name'])) {
  $error['name'] = 'alphanumeric';
}

$form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
if ($form['email'] === '') {
  $error['email'] = 'blank';
}

$form['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
if (!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $form['password'])) {
  $error['password'] = 'alphanumeric';
  $form['password'] = '';
} 

$re_password = filter_input(INPUT_POST, 're_password', FILTER_SANITIZE_STRING);
if ($form['password'] !== $re_password) {
  $error['re_password'] = 'miss';
  $re_password = '';
}

if (empty($error)) {
  $_SESSION['form'] = $form;
  header('location: login.php');
  exit();
}

$title = '新規登録 - ';
$this_css = 'form';
include(__DIR__ . '/../app/_parts/_header.php');

?>

<div class="forms">
  <div class="form_title">
    <h1>新規登録</h1>
  </div>
  <div class="form">
    <form action="" method="post" enctype="multipart/form-data">
      <div class="form_item">
        <label for="name">ユーザーネーム</label>
        <input type="text" name="name" id="name" maxlength="255" placeholder="（例）yume3" value="<?= h($form['name']); ?>" >
      </div>
          <div class="error">
            <?php if (isset($error['name']) && $error['name'] === 'alphanumeric'):?>
              <p>* ユーザーネームは半角英数で入力してください</p>
            <?php endif; ?>
        </div>
      <div class="form_item">
        <label for="email">メールアドレス</label>
        <input type="email" name="email" id="email" maxlength="255" placeholder="（例）yumemi@gmail.com" value="<?= h($form['email']); ?>">
      </div>
        <div class="error">
          <?php if(isset($error['email']) && $error['email'] === 'blank'): ?>
          <p>* メールアドレスを入力してください</p>
          <?php endif; ?>
          <p>* ご指定のメールアドレスはすでに登録されています</p>
        </div>
      <div class="form_item">
        <label for="password">パスワード</label>
        <input type="password" name="password" id="password" placeholder="（例）yume36ko" value="<?= h($form['password']); ?>">
      </div>
        <div class="error">
          <?php if (isset($error['password']) && $error['password'] === 'alphanumeric'): ?>
          <p>* パスワードは半角英数を組み合わせ、8文字以上で入力してください</p>
          <?php endif; ?>
        </div>
      <div class="form_item">
        <label for="re_password">パスワード（再入力）</label>
        <input type="password" name="re_password" id="re_password" placeholder="（例）yume36ko" value="<?= h($re_password);?>"
        >
      </div>
        <div class="error">
          <?php if (isset($error['re_password']) && $error['re_password'] === 'miss'):?>
          <p>* パスワードが一致していません</p>
          <?php endif; ?>
      </div>
      <button>登録</button>
    </form>
  </div>
</div>

<?php

include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
</body>
</html>