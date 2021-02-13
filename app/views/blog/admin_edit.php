<h1>ブログ編集</h1>

<?php if(!empty($data['blog_edit_error']) ):?>
    <pre><?php echo $data['blog_edit_error'];?></pre>
<?php endif;?>
<form method="POST" action="/blog/admin_edit/<?php echo $data['blog']['id'];?>">

    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />

    <p>
        <label>
            タイトル
            <input type="text" name="title" maxlength="200" required value="<?php echo $data['blog']['title'];?>"/>
            <?php if( !empty( $validateErrors['title'] ) ):?>
                <p class="error"><?php echo $validateErrors['title'];?></p>
            <?php endif;?>
        </label>
    </p>
    <p>
        <label>
            カテゴリー
            <select name="category_id">
                <?php foreach( $data['categories'] as $key => $category ):?>
                    <option value="<?php echo Util::h( $category['id']);?>" <?php if($category['id']===$data['blog']['category_id']):?><?php endif;?>><?php echo Util::h( $category['category_name']);?></option>
                <?php endforeach;?>
            </select>
        </label>
    </p>
    <p>
        <label>
            本文
            <textarea name="body" rows="5" cols="15"><?php echo $data['blog']['body'];?></textarea>
        </label>
    </p>


    <p>
        <input type="submit"></input>
    </p>


</form>