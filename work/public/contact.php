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
  $form['email'] = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  $form['message'] = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
  if ($form['name'] === '') {
    $error['name'] = 'blank';
  }
  if ($form['email'] === '') {
    $error['email'] = 'blank';
  } elseif (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $form['email'])) {
    $error['email']= 'check';
  }
  if ($form['message'] === '') {
    $error['message'] = 'blank';
  }

  if (empty($error)) {
    $_SESSION['form'] = $form;
    header('Location: check_contact.php');
    exit;
  }
}

$title = 'お問い合わせ - ';
$this_css = 'form';
include(__DIR__ . '/../app/_parts/_header.php');
?>
<div class="forms">
  <div class="form_title">
    <h1>お問い合わせ</h1>
  </div>
  <div class="form">
    <form action="" method="post" autocomplete="off">
      <div class="form_item">
        <label for="name">お名前</label>
        <input type="text" name="name" id="name" maxlength="255" value="<?= h($form['name']); ?>">
      </div>
      <div class="error">
        <?php if (isset($error['name']) && $error['name'] === 'blank'):?>
          <p>* お名前を入力してください</p>
        <?php endif; ?>
      </div>
      <div class="form_item">
        <label for="email">メールアドレス</label>
        <input type="email" name="email" id="email" maxlength="255" placeholder="（例）yumemiruko@gmail.com" value="<?= h($form['email']); ?>">
      </div>
      <div class="error">
        <?php if (isset($error['email']) && $error['email'] === 'blank'): ?>
          <p>* メールアドレスを入力してください</p>
        <?php endif; ?>
        <?php if (isset($error['email']) && $error['email'] === 'check'): ?>
          <p>* メールアドレスを正しい形式で入力してください</p>
        <?php endif; ?>
      </div>
      <div class="form_item">
        <label for="message">お問い合わせ内容</label>
        <textarea name="message" id="message" rows="10"><?= h($form['message']); ?></textarea>
      </div>
      <div class="error">
        <?php if (isset($error['message']) && $error['message'] === 'blank'): ?>
          <p>* お問い合わせ内容を入力してください</p>
        <?php endif; ?>
      </div>
      <div class="button">
        <button>送信</button>
        <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
      </div>
    </form>
  </div>
</div>

<?php
include(__DIR__ . '/../app/_parts/_footer.php');
?>
<script src="js/main.js"></script>
</body>
</html>