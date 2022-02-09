<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');




// きょうの夢 fun
  $stmt = $pdo->prepare(
    "SELECT title, id from posts where emotion = 'fun' order by rand() limit 3;"
  );
  $stmt->execute();
  $funs = $stmt->fetchAll();
  
  // きょうの夢 happy
  $stmt = $pdo->prepare(
    "SELECT title, id from posts where emotion = 'happy' order by rand() limit 3;"
  );
  $stmt->execute();
  $happies = $stmt->fetchAll();

  // きょうの夢 hard
  $stmt = $pdo->prepare(
    "SELECT title, id from posts where emotion = 'hard' order by rand() limit 3;"
  );
  $stmt->execute();
  $hards = $stmt->fetchAll();

  // きょうの夢 scary
  $stmt = $pdo->prepare(
    "SELECT title, id from posts where emotion = 'scary' order by rand() limit 3;"
  );
  $stmt->execute();
  $scaries = $stmt->fetchAll();

  // きょうの夢 forget
  $stmt = $pdo->prepare(
    "SELECT title, id from posts where emotion = 'forget' order by rand() limit 3;"
  );
  $stmt->execute();
  $forgets = $stmt->fetchAll();





$title = 'きょうの夢 - ';
$this_css = 'dreams';
$index = '';
$dreams = 'select';
$howto = '';
$my_page = '';
include('../app/_parts/_header.php');

?>

  <main>
    <div class="dreams_container">
      <h1>きょうの夢</h1>
      <div class="emotions_area">
        <div class="emotion_dreams">
          <h2 class="emotion">たのしい</h2>
          <ul class="dreams">
            <?php foreach ($funs as $fun):?>
              <li>
              <a href="today_dream.php?post_id=<?= h($fun['id']); ?>">
                <?= h($fun['title']); ?>
              </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="emotion_dreams">
          <h2 class="emotion">しあわせ</h2>
          <ul class="dreams">
            <?php foreach ($happies as $happy):?>
              <li>
              <a href="today_dream.php?post_id=<?= h($happy['id']); ?>">
                <?= h($happy['title']); ?>
              </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="emotion_dreams">
          <h2 class="emotion">しんどい</h2>
          <ul class="dreams">
            <?php foreach ($hards as $hard):?>
              <li>
              <a href="today_dream.php?post_id=<?= h($hard['id']); ?>">
                <?= h($hard['title']); ?>
              </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="emotion_dreams">
          <h2 class="emotion">こわい</h2>
          <ul class="dreams">
            <?php foreach ($scaries as $scary):?>
              <li>
              <a href="today_dream.php?post_id=<?= h($scary['id']); ?>">
                <?= h($scary['title']); ?>
              </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="emotion_dreams">
          <h2 class="emotion">忘れたい</h2>
          <ul class="dreams">
            <?php foreach ($forgets as $forget):?>
              <li>
              <a href="today_dream.php?post_id=<?= h($forget['id']); ?>">
                <?= h($forget['title']); ?>
              </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div> <!-- emotions_area -->
    </div> <!-- dreams_container -->
  </main>

  <?php

include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
</body>
</html>