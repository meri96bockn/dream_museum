<?php
session_start();
require_once(__DIR__ . '/../app/config.php');
require('../app/functions.php');


if (isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
} else {
  header('Location: login.php');
  unset($_SESSION['form']);
  exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

  $_SESSION['id'] = $form['id'];
  $_SESSION['name'] = $form['name'];
  unset($_SESSION['form']);
  header('Location: my_page.php');
  exit();
}

$title = '投稿内容確認 - ';
$this_css = 'post';
include('../app/_parts/_header.php');

?>

<div class="container">
  <form action="" method="post" enctype="multipart/form-data">
    <h1>
      <?php if (isset($form['tag']) && ($form['tag'] === 'yes_tag')): ?>
        <i class="bi bi-award"></i>
      <?php endif; ?>
      <?= h($form['title']); ?>
    </h1>
    <div>
      <p>
        <?php if (isset($form['emotion']) && !($form['emotion'] === '')): ?>
          <i class="bi bi-tag-fill"></i>
        <?= h($form['emotion']);?></p>
        <?php endif; ?>
      </p>
      <p>
        <i class="bi bi-person-circle"></i>
        <?= h($form['name']); ?>
      </p>
      <p><?= date("Y/m/d H:i"); ?></p>
    </div>
    <div class="content">
      <?= nl2br(h($form['content'])); ?>
    </div>
    <div class="button">
      <button type="button" onclick=location.href="my_page.php?action=rewrite">変更</button>
      <button>投稿</button>
    </div>
  </form>
</div>


<?php

include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
</body>
</html>