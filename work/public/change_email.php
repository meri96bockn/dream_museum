<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');

if (!isset($_GET['urltoken'])) {
  header("Location: change_preemail.php");
  exit;
} else {
  $urltoken = isset($_GET["urltoken"]) ? $_GET["urltoken"] : NULL;
  $error = [];

  $sql = "SELECT * FROM pre_emails WHERE urltoken=(:urltoken)";
  $stm = $pdo->prepare($sql);
  $stm->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
  $stm->execute();
  $row = $stm->fetch(); 

  if ($row['flag'] === 1) {
    header("Location: change_preemail.php");
    exit;
  } elseif ($urltoken === '') {
    $error['urltoken'] = "notfind";
  } else {
    try {
      $sql = "SELECT email FROM pre_emails WHERE urltoken=(:urltoken) AND flag = 0 AND date > now() - interval 24 hour";
      $stm = $pdo->prepare($sql);
      $stm->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
      $stm->execute();
      $row_count = $stm->rowCount();
      if ($row_count === 1) {
        $email_array = $stm->fetch();
        $remail = $email_array["email"];
      } else {
        $error['urltoken'] = "notuse";
      }
    } catch (PDOException $e) {
      $error['try'] = "failure";
      $error_message = 'Error:'. $e->getMessage();
      error_log($error_message, 1, "error@dreamuseum.com");
    }
  }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type']) &&  $_POST['type'] === 'remail' ) {
  try{
    $stmt = $pdo->prepare(
      "UPDATE members SET email = :remail WHERE id = :member_id"
    );
    $stmt->execute(
      [ 'remail' => $remail,
      'member_id' => $row['member_id'] ]
    );

    $sql = "UPDATE pre_emails SET flag = 1 WHERE email=:remail";
    $stm = $pdo->prepare($sql);
    $stm->bindValue(':remail', $remail, PDO::PARAM_STR);
    $stm->execute();

    /* メール送信 */

    $to = $remail;
    $subject = '【自動返信】メールアドレスの再設定が完了しました';
    $body = <<< EOM
    メールアドレスの再設定にご協力いただきありがとうございました。
    下記URLからログインできます。
    https://dreamuseum.com/login.php

    また、こちらのメールにご返信いただくことはできません。
    ご了承ください。
    お困りの際は、本サービスの「お問い合わせ」にてご連絡ください。
    EOM;
    $from_name = 'DreaMuseum';
    $from_email = 're_email@dreamuseum.com';
    $pfrom = "-f $from_email";
    $headers = 'From: ' . ($from_name). ' <' . $from_email. '>';

    mb_language('ja');
    mb_internal_encoding('UTF-8');
    if (mb_send_mail($to, $subject, $body, $headers, $pfrom))
    {
      $stm = null;
      $_SESSION = array();
      if (isset($_COOKIE["PHPSESSID"])) {
          setcookie("PHPSESSID", '', time() - 1800, '/');
      }
      session_destroy();
      header('Location: login.php');
      exit;
    }
  } catch (PDOException $e) {
    $error['try'] = "failure";
    $error_message = 'Error:'. $e->getMessage();
    error_log($error_message, 1, "error@dreamuseum.com");
  }
}


$title = 'メールアドレス変更 - ';
$this_css = 'form';
$setting = 'select';
include('../app/_parts/_header.php');

?>

<?php if (isset($error['urltoken'])): ?>
<div class="forms">
  <div class="form_title">
    <h1>メールアドレス変更</h1>
  </div>
  <div class="form">
    <div class="form_item">
      <?php if (isset($error['urltoken']) && $error['urltoken'] === "notfind"): ?>
      <div class="error">
        <p>* トークンがありません。</p>
      </div>
      <?php endif; ?>
      <?php if (isset($error['urltoken']) && $error['urltoken'] === "notuse"): ?>
      <div class="error">
        <p>* このURLはご利用できません。有効期限が過ぎたかURLが間違えている可能性がございます。もう一度登録をやりなおして下さい。</p>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php elseif (isset($error['try']) && $error['try'] === 'failure'): ?>
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
        <dt>新しいメールアドレス</dt>
        <dd><i class="bi bi-chevron-double-right"></i><?= h($remail); ?></dd>
      </dl>
      
      <div class="button">
        <button>更新</button>
        <input type="hidden" name="type" value="remail">
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
