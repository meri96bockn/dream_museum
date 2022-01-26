<?php

$title = 'マイページ';
$this_css = 'form';
include('../app/_parts/_header.php');

?>

  <div class="forms">
    <div class="form_title">
      <h1>夢日記</h1>
    </div>
    <div class="form">
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

        <div class="form_item">
          <div class="radio_label">
            <label>「きょうの夢」に寄贈しますか？</label>
          </div>
          <div class="radio_items">
            <div class="radio_item">
              <input type="radio" name="tag" id="no_tag" value="no_tag">
              <label for="no_tag" class="radio_title">寄贈しない</label>
            </div>
            <div class="radio_item">
              <input type="radio" name="tag" id="yes_tag" value="yes_tag">
              <label for="yes_tag" class="radio_title">タグをつけて寄贈する</label>
            </div>
          </div>
        </div>

        <div class="form_item">
          <div class="radio_label">
            <label>合うタグを選んでください。<br>どんな夢ですか？</label>
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

        <button>記録</button>
      </form>
    </div>
  </div>

  <?php

  include('../app/_parts/_footer.php');