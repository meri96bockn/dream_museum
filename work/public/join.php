<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');
createToken();
$form = [
  'name' => '',
  'email' => '',
  'password' => ''
];
$re_password = '';
$error = [];

if(isset($_GET['urltoken'])) {
  $urltoken = isset($_GET["urltoken"]) ? $_GET["urltoken"] : NULL;
  $sql = "SELECT flag FROM pre_members WHERE urltoken=(:urltoken)";
  $stm = $pdo->prepare($sql);
  $stm->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
  $stm->execute();
  $row = $stm->fetch();

  if ($row['flag'] === 1) {
    header("Location: pre_join.php");
    exit;
  } elseif ($urltoken === '') {
    $error['urltoken'] = "notfind";
  } else {
    try {
      $sql = "SELECT email FROM pre_members WHERE urltoken=(:urltoken) AND flag = 0 AND date > now() - interval 24 hour";
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
} elseif (!isset($_GET['urltoken'])) {
  header("Location: pre_join.php");
}

if (isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
  validateToken();

  $form['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  if (!preg_match('/\A[a-z\d]{1,100}+\z/i', $form['name'])) {
    $error['name'] = 'alphanumeric';
  }

  $form['email'] = $email;

  $form['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  if (!preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}+\z/i', $form['password'])) {
    $error['password'] = 'alphanumeric';
    $form['password'] = '';
  } 
  
  $re_password = filter_input(INPUT_POST, 're_password', FILTER_SANITIZE_STRING);
  if ($form['password'] !== $re_password){
    $error['re_password'] = 'miss';
    $re_password = '';
  }


  if (empty($error)) {
    $_SESSION['form'] = $form;
    $_SESSION['urltoken'] = $urltoken;
    header('Location: check.php');
    exit;
  }
}


if ($form['name'] === '') {
  $error['name'] = 'alphanumeric';
}
if ($form['password'] === '') {
  $error['password'] = 'alphanumeric';
}


$title = '本会員登録 - ';
$this_css = 'form';
include(__DIR__ . '/../app/_parts/_header.php');
?>

<?php if (isset($error['urltoken'])): ?>
<div class="forms">
  <div class="form_title">
    <h1>本会員登録</h1>
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
    <h1>本会員登録</h1>
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
    <h1>本会員登録</h1>
  </div>
  <div class="form">
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
      <div class="form_item">
        <label for="name">ユーザーネーム</label>
        <input type="text" name="name" id="name" maxlength="255" placeholder="（例）yume3" value="<?= h($form['name']); ?>">
      </div>
      <div class="error">
        <?php if (isset($error['name']) && $error['name'] === 'alphanumeric'):?>
          <p>* ユーザーネームは半角英数字で入力してください</p>
        <?php endif; ?>
      </div>
      <dl class="form_item check">
        <dt>メールアドレス</dt>
        <dd><i class="bi bi-chevron-double-right"></i><?= h($email); ?></dd>
      </dl>
      <div class="form_item">
        <label for="password">パスワード</label>
        <input type="password" name="password" id="password" placeholder="（例）yume36ko" value="<?= h($form['password']); ?>">
      </div>
      <div class="error">
        <?php if (isset($error['password']) && $error['password'] === 'alphanumeric'): ?>
        <p>* パスワードは半角英数字を1文字ずつ組み合わせ、8文字以上で入力してください</p>
        <?php endif; ?>
      </div>
      <div class="form_item">
        <label for="re_password">パスワード（再入力）</label>
        <input type="password" name="re_password" id="re_password" placeholder="（例）yume36ko" value="<?= h($re_password); ?>">
      </div>
      <div class="error">
        <?php if (isset($error['re_password']) && $error['re_password'] === 'miss'):?>
        <p>* パスワードが一致していません</p>
        <?php endif; ?>
      </div>
      <div class="button">
        <button>入力内容を確認する</button>
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