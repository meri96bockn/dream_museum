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
  }

  if (empty($error)) {
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

    if ($counts['COUNT(*)'] !== 1) {
      header("Location: success_repasswd.php");
      exit;
    } else {
      $urltoken = hash('sha256',uniqid(rand(),1));
      $url = "https://dreamuseum.com/re_passwd.php?urltoken=".$urltoken;
      try{
        $sql = "INSERT INTO re_passwd (urltoken, email, date, flag) VALUES (:urltoken, :email, now(), '0')";
        $stm = $pdo->prepare($sql);
        $stm->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
        $stm->bindValue(':email', $email, PDO::PARAM_STR);
        $stm->execute();
        $pdo = null;
        /* メール送信 */
        $to = $email;
        $subject = '【自動返信】パスワード再設定のご案内';
        $body = <<< EOM
        24時間以内に下記URLへアクセスし、パスワードを再設定してください。
        {$url}

        また、こちらのメールにご返信いただくことはできません。
        ご了承ください。
        お困りの際は、本サイト上の「お問い合わせ」にてご連絡ください。
        EOM;
        $from_name = 'DreaMuseum';
        $from_email = 're_passwd@dreamuseum.com';
        $pfrom = "-f $from_email";
        $headers = 'From: ' . ($from_name). ' <' . $from_email. '>';
        mb_language('ja');
        mb_internal_encoding('UTF-8');
        if (mb_send_mail($to ,$subject ,$body , $headers, $pfrom)) {
          header("Location: success_repasswd.php");
          exit;
        }
      } catch (PDOException $e) {
          $error['try'] = "failure";
          $error_message = 'Error:'. $e->getMessage();
          error_log($error_message, 1, "error@dreamuseum.com");
      }
    }
  }
}

$title = 'パスワード再設定 - ';
$this_css = 'form';
include(__DIR__ . '/../app/_parts/_header.php');
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
      <div class="form_item caution">
        <p>再設定用のメール案内をお送りいたしますので、<br>現在ご登録されているメールアドレスを入力してください。</p>
      </div>
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
        </div>
        <div class="button">
          <button>再設定用メール送信</button>
          <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
        </div>
      </form>
    </div>
  </div>
<?php endif; ?>

<?php
include(__DIR__ . '/../app/_parts/_footer.php');
?>
<script src="js/main.js"></script>
</body>
</html>