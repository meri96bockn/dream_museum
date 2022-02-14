<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');
var_dump($_SESSION['urltoken']);
var_dump($urltoken);
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
    //プレースホルダへ実際の値を設定する
    $stm->bindValue(':email', $form['email'], PDO::PARAM_STR);
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
      header('Location: success.php');
      exit();
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
var_dump($_SESSION['urltoken']);
var_dump($urltoken);
include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
</body>
</html>