<?php
if (!isset($_SESSION['login_id'])) {
    header("Location: /login");
    exit;
}
?>

<h1>ブログ表示ページ</h1>

<artible>
<?php foreach( $data['blogs'] as $key => $value ):?>


    <div class="title"><h2><?php echo Util::h( $value['title'] );?></h2></div>
    <div class="date">更新日：<?php echo Util::h( $value['updated'] );?></div>
    <div class="date">カテゴリー：<?php echo Util::h( $value['category_name'] );?></div>
    <div class="body">
        <?php echo nl2br( Util::h( $value['body'] ));?>
    </div>

<?php endforeach;?>
</article>
