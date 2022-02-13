<?php
require_once(__DIR__ . '/../app/config.php');
require('../app/functions.php');


$post_id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);
if (isset($_SESSION['name']) && isset($_SESSION['id']) && isset($post_id)) {
  createToken();
  $id = $_SESSION['id'];
  $name = $_SESSION['name'];
}


$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :post_id");
$stmt->execute([':post_id' => $post_id]);
$today_dream = $stmt->fetch();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type']) && $_POST['type'] === 'action') {
  $stmt = $pdo->prepare(
    "UPDATE posts SET tag = 'no_tag', emotion = '' WHERE id = :post_id AND member_id = :members_id"
  );
  $stmt->execute([
    ':post_id' => $post_id,
    ':members_id' => $id
  ]);
  header('Location: dreams.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type']) &&  $_POST['type'] === 'bye') {
  $stmt = $pdo->prepare(
    "DELETE FROM posts WHERE id = :post_id AND member_id = :members_id"
  );
  $stmt->execute([
    ':post_id' => $post_id,
    ':members_id' => $id
  ]);
  header('Location: dreams.php');
  }


$title = 'むかしの夢 - ';
$this_css = 'post';
$dreams = 'select';
include('../app/_parts/_header.php');

?>
<div class="container">
  <?php if ($today_dream === [] || $today_dream['tag'] === 'no_tag'): ?>
    <p>この夢日記は非公開または削除されたか、ご指定のURLが間違っている可能性があります。</p>
    <?php else: ?>
      <div class="tag">
        <?php if (isset($today_dream['tag']) && ($today_dream['tag'] === 'yes_tag')): ?>
          <i class="bi bi-award gold"></i>
          <?php endif; ?>
          <?php if (isset($today_dream['tag']) && ($today_dream['tag'] === 'no_tag')): ?>
            <i class="bi bi-award normal"></i>
            <?php endif; ?>
            <div class="emotion">
              <?php if ($today_dream['tag'] === 'yes_tag'): ?>
                <i class="bi bi-tag-fill"></i><?= h($today_dream['emotion']); ?>
                <?php endif; ?>
              </div>
            </div>
            <div>
              <h1>
                <?= h($today_dream['title']); ?>
              </h1>
            </div>
            <div class="content">
              <?= nl2br(h($today_dream['content'])); ?>
            </div>
            <div class="info">
              <p>
                <?= h($today_dream['created']); ?>
              </p>
              <p>
                <i class="bi bi-person-circle"></i>
                寄贈者さん
              </p>
            </div>
            <div class="button">
              <?php if (isset($id) && $id === $today_dream['member_id']): ?>
                <form action="" method="POST" id="action" onsubmit="return tag()">
                  <button form='action'>非公開にする</button>
                  <input type="hidden" name="type" value="action">
                </form>
                <form action="" method="POST" id="delete" onsubmit="return del()">
                  <button form="delete">削除</button>
                  <input type="hidden" name="type" value="bye">
                </form>
                <?php endif; ?>
              </div>
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

  function tag() {
    const select = confirm('タグを外して非公開にしますか？');
    return select;
  }
</script>
<script src="js/main.js"></script>
</body>
</html>