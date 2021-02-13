<h1>カテゴリー一覧</h1>
<a href="./admin_add_view">新規作成＋</a>
<table>
    <tr>
        <th class="category_name">カテゴリー名</th>
        <th class="updated">更新日時</th>
        <th class="action">操作</th>
    </tr>
    <?php if (!empty($data['categories'])) : ?>
        <?php foreach ($data['categories'] as $category) : ?>
            <tr>
                <td><?php echo Util::h($category['category_name']); ?></td>
                <td><?php echo Util::h($category['updated']); ?></td>
                <td>
                    <a href="/category/admin_edit_view/<?php echo Util::h($category['id']); ?>">編集</a>

                    <form name="delete<?php echo Util::h($category['id']); ?>" method="post" action="/category/admin_delete/<?php echo Util::h($category['id']); ?>">
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                        <a href="#" onclick="javascript:delete<?php echo Util::h($category['id']); ?>.submit()">削除</a>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>