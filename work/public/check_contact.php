<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');

if (isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
} else {
  header('Location: index.php');
  exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  validateToken();
  $name = $form["name"];
  $to = $form["email"];
  $message = $form["message"];
  $email = "";

  mb_language("ja");
  mb_internal_encoding("UTF-8");
  $subject = "[自動送信]お問い合わせ内容の確認";
  $body = <<< "EOM"
  {$name}様、お問い合わせありがとうございます。
  以下の内容で承りました。
  ===================================================
  【 お名前 】
  {$name}

  【 メールアドレス 】
  {$user_email}

  【 お問い合わせ内容 】
  {$message}

  ===================================================
  内容を確認の上、回答いたします。
  しばらくお待ちください。

  DreamMuseum
  EOM;


  $fromEmail = ""; 
  $fromName = "DreamMuseum";
  $header = "From: " .mb_encode_mimeheader($fromName) ."<{$fromEmail}>";
  mb_send_mail($user_email, $subject, $body, $header);

  unset($_SESSION['token']);
  unset($_SESSION['form']);
  createToken();
  header("Location: success_contact.php");
  exit;

}

$title = 'お問い合わせ内容確認 - ';
$this_css = 'form';
include(__DIR__ . '/../app/_parts/_header.php');

?>
<div class="forms">
  <div class="form_title">
    <h1>新規登録</h1>
  </div>
  <div class="form">
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
      <dl class="form_item check">
        <dt>お名前</dt>
        <dd><i class="bi bi-chevron-double-right"></i><?= h($form['name']); ?> 様</dd>
      </dl>
      <dl class="form_item check">
        <dt>メールアドレス</dt>
        <dd><i class="bi bi-chevron-double-right"></i><?= h($form['email']); ?></dd>
      </dl>
      <dl class="form_item check">
        <dt>お問い合わせ内容</dt>
        <dd class="message"><i class="bi bi-chevron-double-right"></i><?= nl2br(h($form['message'])); ?></dd>
      </dl>
      <div class="button">
        <button type="button" onclick=location.href="contact.php?action=rewrite">変更</button>
        <button>送信</button>
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