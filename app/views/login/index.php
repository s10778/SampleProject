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
	<h2>ログイン画面</h2>

	<form method="POST" action="/login/authenticate">

    <label for=login_id>ログインID</label>
    <input type="text" name="login_id" id="login_id" maxlength="100" required value=""/>

    <label for=password>パスワード</label>
    <input type="password" name="password" id="password" maxlength="100" required value=""/>

	<input type="submit" value="ログイン"/>
	</form>
</body>

</html>
