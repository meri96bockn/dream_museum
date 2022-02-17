<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');

if (!isset($_SESSION['name']) &&
!isset($_SESSION['id'])) {
  header('Location: login.php');
  exit;
} else {
  createToken();
  $id = $_SESSION['id'];
  $name = $_SESSION['name'];
  $error = '';
  $stmt = $pdo->prepare(
    "SELECT email
    FROM members
    WHERE id = :id"
    );
    $stmt->bindvalue(
      ':id', $id, PDO::PARAM_STR
    );
    $stmt->execute();
    $member = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type']) &&  $_POST['type'] === 'remail' ) {
  validateToken();
  $remail = filter_input(INPUT_POST, 'remail', FILTER_SANITIZE_EMAIL);
  if ($remail === '') {
    $error = 'blank';
  } elseif (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $remail)) {
    $error= 'check';
  } else {
    $stmt = $pdo->prepare(
      "SELECT COUNT(*)
      FROM members
      WHERE email = :email"
    );
    $stmt->bindvalue(
      ':email', $remail, PDO::PARAM_STR
    );
    $stmt->execute();
    $counts = $stmt->fetch();
    if ($counts['COUNT(*)'] > 0) {
      $error = 'duplicate';
    }
  }

  if (empty($error)) {
    // メール送信処理
    $urltoken = hash('sha256',uniqid(rand(),1));
    $url = "https://dreamuseum.com/change_email.php?urltoken=".$urltoken;

    try {
      $sql = "INSERT INTO pre_emails (member_id, urltoken, email, date, flag) VALUES (:member_id, :urltoken, :remail, now(), '0')";
      $stm = $pdo->prepare($sql);
      $stm->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
      $stm->bindValue(':remail', $remail, PDO::PARAM_STR);
      $stm->bindValue(':member_id', $id, PDO::PARAM_STR);
      $stm->execute();

      $to = $remail;
      $subject = '【自動返信】メールアドレス再設定のご案内';
      $body = <<< EOM
      24時間以内に下記URLへアクセスし、メールアドレスを再設定してください。
      {$url}

      また、こちらのメールにご返信いただくことはできません。
      ご了承ください。
      お困りの際は、本サイト上の「お問い合わせ」にてご連絡ください。
      EOM;
      $from_name = 'DreaMuseum';
      $from_email = 're_email@dreamuseum.com';
      $pfrom = "-f $from_email";
      $headers = 'From: ' . ($from_name). ' <' . $from_email. '>';
      $pdo = null;
      mb_language('ja');
      mb_internal_encoding('UTF-8');
      if (mb_send_mail($to ,$subject ,$body , $headers, $pfrom)) {
        header("Location: success_preemail.php");
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



$title = 'メールアドレス変更 - ';
$this_css = 'form';
$setting = 'select';
include('../app/_parts/_header.php');

?>

<?php if (isset($error['try']) && $error['try'] === 'failure'): ?>
<div class="forms">
  <div class="form_title">
    <h1>メールアドレス変更</h1>
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
    <h1>メールアドレス変更</h1>
  </div>
  <div class="form">
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off" onsubmit="return settings()">
      <dl class="form_item check">
        <dt>現在のメールアドレス</dt>
        <dd><i class="bi bi-chevron-double-right"></i><?= h($member['email']); ?></dd>
      </dl>
      <div class="form_item">
        <label for="email">新しいメールアドレス</label>
        <input type="text" name="remail" id="email">
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
        <button>再設定用メール送信</button>
        <input type="hidden" name="type" value="remail">
        <input type="hidden" name="token" value="<?=  h($_SESSION['token']); ?>">
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<?php

include('../app/_parts/_footer.php');

?>
<script>
  function settings() {
    const select = confirm('本当に更新しますか？');
    return select;
  }
</script>
<script src="js/main.js"></script>
</body>
</html>