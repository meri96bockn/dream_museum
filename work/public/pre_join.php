<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');
createToken();

$email = '';
$re_password = '';
$error = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
  validateToken();

  $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

  if ($email === '') {
    $error = 'blank';
  // } elseif (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email)) {
  //   $error= 'check';
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
    // メール送信処理
    $urltoken = hash('sha256',uniqid(rand(),1));
    $url = "http://localhost:8562/join.php?urltoken=".$urltoken;

    try{
      $sql = "INSERT INTO pre_members (urltoken, email, date, flag) VALUES (:urltoken, :email, now(), '0')";
      $stm = $pdo->prepare($sql);
      $stm->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
      $stm->bindValue(':email', $email, PDO::PARAM_STR);
      $stm->execute();
      $pdo = null;
      $message = "メールをお送りしました。24時間以内にメールに記載されたURLからご登録下さい。";
      $_SESSION['message'] = $message;
      $_SESSION['url'] = $url;
      header("Location: success_pre.php");

  }catch (PDOException $e){
      print('Error:'.$e->getMessage());
      die();
  }

/*
       * メール送信処理
       * 登録されたメールアドレスへメールをお送りする。
       * 今回はメール送信はしないためコメント
       */
       /*  
   	$mailTo = $mail;
       $body = <<< EOM
       この度はご登録いただきありがとうございます。
       24時間以内に下記のURLからご登録下さい。
       {$url}
EOM;
       mb_language('ja');
       mb_internal_encoding('UTF-8');
   
       //Fromヘッダーを作成
       $header = 'From: ' . mb_encode_mimeheader($companyname). ' <' . $companymail. '>';
   
       if(mb_send_mail($mailTo, $registation_subject, $body, $header, '-f'. $companymail)){      
           //セッション変数を全て解除
           $_SESSION = array();
           //クッキーの削除
           if (isset($_COOKIE["PHPSESSID"])) {
               setcookie("PHPSESSID", '', time() - 1800, '/');
           }
           //セッションを破棄する
           session_destroy();
           $message = "メールをお送りしました。24時間以内にメールに記載されたURLからご登録下さい。";
       }
       */
    }
}


$title = '仮会員登録 - ';
$this_css = 'form';
include(__DIR__ . '/../app/_parts/_header.php');
?>

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


<?php

include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
</body>
</html>