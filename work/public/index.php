<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');

$title = '';
$this_css = 'top';
$index = 'select';
$dreams = '';
$howto = '';
$login = '';
include('../app/_parts/_header.php');

?>

<div class="hero_container">
<img src="img/hero.png" alt="夢のような博物館">
<div class="hero_message">
  <h1>Dream<br>Museum</h1>
  <p>夢を展示する博物館</p>
  <div class="join">
    <a href="join.php">新規登録</a>
  </div>
</div>
</div>

<div class="top_features">
<div class="top_feature">
  <div class="top_feature_title">
    <h1>夢を寄贈する</h1>
  </div>
  <div class="top_feature_message">
    <p>夢を寄贈しましょう</p>
    <p>眠っているあいだに見る、</p>
    <p>奇想天外な夢•••</p>
    <p>恐ろしい夢•••</p>
    <p>現実のような夢•••</p>
    <p>「きょうの夢」に展示します</p>
  </div>
  <div class="top_feature_icon">
    <i class="bi bi-image-fill"></i>
  </div>
  <div class="top_feature_link">
    <a href="dreams.php">寄贈された夢を見る</a>
  </div>
</div>

<div class="top_feature">
  <div class="top_feature_title">
    <h1>夢を記録する</h1>
  </div>
  <div class="top_feature_message">
    <p>夢日記をつけましょう</p>
    <p>だれかに見てもらえれば</p>
    <p>こわい夢だって消化できます</p>
    <p>夢の話を披露して</p>
    <p>友だちを困らせるのは、</p>
    <p>今日でおしまい</p>
  </div>
  <div class="top_feature_icon">
    <i class="bi bi-journal-richtext"></i>
  </div>
  <div class="top_feature_link">
    <a href="howto.php">夢日記のつけ方を見る</a>
  </div>
</div>
</div>

<?php

include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
</body>
</html>