<?php
require_once(__DIR__ . '/../app/config.php');
require(__DIR__ . '/../app/functions.php');

$title = '使い方 - ';
$this_css = 'howto';
include('../app/_parts/_header.php');

?>


<div class="container">
  <div class="container_title">
    <h1>使い方</h1>
  </div>
  <div class="howto">
    <div class="title">
      <i class="bi bi-person"></i>
      <h2>ゲストユーザーの楽しみ方</h2>
    </div>
    <p>会員登録せずゲストユーザーとしてご利用される方は、「きょうの夢」をご覧になれます。
    <br>
    これまで寄贈された夢がランダムに展示されていますので、お楽しみください。
    </p>
    <a href="dreams.php">「きょうの夢」を見る</a>
  </div>
  <div class="howto">
    <div class="title">
      <i class="bi bi-person-check-fill"></i>
      <h2>登録ユーザーの楽しみ方</h2>
    </div>
      <p>会員登録されるとマイページが開設され、夢日記を記録できます。</p>
      <img src="img/my_page.png" alt="夢日記を記録できるマイページの画像">
      <p>記録した夢日記は「むかしの夢を見る」で鑑賞できます。</p>
      <img src="img/past_dream.png" alt="夢日記を閲覧できるマイページの画像">
      <p>もちろん「きょうの夢」をご覧になることもできます。</p>
      <?php if (!isset($_SESSION['name']) && !isset($_SESSION['id'])) :?>
        <a href="pre_join.php">新規登録</a>
      <?php endif; ?>
  </div>


  <div class="howto">
    <div class="title">
      <i class="bi bi-image-fill"></i>
      <h2>夢日記を寄贈する方法</h2>
    </div>
    <p>
    記録した夢日記は、「きょうの夢」に寄贈することで公開できます。
    <br>
    公開したいときは、夢日記を記録するときに「タグをつけて寄贈する」を選択します。
    <br>
    同時に、記録した夢に合うタグも選択してください。
    </p>
    <img src="img/tag.png" alt="夢を寄贈するか選択するラジオボタンの画像">
    <p>寄贈した夢を非公開にしたくなったときも、マイページから変更できます。
    <br>
    また、「きょうの夢」でご自身の夢日記を見つけられたときも非公開に変更できます。
    <br>
    その際、ログインしている必要があるのでご注意ください。
    </p>
    <?php if (!isset($_SESSION['name']) && !isset($_SESSION['id'])) :?>
        <a href="login.php">ログイン</a>
      <?php endif; ?>
    <?php if (isset($_SESSION['name']) && isset($_SESSION['id'])) :?>
        <a href="my_page.php">マイページ</a>
      <?php endif; ?>
  </div>
  <div class="howto">
    <div class="title">
      <i class="bi bi-award"></i>
      <h2>夢日記が公開されているか見分ける方法</h2>
    </div>
    <p>寄贈されて公開済みの夢はアイコンとタグがゴールドです。</p>
    <img src="img/yes_tag.png" alt="公開されてアイコンとタグがゴールドになった夢日記">
    <p>寄贈されていない夢はアイコンがネイビーで、タグはついていません。</p>
    <img src="img/no_tag.png" alt="非公開のためアイコンがネイビーでタグがついていない夢日記">
  </div>
  <div class="howto">
    <div class="title">
    <i class="bi bi-moon-stars"></i>
    <h2>DreaMuseumへようこそ</h2>
    </div>
    <p>夢の博物館を楽しめますように！</p>
    <?php if (!isset($_SESSION['name']) && !isset($_SESSION['id'])) :?>
      <div class="button">
        <a href="pre_join.php">新規登録</a>
        <a href="login.php">ログイン</a>
      </div>
      <?php endif; ?>
    <?php if (isset($_SESSION['name']) && isset($_SESSION['id'])) :?>
        <a href="my_page.php">マイページへ</a>
      <?php endif; ?>
  </div>
</div>


<?php

include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
</body>
</html>