<?php
require_once(__DIR__ . '/../app/config.php');
require('../app/functions.php');

$post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);
if (isset($_SESSION['name']) && isset($_SESSION['id']) && isset($post_id)) {
  createToken();
  $id = $_SESSION['id'];
  $name = $_SESSION['name'];
} else {
  header('Location: login.php');
  exit;
}


$stmt = $pdo->prepare(
  "SELECT * FROM posts WHERE id = :post_id AND member_id = :members_id"
);
$stmt->execute([
  ':post_id' => $post_id,
  ':members_id' => $id
]);
$past_dreams = $stmt->fetchAll();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type']) && $_POST['type'] === 'action') {
  try {
    $stmt = $pdo->prepare(
      "UPDATE posts SET tag = 'no_tag', emotion = '' WHERE id = :post_id AND member_id = :members_id"
    );
    $stmt->execute([
      'post_id' => $post_id,
      'members_id' => $id
    ]);
    header('Location: my_page.php');
  } catch (PDOException $e) {
    $pdo->rollBack();
    $error['try'] = "failure";
    $error_message = 'Error:'. $e->getMessage();
    error_log($error_message, 1, "error@dreamuseum.com");
    die();
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type']) &&  $_POST['type'] === 'bye') {
  try {
    $stmt = $pdo->prepare(
      "DELETE FROM posts WHERE id = :post_id AND member_id = :members_id"
    );
    $stmt->execute([
      ':post_id' => $post_id,
      ':members_id' => $id
    ]);
    header('Location: my_page.php');
  } catch (PDOException $e) {
    $pdo->rollBack();
    $error['try'] = "failure";
    $error_message = 'Error:'. $e->getMessage();
    error_log($error_message, 1, "error@dreamuseum.com");
    die();
  }
}

$title = 'むかしの夢 - ';
$this_css = 'post';
$my_page = 'select';
include('../app/_parts/_header.php');

?>

<?php if (isset($error['try']) && $error['try'] === 'failure'): ?>
<div class="container">
    <div class="error">
      <p>* お手数ですが、もう一度やり直してください</p>
    </div>
</div>

<?php else: ?>
<div class="container">
  <?php if ($past_dreams === []): ?>
    <p>この夢日記は削除されたか、ご指定のURLが間違っている可能性があります。</p>
  <?php else: ?>
    <?php foreach ($past_dreams as $past_dream):?>
      <div class="tag">
        <?php if (isset($past_dream['tag']) && ($past_dream['tag'] === 'yes_tag')): ?>
            <i class="bi bi-award gold"></i>
        <?php endif; ?>
        <?php if (isset($past_dream['tag']) && ($past_dream['tag'] === 'no_tag')): ?>
            <i class="bi bi-award normal"></i>
        <?php endif; ?>
        <div class="emotion">
          <?php if ($past_dream['tag'] === 'yes_tag'): ?>
            <i class="bi bi-tag-fill"></i><?= h($past_dream['emotion']); ?>
          <?php endif; ?>
        </div>
      </div>
      <div>
        <h1>
        <?= h($past_dream['title']); ?>
        </h1>
      </div>
      <div class="content">
        <?= nl2br(h($past_dream['content'])); ?>
      </div>
      <div class="info">
        <p>
        <?= h($past_dream['created']); ?>
        </p>
        <p>
          <i class="bi bi-person-circle"></i>
          <?= h($name); ?>
        </p>
      </div>
      <div class="button">
        <?php if ($past_dream['tag'] === 'yes_tag'): ?>
          <form action="" method="POST" id="action" onsubmit="return tag()">
            <button form='action'>非公開にする</button>
            <input type="hidden" name="type" value="action">
          </form>
        <?php endif;?>
          <form action="" method="POST" id="delete" onsubmit="return del()">
            <button form="delete">削除</button>
            <input type="hidden" name="type" value="bye">
          </form>
        </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>
<?php endif; ?>


<?php
include('../app/_parts/_footer.php');

?>
<script>
  function del() {
    const select = confirm('本当に削除しますか？');
    return select;
  }

  function tag() {
    const select = confirm('タグを外して非公開にしますか？');
    return select;
  }
</script>
<script src="js/main.js"></script>
</body>
</html>