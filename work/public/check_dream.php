<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');

if (isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
  $error = [];
} else {
  header('Location: index.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  validateToken();
  try {
    $stmt = $pdo->prepare(
      "INSERT INTO 
      posts (title, content, tag, emotion, member_id)
      VALUES (:title, :content, :tag, :emotion, :member_id)"
    );
    $stmt->execute(
      [ 'title' => $form['title'],
        'content' => $form['content'],
        'tag' => $form['tag'],
        'emotion' => $form['emotion'],
        'member_id' => $form['id'] ]
    );
    unset($_SESSION['token']);
    unset($_SESSION['form']);
    createToken();
    header('Location: success_post.php');
    exit;
  } catch (PDOException $e) {
    $pdo->rollBack();
    $error['try'] = "failure";
    $error_message = 'Error:'. $e->getMessage();
    error_log($error_message, 1, "error@dreamuseum.com");
  }
}

$title = '夢日記内容確認 - ';
$this_css = 'post';
$my_page = 'select';
include(__DIR__ . '/../app/_parts/_header.php');
?>

<?php if (isset($error['try']) && $error['try'] === 'failure'): ?>
  <div class="container">
    <div class="error">
      <p>* お手数ですが、もう一度やり直してください</p>
    </div>
  </div>

<?php else: ?>
  <div class="container">
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
      <div class="tag">
        <?php if (isset($form['tag']) && ($form['tag'] === 'yes_tag')): ?>
          <i class="bi bi-award gold"></i>
        <?php endif; ?>
        <?php if (isset($form['tag']) && ($form['tag'] === 'no_tag')): ?>
          <i class="bi bi-award"></i>
        <?php endif; ?>
        <div class="emotion">
          <?php if (isset($form['emotion']) && !($form['emotion'] === '')): ?>
            <i class="bi bi-tag-fill"></i><?= h($form['emotion']); ?>
          <?php endif; ?>
        </div>
      </div>
      <div>
        <h1><?= h($form['title']); ?></h1>
      </div>
      <div class="content">
        <?= nl2br(h($form['content'])); ?>
      </div>
      <div class="info">
        <p><?= date("Y-m-d H:i:s"); ?></p>
        <p><i class="bi bi-person-circle"></i><?= h($form['name']); ?></p>
      </div>
      <div class="button">
        <button type="button" onclick=location.href="my_page.php?action=rewrite">変更</button>
        <button>記録</button>
        <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
      </div>
    </form>
  </div>
<?php endif; ?>

<?php
include(__DIR__ . '/../app/_parts/_footer.php');
?>
<script src="js/main.js"></script>
</body>
</html>