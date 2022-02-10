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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$stmt = $pdo->prepare(
  "DELETE FROM posts WHERE id = :post_id AND member_id = :members_id"
);
$stmt->execute([
  ':post_id' => $post_id,
  ':members_id' => $id
]);

header('Location: my_page.php');
}


$title = 'むかしの夢 - ';
$this_css = 'post';
$index = '';
$dreams = '';
$howto = '';
$my_page = '';
include('../app/_parts/_header.php');

?>

<div class="container">
  <?php if ($past_dreams === []): ?>
    <p>この夢日記は削除されたか、ご指定のURLが間違っている可能性があります。</p>
  <?php else: ?>
    <?php foreach ($past_dreams as $past_dream):?>
      <h1>
        <?php if ($past_dream['tag'] === 'yes_tag'): ?>
          <i class="bi bi-award"></i>
        <?php endif; ?>
        <?= h($past_dream['title']); ?>
      </h1>
      <div>
        <p>
          <?php if ($past_dream['tag'] === 'yes_tag'): ?>
            <i class="bi bi-tag-fill"></i>
          <?= h($past_dream['emotion']); ?>
          <?php endif; ?>
        </p>
        <p>
          <i class="bi bi-person-circle"></i>
          <?= h($name); ?>
        </p>
        <p><?= h($past_dream['created']); ?></p>
      </div>
      <div class="content">
        <?= h($past_dream['content']); ?>
      </div>
      <div class="button">
        <form action="" method="POST" onsubmit="return del()">
          <button>削除</button>
      </form>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

<?php

include('../app/_parts/_footer.php');

?>
<script>
  function del() {
    const select = confirm('本当に削除しますか？');
    return select;
  }
</script>
<script src="js/main.js"></script>
</body>
</html>