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
					<li><a href="/post/create" class="btn4">新規投稿</a></li>
				</ul>
			</div>
			<?php echo $contents; ?>
		</main>
		<footer class="footer">共通フッター部分</footer>
	</div>
</body>

</html>
