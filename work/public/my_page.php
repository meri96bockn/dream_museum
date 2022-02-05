<?php
session_start();
require_once(__DIR__ . '/../app/config.php');
require('../app/functions.php');

if (!(isset($_SESSION))) {
  header('Location: login.php');
  exit;
} else {
  $name = $_SESSION['name'];
}

$title = 'マイページ - ';
$this_css = 'tab';
include('../app/_parts/_header.php');

?>

<!-- マイページ -->
<div class="container">
  <div class="container_title">
    <h1>マイページ</h1>
  </div>

  <!-- タブメニュー夢日記 -->
  <div class="tab">
    <ul class="tab_title_mobile">
      <li>
        <a href="#" class="tab1 active" data-id="diary">
          <i class="bi bi-journal-richtext"></i>
        </a>
      </li>
      <li>
        <a href="#" class="tab2" data-id="dreams">
          <i class="bi bi-image-fill"></i>
        </a>
      </li>
    </ul>

    <ul class="tab_title_pc">
      <li><a href="#" class="tab1 active" data-id="diary">夢日記をつける</a></li>
      <li><a href="#" class="tab2" data-id="dreams">むかしの夢を見る</a></li>
    </ul>

    <div class="content1 form active" id="diary">
      <form action="process.php" method="post">
        <div class="form_item">
          <label for="date">夢を見た日</label>
          <input type="date" id="date" value="2022-01-18" >
        </div>
      
        <div class="form_item">
          <label for="dream_title">タイトル</label>
          <input type="text" id="dreamtitle" maxlength="255" placeholder="15字以内">
        </div>
      
        <div class="form_item">
          <label for="message">夢の内容</label>
          <textarea name="message" id="message" rows="10"></textarea>
        </div>
      
        <div class="form_item radio">
          <div class="radio_label">
            <label>「きょうの夢」に寄贈しますか？</label>
          </div>
          <div class="radio_items">
            <div class="radio_item">
              <input type="radio" name="tag" id="no_tag" value="no_tag" checked="checked">
              <label for="no_tag" class="radio_title">寄贈しない</label>
            </div>
            <div class="radio_item">
              <input type="radio" name="tag" id="yes_tag" value="yes_tag">
              <label for="yes_tag" class="radio_title">タグをつけて寄贈する</label>
            </div>
          </div>
        </div>
      
        <div class="form_item radio" id="tags">
          <div class="radio_label">
            <label>どんな夢ですか？<br>合うタグを1つ選んでください。</label>
          </div>
          <div class="radio_items">
              <div class="radio_item">
                <input type="radio" name="emotion" id="emotion_1" value="fun">
                <label for="emotion_1" class="radio_title">たのしい</label>
              </div>
              <div class="radio_item">
                <input type="radio" name="emotion" id="emotion_2" value="happy">
                <label for="emotion_2" class="radio_title">しあわせ</label>
              </div>
              <div class="radio_item">
                <input type="radio" name="emotion" id="emotion_3" value="hard">
                <label for="emotion_3" class="radio_title">しんどい</label>
              </div>
              <div class="radio_item">
                <input type="radio" name="emotion" id="emotion_4" value="scary">
                <label for="emotion_4" class="radio_title">こわい</label>
              </div>
              <div class="radio_item">
                <input type="radio" name="emotion" id="emotion_5" value="forget">
                <label for="emotion_5" class="radio_title">忘れたい</label>
              </div>
          </div>
        </div>
      
        <button>内容を確認する</button>
      </form>
    </div>

    <!-- タブメニューむかしの夢 -->
    <div class="content2" id="dreams">
      <div class="dreams_container">
        <h2 class="dreams_title">むかしの夢</h2>
        <div class="dreams">
          <ul class="dream_items">
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
          </ul>
        </div>  <!-- dreams -->
        <i class="bi bi-hand-index scroll"></i> 
      </div>  <!-- dreams_container -->
    </div> <!-- content2 -->
</div>


<?php

include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
<script src="js/my_page.js"></script>
</body>
</html>