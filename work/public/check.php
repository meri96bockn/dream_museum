<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');

if (!isset($_SESSION['form'])) {
  header('Location: index.php');
  exit();
} else {
  $form = $_SESSION['form'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  validateToken();
  $stmt = $pdo->prepare(
    "INSERT INTO 
      members (username, email, password)
      VALUES (:username, :email, :password)"
  );
  $stmt->execute(
    [ 'username' => $form['name'],
      'email' => $form['email'],
      'password' => password_hash($form['password'], PASSWORD_DEFAULT)
    ]
  );
  unset($_SESSION['token']);
  unset($_SESSION['form']);
  createToken();
  header('Location: success.php');
  exit();
}

$title = '登録内容確認 - ';
$this_css = 'form';
include(__DIR__ . '/../app/_parts/_header.php');

?>
<div class="forms">
  <div class="form_title">
    <h1>新規登録</h1>
  </div>
  <div class="form">
    <form action="" method="post" enctype="multipart/form-data">
      <dl class="form_item check">
        <dt>ユーザーネーム</dt>
        <dd><i class="bi bi-chevron-double-right"></i><?= h($form['name']); ?></dd>
      </dl>
      <dl class="form_item check">
        <dt>メールアドレス</dt>
        <dd><i class="bi bi-chevron-double-right"></i><?= h($form['email']); ?></dd>
      </dl>
      <dl class="form_item check">
        <dt>パスワード</dt>
        <dd><i class="bi bi-chevron-double-right"></i>非表示</dd>
      </dl>
      <div class="button">
        <button type="button" onclick=location.href="join.php?action=rewrite">変更</button>
        <button>登録</button>
        <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
      </div>
    </form>
  </div>
</div>

<?php

include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
</body>
</html>