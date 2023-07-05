<?php
if (!isset($_SESSION['login_id'])) {
    header("Location: /login");
    exit;
}
?>

<h1>カテゴリー編集</h1>

<?php if(!empty($data['category_edit_error']) ):?>
    <pre><?php echo $data['category_edit_error'];?></pre>
<?php endif;?>
<form method="POST" action="/category/admin_edit/<?php echo $data['category']['id'];?>">

    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />

    <label>
        カテゴリー名
        <input type="text" name="category_name" maxlength="100" required value="<?php echo $data['category']['category_name'];?>" />
        <?php if( !empty( $validateErrors['category_name'] ) ):?>
            <p class="error"><?php echo $validateErrors['category_name'];?></p>
        <?php endif;?>
    </label>


    <p>
        <input type="submit"></input>
    </p>


</form>
