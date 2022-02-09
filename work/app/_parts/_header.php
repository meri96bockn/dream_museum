<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= h($title); ?>DreamMuseum</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/<?= h($this_css); ?>.css">
</head>

<body>
  <header>
    <div class="topbar_container_mobile">
      <ul class="topbar_nav_mobile">
        <li>
          <a href="index.php">DreamMuseum</a>
        </li>
        <li>
          <i class="bi bi-list" id="open"></i>
        </li>
      </ul>
    </div>
    <div class="overlay">
      <i class="bi bi-x-lg" id="close"></i>
      <nav>
        <ul>
          <li><a href="dreams.php">きょうの夢</a></li>
          <li><a href="howto.php">使い方</a></li>
          <li><a href="my_page.php">マイページ</a></li>
        </ul>
        <ul>
          <li><a href="user_policy.php">利用規約</a></li>
          <li><a href="privacy.php">プライバシーポリシー</a></li>
          <li><a href="contact.php">お問い合わせ</a></li>
        </ul>
      </nav>
    </div>

    <nav class="topbar_container_pc">
      <ul class="topbar_nav">
        <li>
          <a id="<?= h($index); ?>" href="index.php">DreamMuseum</a>
        </li>
        <li>
          <a id="<?= h($dreams); ?>" href="dreams.php">きょうの夢</a>
        </li>
        <li>
          <a id="<?= h($howto); ?>" href="howto.php">使い方</a>
        </li>
        <li>
          <a id="<?= h($my_page); ?>" href="my_page.php">マイページ</a>
        </li>
      </ul>
    </nav>
  </header>
