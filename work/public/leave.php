<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');

if (!isset($_SESSION['name']) && !isset($_SESSION['id'])) {
  header('Location: login.php');
  exit;
} else {
  createToken();
  $id = $_SESSION['id'];
  $name = $_SESSION['name'];
  $error = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type']) && $_POST['type'] === 'leave' ) {
  validateToken();
  $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
  $stmt = $pdo->prepare(
    "SELECT password
    FROM members
    WHERE id = :id 
    limit 1"
  );
  $stmt->execute(
    [ 'id' => $id ]
  );
  $row = $stmt->fetch();
  if (!password_verify($password, $row["password"])) {
    $error = 'notmatch';
  }

  if (empty($error)) {
    try{
      $stmt = $pdo->prepare(
        "DELETE FROM members WHERE id = :id"
      );
      $stmt->execute(
        [ 'id' => $id ]
      );
      $stmt = $pdo->prepare(
        "DELETE FROM posts WHERE member_id = :id"
      );
      $stmt->execute(
        [ 'id' => $id ]
      );
      $_SESSION = array();
      if (isset($_COOKIE["PHPSESSID"])) {
        setcookie("PHPSESSID", '', time() - 1800, '/');
      }
      session_destroy();
      header('Location: index.php');
      exit;
    } catch (PDOException $e) {
      $pdo->rollBack();
      $error['try'] = "failure";
      $error_message = 'Error:'. $e->getMessage();
      error_log($error_message, 1, "error@dreamuseum.com");
    }
  }
}

$title = '退会- ';
$this_css = 'form';
$setting = 'select';
include(__DIR__ . '/../app/_parts/_header.php');
?>

<?php if (isset($error['try']) && $error['try'] === 'failure'): ?>
  <div class="forms">
    <div class="form_title">
      <h1>退会</h1>
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
      <h1>退会</h1>
    </div>
    <div class="form">
      <form action="" method="post" enctype="multipart/form-data" autocomplete="off" onsubmit="return settings()">
        <div class="form_item caution">
          <p>退会すると、夢日記はすべて削除されます。
            <br>退会してよろしければ、
            <br>パスワードを入力のうえ、
            <br>下のボタンをタップしてください。
          </p>
        </div>
        <div class="form_item leave">
          <label for="password">パスワード</label>
          <input type="password" name="password" id="password">
        </div>
        <div class="error">
          <?php if (isset($error) && $error === 'notmatch'): ?>
            <p>* 正しいパスワードを入力してください</p>
          <?php endif; ?>
        </div>
        <div class="button">
          <button>退会</button>
          <input type="hidden" name="type" value="leave">
          <input type="hidden" name="token" value="<?=  h($_SESSION['token']); ?>">
        </div>
      </form>
    </div>
  </div>
<?php endif; ?>

<?php
include(__DIR__ . '/../app/_parts/_footer.php');
?>
<script>
  function settings() {
    const select = confirm('本当に退会しますか？');
    return select;
  }
</script>
<script src="js/main.js"></script>
</body>
</html>