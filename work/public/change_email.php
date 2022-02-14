<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');

if (!isset($_SESSION['name']) &&
!isset($_SESSION['id'])) {
  header('Location: login.php');
  exit;
} elseif (isset($_GET['urltoken'])) {
  $urltoken = isset($_GET["urltoken"]) ? $_GET["urltoken"] : NULL;
  $id = $_SESSION['id'];
  $name = $_SESSION['name'];

  $sql = "SELECT flag FROM pre_emails WHERE urltoken=(:urltoken)";
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
      //データベース接続切断
      $stm = null;
    } catch (PDOException $e) {
      print('Error:'.$e->getMessage());
      die();
    }
  }
} elseif (!isset($_GET['urltoken'])) {
    header("Location: change_preemail.php");
  }



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type']) &&  $_POST['type'] === 'remail' ) {
  try{
    $stmt = $pdo->prepare(
      "UPDATE members SET email = :remail WHERE id = :id"
    );
    $stmt->execute(
      [ 'remail' => $remail,
      'id' => $id ]
    );

    $sql = "UPDATE pre_emails SET flag = 1 WHERE email=:remail";
    $stm = $pdo->prepare($sql);
    //プレースホルダへ実際の値を設定する
    $stm->bindValue(':remail', $remail, PDO::PARAM_STR);
    $stm->execute();

    /*
      * 登録ユーザと管理者へ仮登録されたメール送信
        */
  /* 
      $mailTo = $mail.','.$companymail;
        $body = <<< EOM
        この度はご登録いただきありがとうございます。本登録致しました。
        EOM;
        mb_language('ja');
        mb_internal_encoding('UTF-8');
    
        //Fromヘッダーを作成
        $header = 'From: ' . mb_encode_mimeheader($companyname). ' <' . $companymail. '>';
    
        if(mb_send_mail($mailTo, $registation_mail_subject, $body, $header, '-f'. $companymail)){          
            $message['success'] = "会員登録しました";
        }else{
            $errors['mail_error'] = "メールの送信に失敗しました。";
      }	
  */
      //データベース接続切断
      $stm = null;

      // //セッション変数を全て解除
      // $_SESSION = array();
      // //セッションクッキーの削除
      // if (isset($_COOKIE["PHPSESSID"])) {
      //     setcookie("PHPSESSID", '', time() - 1800, '/');
      // }
      // //セッションを破棄する
      // session_destroy();
      
    }catch (PDOException $e){
      //トランザクション取り消し（ロールバック）
      $pdo->rollBack();
      $errors['error'] = "もう一度やりなおして下さい。";
      print('Error:'.$e->getMessage());
    }
      $urltoken = '';
  
        $_SESSION['form'] = $form;
        header('Location: change_preemail.php');
        exit();
        unset($_SESSION['token']);
        $_SESSION['remail'] = $remail;
}


$title = 'ユーザーネーム変更 - ';
$this_css = 'form';
$setting = 'select';
include('../app/_parts/_header.php');

?>
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
        <input type="hidden" name="token" value="<?=  h($_SESSION['token']); ?>">
      </div>
    </form>
  </div>
</div>


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