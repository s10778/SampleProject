<?php
if (!isset($_SESSION['login_id'])) {
    header("Location: /login");
    exit;
}
?>



<!DOCTYPE HTML>
<html lang="ja">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
	<meta name="format-detection" content="telephone=no">
	<title><?php echo $title ?></title>
	<meta name="Description" content="<?php echo $description; ?>" />
	<meta name="Keywords" content="<?php echo $keyword; ?>" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<link rel="stylesheet" type="text/css" href="/assets/css/style.css" media="screen" />
	<style>
      /* The Modal (background) */
      .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0, 0, 0); /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
      }

      /* Modal Content */
      .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
      }

      /* The Close Button */
      .close {
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
      }

      .close:hover,
      .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
      }
    </style>
</head>

<body>
	<div id="wrap">
		<header class="header">
			<div>サンプルプロジェクト</div>
		</header>
		<main id="main" class="main">
			<div class="menu">
				<ul>
					<li><a href="/" class="btn2">Home</a></li>
					<li><a href="/category/admin_index" class="btn4">カテゴリー管理</a></li>
					<li><a href="/blog/admin_index" class="btn4">ブログ管理</a></li>
					<li><a href="/post/create" class="btn4">お知らせ新規投稿</a></li>
					<li><a href="/post/index" class="btn4">お知らせ一覧</a></li>
				</ul>
			</div>
			<?php echo $contents; ?>
		</main>
		<footer class="footer">共通フッター部分</footer>
	</div>
</body>

</html>
