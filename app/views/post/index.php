<?php
if (!isset($_SESSION['login_id'])) {
    header("Location: /login");
    exit;
}
?>

<h1>お知らせ一覧</h1>
<?php if (!empty($data['posts'])) : ?>
    <?php foreach ($data['posts'] as $key => $posts) : ?>
        <div style="width: 288px; height: auto; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,.2); margin-bottom: 20px;">

            <div><?php echo Util::h($posts['updated_at']); ?></div>
            <?php foreach ($posts['category_name'] as $name) : ?>
                <div><?php echo Util::h($name); ?></div>
            <?php endforeach; ?>
            <div>
                <button id="myBtn_<?php echo $key ?>">確認</button>
                <div id="myModal_<?php echo $key ?>" class="modal">
                    <!-- Modal content -->
                    <div class="modal-content">
                        <span class="close close_<?php echo $key ?>">&times;</span>
                        <h3>お知らせ詳細</h3>
                        <p>タイトル</p>
                        <div><?php echo Util::h($posts['title']); ?></div>
                        <p>送信日時</p>
                        <div><?php echo Util::h($posts['sent_date']); ?></div>
                        <p>送信先</p>
                        <div><?php echo Util::h($posts['sender']); ?></div>
                        <p>カテゴリ</p>
                        <?php foreach ($posts['category_name'] as $name) : ?>
                            <div><?php echo Util::h($name); ?></div>
                        <?php endforeach; ?>
                        <label>送信対象</label>
                        <a href="indexDetail/<?php echo $posts['post_id'] ?>">
                            <button>ダウンロード</button>
                        </a>
                        <p></p>
                        <div><?php echo Util::h($posts['title']); ?></div>
                    </div>
                </div>


                <script>
                    // Get the modal
                    var modal = document.getElementById("myModal_<?php echo $key ?>");

                    // Get the button that opens the modal
                    var btn = document.getElementById("myBtn_<?php echo $key ?>");

                    // Get the <span> element that closes the modal
                    var span = document.getElementsByClassName("close_<?php echo $key ?>")[0];

                    // When the user clicks the button, open the modal
                    btn.onclick = function () {
                        modal.style.display = "block";
                    };

                    // When the user clicks on <span> (x), close the modal
                    span.onclick = function () {
                        modal.style.display = "none";
                    };

                    // When the user clicks anywhere outside of the modal, close it
                    window.onclick = function (event) {
                        if (event.target == modal) {
                        modal.style.display = "none";
                        }
                    };
                </script>
            </div>
            <div>
                <a href="/post/edit/<?php echo Util::h($posts['post_id']); ?>">
                    <button type="button">編集</button>
                </a>
            </div>
            <div>
                <form name="delete<?php echo Util::h($posts['post_id']); ?>" method="post" action="/post/delete/<?php echo Util::h($posts['post_id']); ?>">
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                    <a href="#" onclick="javascript:delete<?php echo Util::h($posts['post_id']); ?>.submit()">
                    <button type="button" >削除</button>
                    </a>
                </form>
            </div>
            <div><?php echo Util::h($posts['title']); ?></div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
