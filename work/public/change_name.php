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
  $error = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type']) &&  $_POST['type'] === 'rename' ) {
  validateToken();

  $rename = filter_input(INPUT_POST, 'rename', FILTER_SANITIZE_STRING);
  if (!preg_match('/\A[a-z\d]{1,100}+\z/i', $rename)) {
    $error = 'alphanumeric';
  }

  if (empty($error)) {
    try {
      $pdo->beginTransaction();
      $stmt = $pdo->prepare(
        "UPDATE members SET username = :username WHERE id = :id"
      );
      $res = $stmt->execute(
        [ 'username' => $rename,
        'id' => $id ]
      );
      if( $res ) {
        $pdo->commit();
        unset($_SESSION['token']);
        $_SESSION['name'] = $rename;
      }
    } catch (PDOException $e) {
      $pdo->rollBack();
      $error['try'] = "failure";
      $error_message = 'Error:'. $e->getMessage();
      error_log($error_message, 1, "error@dreamuseum.com");
    } finally {
      $pdo = null;
    }
  }
}


$title = 'ユーザーネーム変更 - ';
$this_css = 'form';
$setting = 'select';
include('../app/_parts/_header.php');

?>

<?php if (isset($error['try']) && $error['try'] === 'failure'): ?>
<div class="forms">
  <div class="form_title">
    <h1>ユーザーネーム変更</h1>
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
    <h1>ユーザーネーム変更</h1>
  </div>
  <div class="form">
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off" onsubmit="return settings()">
      <dl class="form_item check">
        <dt>現在のユーザーネーム</dt>
        <dd><i class="bi bi-chevron-double-right"></i><?= h($_SESSION['name']); ?></dd>
      </dl>
      <div class="form_item">
        <label for="name">新しいユーザーネーム</label>
        <input type="text" name="rename" id="name">
      </div>
      <div class="error">
        <?php if (isset($error) && $error === 'alphanumeric'):?>
          <p>* ユーザーネームは半角英数字で入力してください</p>
        <?php endif; ?>
      </div>

      <div class="button">
        <button>更新</button>
        <input type="hidden" name="type" value="rename">
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