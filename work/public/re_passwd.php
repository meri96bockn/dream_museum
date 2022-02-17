<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');
createToken();

if (!isset($_GET['urltoken'])) {
  header("Location: forget_passwd.php");
  exit;
} else {
  $urltoken = isset($_GET["urltoken"]) ? $_GET["urltoken"] : NULL;
  $sql = "SELECT flag FROM re_passwd WHERE urltoken=(:urltoken)";
  $stm = $pdo->prepare($sql);
  $stm->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
  $stm->execute();
  $row = $stm->fetch();

  if ($row['flag'] === 1) {
    header("Location: forget_passwd.php");
    exit;
  } elseif ($urltoken === '') {
    $error['urltoken'] = "notfind";
  } else {
    try {
      $sql = "SELECT email FROM re_passwd WHERE urltoken=(:urltoken) AND flag = 0 AND date > now() - interval 24 hour";
      $stm = $pdo->prepare($sql);
      $stm->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
      $stm->execute();
      $row_count = $stm->rowCount();
      if ($row_count === 1) {
        $email_array = $stm->fetch();
        $email = $email_array["email"];
      } else {
        $error['urltoken'] = "notuse";
      }
      $stm = null;
    } catch (PDOException $e) {
      $error['try'] = "failure";
      $error_message = 'Error:'. $e->getMessage();
      error_log($error_message, 1, "error@dreamuseum.com");
      die();
    }
  }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type']) &&  $_POST['type'] === 'newpasswd' ) {
  validateToken();

  $new_password = filter_input(INPUT_POST, 'new_password', FILTER_SANITIZE_STRING);
  if (!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $new_password)) {
    $error['new_password'] = 'alphanumeric';
    $new_password = '';
  } 

  $re_password = filter_input(INPUT_POST, 're_password', FILTER_SANITIZE_STRING);
  if ($new_password !== $re_password){
    $error['re_password'] = 'miss';
    $re_password = '';
  }

  if (empty($error)) {
    try {
      $stmt = $pdo->prepare(
        "UPDATE members SET password = :newpasswd WHERE email = :email"
      );
      $stmt->execute(
        [ 'newpasswd' => password_hash($new_password, PASSWORD_DEFAULT),
        'email' => $email ]
      );
      $stm = $pdo->prepare(
        "UPDATE re_passwd SET flag = 1 WHERE email=:email"
      );
      $stm->bindValue(':email', $email, PDO::PARAM_STR);
      $stm->execute();

      $to = $email;
      $subject = '【自動返信】パスワードの再設定が完了しました';
      $body = <<< EOM
      パスワードの再設定にご協力いただきありがとうございました。
      下記URLからログインできます。
      https://dreamuseum.com/login.php

      また、こちらのメールにご返信いただくことはできません。
      ご了承ください。
      お困りの際は、本サービスの「お問い合わせ」にてご連絡ください。
      EOM;
      $from_name = 'DreaMuseum';
      $from_email = 're_passwd@dreamuseum.com';
      $pfrom = "-f $from_email";
      $headers = 'From: ' . ($from_name). ' <' . $from_email. '>';

      mb_language('ja');
      mb_internal_encoding('UTF-8');
      if (mb_send_mail($to, $subject, $body, $headers, $pfrom))
      {
        $urltoken = '';
        $password = "";
        $new_password = "";
        $re_password = "";
        unset($_SESSION['token']);
        $stm = null;
        header('Location: login.php');
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
}

  $title = 'パスワード再設定 - ';
  $this_css = 'form';
  $setting = 'select';
  include('../app/_parts/_header.php');
  
  ?>
  
<?php if (isset($error['try']) && $error['try'] === 'failure'): ?>
<div class="forms">
  <div class="form_title">
    <h1>パスワード再設定</h1>
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
      <h1>パスワード再設定</h1>
    </div>
    <div class="form">
      <form action="" method="post" enctype="multipart/form-data" autocomplete="off" onsubmit="return settings()">
        <div class="form_item">
          <label for="new_password">新しいパスワード</label>
          <input type="password" name="new_password" id="new_password" placeholder="（例）yume36ko" value="<?= h($new_password); ?>">
        </div>
        <div class="error">
          <?php if (isset($error['new_password']) && $error['new_password'] === 'alphanumeric'): ?>
          <p>* パスワードは半角英数字を1文字ずつ組み合わせ、8文字以上で入力してください</p>
          <?php endif; ?>
        </div>
        <div class="form_item">
          <label for="re_password">新しいパスワード（再入力）</label>
          <input type="password" name="re_password" id="re_password" placeholder="（例）yume36ko" value="<?= h($re_password); ?>">
        </div>
        <div class="error">
          <?php if (isset($error['re_password']) && $error['re_password'] === 'miss'):?>
          <p>* 新しいパスワードが一致していません</p>
          <?php endif; ?>
        </div>
        
        <div class="button">
          <button>更新</button>
          <input type="hidden" name="type" value="newpasswd">
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