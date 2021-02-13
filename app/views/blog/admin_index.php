<h1>ブログ一覧</h1>
<a href="./admin_add_view">新規作成＋</a>
<table>
    <tr>
        <th class="title">タイトル</th>
        <th class="category">カテゴリー</th>
        <th class="body">本文</th>
        <th class="updated">更新日時</th>
        <th class="action">操作</th>
    </tr>
    <?php if (!empty($data['blogs'])) : ?>
        <?php foreach ($data['blogs'] as $blog) : ?>
            <tr>
                <td><?php echo Util::h($blog['title']); ?></td>
                <td><?php echo Util::h($blog['category_name']); ?></td>
                <td><?php echo mb_substr( Util::h($blog['body']) , 0, 50) . '...'; ?></td>
                <td><?php echo Util::h($blog['updated']); ?></td>
                <td>
                    <a href="/blog/admin_edit_view/<?php echo Util::h($blog['id']); ?>">編集</a>

                    <form name="delete<?php echo Util::h($blog['id']); ?>" method="post" action="/blog/admin_delete/<?php echo Util::h($blog['id']); ?>">
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>" />
                        <a href="#" onclick="javascript:delete<?php echo Util::h($blog['id']); ?>.submit()">削除</a>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>