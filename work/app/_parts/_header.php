<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= h($title); ?>DreaMuseum</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/<?= h($this_css); ?>.css">
</head>

<body>
  <header>
    <div class="topbar_container_mobile">
      <ul class="topbar_nav_mobile">
        <li class="top"><a href="index.php">DreaMuseum</a></li>
        <?php if (isset($_SESSION['name']) && isset($_SESSION['id'])): ?>
        <li><a href="setting.php"><i class="bi bi-gear"></i></a></li>
        <?php endif; ?>
        <li><i class="bi bi-list" id="open"></i></li>
      </ul>
    </div>
    <div class="overlay">
      <i class="bi bi-x-lg" id="close"></i>
      <nav>
        <ul>
          <li><a href="dreams.php">きょうの夢</a></li>
          <li><a href="my_page.php">マイページ</a></li>
          <li><a href="howto.php">使い方</a></li>
          <li><a href="user_policy.php">利用規約</a></li>
          <li><a href="privacy.php">プライバシーポリシー</a></li>
          <li><a href="contact.php">お問い合わせ</a></li>
        </ul>
      </nav>
    </div>

    <nav class="topbar_container_pc">
      <ul class="topbar_nav">
        <li>
          <a id="<?php if (isset($index) && $index === 'select') { echo h($index); } ?>" href="index.php">
            DreaMuseum
          </a>
        </li>
        <li>
          <a id="<?php if (isset($dreams) && $dreams === 'select') { echo h($dreams); } ?>" href="dreams.php">
            きょうの夢
          </a>
        </li>
        <li>
          <a id="<?php if (isset($my_page) && $my_page === 'select') { echo h($my_page); } ?>" href="my_page.php">
            マイページ
          </a>
        </li>
        <?php if (isset($_SESSION['name']) && isset($_SESSION['id'])): ?>
          <li>
            <a class="setting" id="<?php if (isset($setting) && $setting === 'select') { echo h($setting); } ?>" href="setting.php">
              <i class="bi bi-gear"></i>
            </a>
          </li>
        <?php endif;?>
      </ul>
    </nav>
  </header>
<main>