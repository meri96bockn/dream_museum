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
    'message' => ''
  ];
}
$error = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
  validateToken();

  $form['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  if ($form['name'] === '') {
    $error['name'] = 'blank';
  }

  $form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  if ($form['email'] === '') {
    $error['email'] = 'blank';
  }

  $form['message'] = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
  if ($form['message'] === '') {
    $error['message'] = 'blank';
  }


  if (empty($error)) {
    $_SESSION['form'] = $form;
    header('Location: check_contact.php');
    exit();
  }
}

$title = 'お問い合わせ - ';
$this_css = 'form';
$index = '';
$dreams = '';
$howto = '';
$my_page = '';
include('../app/_parts/_header.php');

?>

  <div class="forms">
    <div class="form_title">
      <h1>お問い合わせ</h1>
    </div>
    <div class="form">
      <form action="process.php" method="post">
        <div class="form_item">
          <label for="name">お名前</label>
          <input type="text" id="name" maxlength="255" value="<?= h($form['name']); ?>" autocomplete="off">
        </div>
        <div class="error">
        <?php if (isset($error['name']) && $error['name'] === 'blank'):?>
          <p>* お名前を入力してください</p>
          <?php endif; ?>
        </div>
        <div class="form_item">
          <label for="email">メールアドレス</label>
          <input type="email" id="email" maxlength="255" placeholder="（例）yumemiruko@gmail.com" value="<?= h($form['email']); ?>">
        </div>
        <div class="error">
        <?php if (isset($error['email']) && $error['email'] === 'blank'): ?>
          <p>* メールアドレスを入力してください</p>
        <?php endif; ?>
        </div>
        <div class="form_item">
          <label for="message">お問い合わせ内容</label>
          <textarea name="message" id="message" rows="10"></textarea>
        </div>
        <div class="error">
        <?php if (isset($error['message']) && $error['message'] === 'blank'): ?>
          <p>* お問い合わせ内容を入力してください</p>
          <?php endif; ?>
        </div>
        <button>送信</button>
      </form>
    </div>
  </div>

  <?php

include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
</body>
</html>