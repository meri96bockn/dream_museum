<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');
createToken();

$email = '';
$error = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
  validateToken();
  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
  if ($email === '') {
    $error = 'blank';
  } elseif (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)) {
    $error= 'check';
  } else {
    $stmt = $pdo->prepare(
      "SELECT COUNT(*)
      FROM members
      WHERE email = :email"
      );
      $stmt->bindvalue(
        ':email', $email, PDO::PARAM_STR
      );
      $stmt->execute();
      $counts = $stmt->fetch();
    if ($counts['COUNT(*)'] > 0) {
      $error = 'duplicate';
    }
  }


  if (empty($error)) {
    $urltoken = hash('sha256',uniqid(rand(),1));
    $url = "https://dreamuseum.com/join.php?urltoken=".$urltoken;

    try{
      $sql = "INSERT INTO pre_members (urltoken, email, date, flag) VALUES (:urltoken, :email, now(), '0')";
      $stm = $pdo->prepare($sql);
      $stm->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
      $stm->bindValue(':email', $email, PDO::PARAM_STR);
      $stm->execute();
      $pdo = null;

      /* メール送信処理 */
      $to = $email;
      $subject = '【自動返信】本会員登録のご案内';
      $body = <<< EOM
      このたびは、仮登録していただきありがとうございます。
      24時間以内に下記のURLへアクセスし、本会員登録をしてください。
      {$url}

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
      if (mb_send_mail($to ,$subject ,$body , $headers, $pfrom)) {
        header("Location: success_pre.php");
      }
    } catch (PDOException $e) {
      $pdo->rollBack();
      $error['try'] = "failure";
      $error_message = 'Error:'. $e->getMessage();
      error_log($error_message, 1, "error@dreamuseum.com");
      die();
    }
  }
}


$title = '仮会員登録 - ';
$this_css = 'form';
include(__DIR__ . '/../app/_parts/_header.php');
?>

<?php if (isset($error['try']) && $error['try'] === 'failure'): ?>
<div class="forms">
  <div class="form_title">
    <h1>仮会員登録</h1>
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
    <h1>仮会員登録</h1>
  </div>
  <div class="form">
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
      <div class="form_item">
        <label for="email">メールアドレス</label>
        <input type="email" name="email" id="email" maxlength="255" placeholder="（例）yumemi@gmail.com" value="<?= h($email); ?>">
      </div>
      <div class="error">
        <?php if (isset($error) && $error === 'blank'): ?>
        <p>* メールアドレスを入力してください</p>
        <?php endif; ?>
        <?php if (isset($error) && $error === 'check'): ?>
        <p>* メールアドレスを正しい形式で入力してください</p>
        <?php endif; ?>
        <?php if (isset($error) && $error === 'duplicate'):?>
        <p>* ご指定のメールアドレスはすでに登録されています</p>
        <?php endif; ?>
      </div>
      <div class="button">
        <button>本登録用のメールを送信する</button>
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