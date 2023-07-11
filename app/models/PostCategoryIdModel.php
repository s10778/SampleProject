<?php

require_once(Env::MODEL_PATH . 'AppModel.php');


/**
 * ポストのモデルクラス
 *
 * @author Yuji Seki
 * @version 1.0.0
 */
class PostCategoryIdModel extends AppModel
{



    /**
     * 主キーの設定
     * @var string
     */
    protected string $id = 'id';


    /**
     * テーブル名の設定
     *
     * @var string
     */
    protected string $tableName = 'app_posts_categories';


    /**
     * フィールド設定
     * @var array
     */
    protected array $fields = array(
        'post_category_id',
        'post_id',
        'category_id',
        'created_at',
        'updated_at',
    );


    public function insertCategory($categories, $lastPostId)
    {
        // SQLの生成
        $sql = 'INSERT INTO ' . $this->tableName . ' (post_id, category_id) VALUES ';

        $insertValues = [];
        $bindingParams = [];
        foreach ($categories as $key => $category) {
            $insertValues[] = "(:postId{$key}, :categoryId{$key})";
            $bindingParams[":postId{$key}"] = $lastPostId;
            $bindingParams[":categoryId{$key}"] = $category;
        }
        $sql .= implode(', ', $insertValues);

        Logger::getInstance()->debug($sql);

        $prepare = $this->pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        foreach ($bindingParams as $key => $value) {
            $prepare->bindValue($key, $value);
        }

        // return文を追加する必要があります
        return $prepare->execute();
    }

    public function findAllCategoryId($order = '')
    {

        $sql = 'SELECT * FROM app_posts_categories ';

        if (!empty($order)) {
            $sql .= $order;
        }

        return $this->query($sql);
    }

    public function deleteCategory($postId)
    {
        $sql = 'DELETE FROM ' . $this->tableName . ' WHERE post_id = :postId';

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);

        $stmt->execute();
    }

}
