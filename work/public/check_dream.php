<?php
require_once(__DIR__ . '/../app/config.php');
require('../app/functions.php');

if (isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
} else {
  header('Location: index.php');
  exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  validateToken();
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
      'member_id' => $form['id']
    ]
  );

  unset($_SESSION['token']);
  unset($_SESSION['form']);
  createToken();
  header('Location: success_post.php');
  exit();
}

$title = '夢日記内容確認 - ';
$this_css = 'post';
$my_page = 'select';
include('../app/_parts/_header.php');
?>

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
        <i class="bi bi-tag-fill"></i><?= h($form['emotion']);?>
      <?php endif; ?>
    </div>
  </div>
  <div>
    <h1>
      <?= h($form['title']); ?>
    </h1>
  </div>

  
  <div class="content">
    <?= nl2br(h($form['content'])); ?>
  </div>
  <div class="info">
    <p>
      <?= date("Y-m-d H:i:s"); ?>
    </p>
    <p>
      <i class="bi bi-person-circle"></i>
      <?= h($form['name']); ?>
    </p>
     
  </div>
  <div class="button">
    <button type="button" onclick=location.href="my_page.php?action=rewrite">変更</button>
    <button>記録</button>
    <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
  </div>
  </form>
</div>


<?php

include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
</body>
</html>