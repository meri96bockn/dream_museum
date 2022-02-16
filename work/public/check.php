<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');
if (isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
  $urltoken = $_SESSION['urltoken'];
} else {
  header('Location: index.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  validateToken();
  try{
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
    $sql = "UPDATE pre_members SET flag = 1 WHERE email=:email";
    $stm = $pdo->prepare($sql);
    $stm->bindValue(':email', $form['email'], PDO::PARAM_STR);
    $stm->execute();


    $to = $form['email'];
    $subject = '【自動返信】本登録が完了しました';
    $body = <<< EOM
    DreaMuseumにご登録いただきありがとうございました。
    下記URLからログインできます。
    https://dreamuseum.com/login.php

    また、こちらのメールにご返信いただくことはできません。
    ご了承ください。
    お困りの際は、本サービスの「お問い合わせ」にてご連絡ください。
    EOM;
    $from_name = 'DreaMuseum';
    $from_email = 'join@dreamuseum.com';
    $pfrom = "-f $from_email";
    $headers = 'From: ' . ($from_name). ' <' . $from_email. '>';
  
    mb_language('ja');
    mb_internal_encoding('UTF-8');
    if (mb_send_mail($to, $subject, $body, $headers, $pfrom)) {
      $urltoken = '';
      unset($_SESSION['token']);
      unset($_SESSION['form']);
      createToken();
      header('Location: success.php');
      exit;
    }
      $stm = null;
  }catch (PDOException $e){
    $pdo->rollBack();
    $errors['error'] = "もう一度やりなおして下さい。";
    print('Error:'.$e->getMessage());
  }
}

$title = '本会員登録確認 - ';
$this_css = 'form';
include(__DIR__ . '/../app/_parts/_header.php');

?>
<div class="forms">
  <div class="form_title">
    <h1>本会員登録確認</h1>
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
        <button type="button" onclick=location.href="join.php?urltoken=<?= h($urltoken) ?>">変更</button>
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