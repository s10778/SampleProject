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
  <h2>新規投稿</h2>

  <form method="POST" action="/post/create">

    <div>
      <label for="sent_date">送信日時</label>
      <input type="datetime-local" name="sent_date" id="sent_date" required value=""/>
    </div>

    <div>
      <label for="title">タイトル</label>
      <input type="text" name="title" id="title" placeholder="最大文字数100文字" required value=""/>
    </div>

    <div>
      <label for="body">本文</label>
      <textarea id="body" name="body"></textarea>
    </div>

    <div>
      <label>送信先</label>
      <input type="radio" name="sender" value="CSV" onchange="toggleInput()"/>CSV (A列:職員番号)<br/>
      <input type="radio" name="sender" value="会員個別" onchange="toggleInput()"/>会員個別<br/>

      <div id="sender1Input" style="display: none;">
        <label>CSV</label>
        <input type="file" id="sender1" name="member_id">
      </div>

      <div id="sender2Input" style="display: none;">
        <label>職員/会員番号</label>
        <input type="text" id="sender3" placeholder="半角数字">
        <input type="text" id="sender3" placeholder="半角数字">
        <input type="text" id="sender3" placeholder="半角数字">
        <input type="text" id="sender3" placeholder="半角数字">
        <input type="text" id="sender3" placeholder="半角数字"><br/>
        <input type="text" id="sender3" placeholder="半角数字">
        <input type="text" id="sender3" placeholder="半角数字">
        <input type="text" id="sender3" placeholder="半角数字">
        <input type="text" id="sender3" placeholder="半角数字">
        <input type="text" id="sender3" placeholder="半角数字"><br/>
      </div>
    </div>

    <script>
      function toggleInput() {
        var selectedField = document.querySelector('input[name="sender"]:checked').value;
        var sender1Input = document.getElementById('sender1Input');
        var sender2Input = document.getElementById('sender2Input');

        // すべての入力フィールドを非表示にする
        sender1Input.style.display = "none";
        sender2Input.style.display = "none";

        // 選択された入力フィールドを表示する
        if (selectedField === "CSV") {
          sender1Input.style.display = "block";
        } else if (selectedField === "会員個別") {
          sender2Input.style.display = "block";
        }
      }
    </script>

    <input type="submit" value="送信"/>
  </form>
</body>

</html>
