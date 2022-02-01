<?php

$title = 'きょうの夢 - ';
$this_css = 'dreams';
$index = '';
$dreams = 'select';
$howto = '';
$login = '';
include('../app/_parts/_header.php');

?>

  <main>
    <div class="dreams_container">
      <h1>きょうの夢</h1>
      <div class="emotions_area">
        <div class="emotion_dreams">
          <h2 class="emotion">たのしい</h2>
          <ul class="dreams">
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
            <li><a href="">たのしい夢タイトルタイトルタイ</a></li>
          </ul>
        </div>
        <div class="emotion_dreams">
          <h2 class="emotion">しあわせ</h2>
          <ul class="dreams">
            <li><a href="">しあわせな夢タイトルタイトルタ</a></li>
            <li><a href="">しあわせな夢タイトルタイトルタ</a></li>
            <li><a href="">しあわせな夢タイトルタイトルタ</a></li>
          </ul>
        </div>
        <div class="emotion_dreams">
          <h2 class="emotion">しんどい</h2>
          <ul class="dreams">
            <li><a href="">しんどい夢タイトルタイトルタイ</a></li>
            <li><a href="">しんどい夢タイトルタイトルタイ</a></li>
            <li><a href="">しんどい夢タイトルタイトルタイ</a></li>
          </ul>
        </div>
        <div class="emotion_dreams">
          <h2 class="emotion">こわい</h2>
          <ul class="dreams">
            <li><a href="">こわい夢タイトルタイトルタイト</a></li>
            <li><a href="">こわい夢タイトルタイトルタイト</a></li>
            <li><a href="">こわい夢タイトルタイトルタイト</a></li>
          </ul>
        </div>
        <div class="emotion_dreams">
          <h2 class="emotion">忘れたい</h2>
          <ul class="dreams">
            <li><a href="">忘れたい夢タイトルタイトルタイ</a></li>
            <li><a href="">忘れたい夢タイトルタイトルタイ</a></li>
            <li><a href="">忘れたい夢タイトルタイトルタイ</a></li>
          </ul>
        </div>
      </div> <!-- emotions_area -->
    </div> <!-- dreams_container -->
  </main>

  <?php

  include('../app/_parts/_footer.php');