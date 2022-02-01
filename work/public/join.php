<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');

$form = [
  'name' => '',
  'email' => '',
  'password' => ''
];
$error = [];

$form['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
if (!preg_match('/\A[a-z\d]{4,100}+\z/i', $form['name'])) {
  $error['name'] = 'alphanumeric';
}

$form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
if ($form['email'] === '') {
  $error['email'] = 'blank';
}

$form['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
if (!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $form['password'])) {
  $error['password'] = 'alphanumeric';
}


if (empty($error)) {
  $_SESSION['form'] = $form;
  header('location: my_page.php');
  exit();
}


$title = '新規登録 - ';
$this_css = 'form';
include('../app/_parts/_header.php');
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
            <div class="error">
              <?php if (isset($error['name']) && $error['name'] === 'alphanumeric'):?>
                <p class="error">* ユーザーネームは半角英数4文字以上で入力してください</p>
              <?php endif; ?>
          </div>
        </div>


        <div class="form_item">
          <label for="email">メールアドレス</label>
          <input type="email" name="email" id="email" maxlength="255" placeholder="（例）yumemi@gmail.com" value="<?= h($form['email']); ?>">
          <div class="error">
            <?php if(isset($error['email']) && $error['email'] === 'blank'): ?>
            <p class="error">* メールアドレスを入力してください</p>
            <?php endif; ?>
            <p class="error">* ご指定のメールアドレスはすでに登録されています</p>
          </div>
        </div>
        <div class="form_item">
          <label for="password">パスワード</label>
          <input type="password" name="password" id="password" maxlength="20" placeholder="（例）yume36ko" value="<?= h($form['password']); ?>">
          <div class="error">
            <?php if (isset($error['password']) && $error['password'] === 'alphanumeric'):?>
            <p class="error">* パスワードは半角英数を組み合わせ、8文字以上で入力してください</p>
            <?php endif; ?>
          </div>
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