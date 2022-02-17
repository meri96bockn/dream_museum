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
  try {
    validateToken();
    $name = $form["name"];
    $message = $form["message"];
    $to = $form['email'];
    $subject = '【自動返信】お問い合わせありがとうございます';
    $body = <<< EOM
    {$name}様、お問い合わせいただきありがとうございます。
    以下の内容で承りました。

    ━━━━━━□■□　お問い合わせ内容　□■□━━━━━━

    【お名前】
    {$name}様

    【メールアドレス】
    {$to}

    【お問い合わせ内容】
    {$message}

    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    内容を確認のうえ、ご返信いたします。
    しばらくお待ちください。
    EOM;

    date_default_timezone_set('Asia/Tokyo');
    $timeStamp = time();
    $week = array('日', '月', '火', '水', '木', '金', '土');
    $dateFormatYMD = date('Y年m月d日',$timeStamp);
    $dateFormatHIS = date('H時i分s秒',$timeStamp);
    $weekFormat = "（".$week[date('w',$timeStamp)]."）";
    $outputDate = $dateFormatYMD.$weekFormat.$dateFormatHIS;
    $admin_subject = "$name 様よりお問い合わせ";
    $admin_body = <<< EOM
    {$name}様よりお問い合わせです。
    {$outputDate}

    ━━━━━━□■□　お問い合わせ内容　□■□━━━━━━

    【お名前】
    {$name}様

    【メールアドレス】
    {$to}

    【お問い合わせ内容】
    {$message}

    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    EOM;


    $from_name = 'DreaMuseum';
    $from_email = 'contact@dreamuseum.com';
    $pfrom = "-f $from_email";
    $headers = 'From: ' . ($from_name). ' <' . $from_email. '>';

    mb_language('ja');
    mb_internal_encoding('UTF-8');
    if (mb_send_mail($to, $subject, $body, $headers, $pfrom) &&
    mb_send_mail($from_email, $admin_subject, $admin_body, $headers, $pfrom)) {
      unset($_SESSION['token']);
      unset($_SESSION['form']);
      createToken();
      header("Location: success_contact.php");
      exit;
    }
  } catch (PDOException $e) {
    $pdo->rollBack();
    $error['try'] = "failure";
    $error_message = 'Error:'. $e->getMessage();
    error_log($error_message, 1, "error@dreamuseum.com");
    die();
  }
}

$title = 'お問い合わせ内容確認 - ';
$this_css = 'form';
include(__DIR__ . '/../app/_parts/_header.php');

?>

<?php if (isset($error['try']) && $error['try'] === 'failure'): ?>
<div class="forms">
  <div class="form_title">
    <h1>お問い合わせ内容確認</h1>
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
    <h1>お問い合わせ内容確認</h1>
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
<?php endif; ?>

<?php

include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
</body>
</html>