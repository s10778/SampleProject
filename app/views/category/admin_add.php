<?php
if (!isset($_SESSION['login_id'])) {
    header("Location: /login");
    exit;
}
?>

<h1>カテゴリー追加</h1>

<?php if(!empty($data['category_add_error']) ):?>
    <pre><?php echo $data['category_add_error'];?></pre>
<?php endif;?>
<form method="POST" action="./admin_add">

    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />

    <label>
        カテゴリー名
        <input type="text" name="category_name" maxlength="100" required/>
        <?php if( !empty( $validateErrors['category_name'] ) ):?>
            <p class="error"><?php echo $validateErrors['category_name'];?></p>
        <?php endif;?>
    </label>


    <p>
        <input type="submit"></input>
    </p>


</form>
