<?php
session_start();
require_once(__DIR__ . '/../app/config.php');
require('../app/functions.php');

var_dump($_SESSION['id']);
var_dump($_SESSION['name']);

if (!isset($_SESSION['name']) && !isset($_SESSION['id'])) {
  header('Location: login.php');
  exit;
} else {
  $name = $_SESSION['name'];
  $id = $_SESSION['id'];
}


if (isset($_GET['action']) && $_GET['action'] === 'rewrite' && isset($_SESSION['form'])) {
  $form = $_SESSION['form'];
} else {
  $form = [
    'id' => '',
    'name' => '',
    'title' => '',
    'content' => '',
    'tag' => '',
    'emotion' => '',
  ];
}
$error = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST' ) {
  $form['id'] = $id;
  $form['name'] = $name;


  $form['title'] = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
  if($form['title'] === '') {
    $error['title'] = 'blank';
  } elseif (mb_strlen($form['title']) > 20) {
    $error['title'] = 'excess';
  }

  $form['content'] = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
  if($form['content'] === '') {
    $error['content'] = 'blank';
  }

  $form['tag'] = filter_input(INPUT_POST, 'tag', FILTER_SANITIZE_STRING);
  $form['emotion'] = filter_input(INPUT_POST, 'emotion', FILTER_SANITIZE_STRING);

  if (empty($error)) {
    $_SESSION['form'] = $form;
    header('location: check_dream.php');
    exit();
  }
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
      <form action="" method="post">
        <div class="form_item">
          <label for="dream_title">タイトル</label>
          <input type="text" name="title" id="dreamtitle" placeholder="20字以内で入力してください" value="<?= h($form['title']); ?>">
        </div>
        <div class="error">
          <?php if (isset($error['title']) && $error['title'] === 'blank'):?>
          <p>* タイトルを入力してください</p>
          <?php endif; ?>
        </div>
        <div class="error">
        <?php if (isset($error['title']) && $error['title'] === 'excess'):?>
          <p>* 20文字以内で入力してください</p>
          <?php endif; ?>
        </div>
      
        <div class="form_item">
          <label for="content">夢の内容</label>
          <textarea name="content" id="content" rows="15"><?= h($form['content']); ?></textarea>
        </div>
        <div class="error">
        <?php if (isset($error['content']) && $error['content'] === 'blank'):?>
          <p>* 夢の内容を入力してください</p>
          <?php endif; ?>
        </div>
      
        <div class="form_item radio">
          <div class="radio_label">
            <label>「きょうの夢」に寄贈しますか？</label>
          </div>
          <div class="radio_items">
            <div class="radio_item">
              <input type="radio" name="tag" id="no_tag" value="no_tag" checked="checked"
              <?php if ($form['tag'] !== 'no_tag'): ?>
              checked=""
              <?php endif; ?>>
              <label for="no_tag" class="radio_title">寄贈しない</label>
            </div>
            <div class="radio_item">
              <input type="radio" name="tag" id="yes_tag" value="yes_tag" 
              <?php if ($form['tag'] === 'yes_tag'): ?>
              checked="checked"
              <?php endif; ?>>
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
                <input type="radio" name="emotion" id="emotion_1" value="たのしい"
                <?php if ($form['emotion'] === 'たのしい'): ?>
                checked="checked"
                <?php endif; ?>>
                <label for="emotion_1" class="radio_title">たのしい</label>
              </div>
              <div class="radio_item">
                <input type="radio" name="emotion" id="emotion_2" value="しあわせ"
                <?php if ($form['emotion'] === 'しあわせ'): ?>
                checked="checked"
                <?php endif; ?>>
                <label for="emotion_2" class="radio_title">しあわせ</label>
              </div>
              <div class="radio_item">
                <input type="radio" name="emotion" id="emotion_3" value="しんどい"
                <?php if ($form['emotion'] === 'しんどい'): ?>
                checked="checked"
                <?php endif; ?>>
                <label for="emotion_3" class="radio_title">しんどい</label>
              </div>
              <div class="radio_item">
                <input type="radio" name="emotion" id="emotion_4" value="こわい"
                <?php if ($form['emotion'] === 'こわい'): ?>
                checked="checked"
                <?php endif; ?>>
                <label for="emotion_4" class="radio_title">こわい</label>
              </div>
              <div class="radio_item">
                <input type="radio" name="emotion" id="emotion_5" value="忘れたい"
                <?php if ($form['emotion'] === '忘れたい'): ?>
                checked="checked"
                <?php endif; ?>>
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
</div>


<?php

include('../app/_parts/_footer.php');

?>
<script src="js/main.js"></script>
<script src="js/my_page.js"></script>
</body>
</html>