<?php

require_once(Env::MODEL_PATH . 'AppModel.php');


/**
 * ポストのモデルクラス
 *
 * @author Yuji Seki
 * @version 1.0.0
 */
class PostModel extends AppModel
{



    /**
     * 主キーの設定
     * @var string
     */
    protected string $id = 'post_id';


    /**
     * テーブル名の設定
     *
     * @var string
     */
    protected string $tableName = 'app_posts';


    /**
     * フィールド設定
     * @var array
     */
    protected array $fields = array(
        'post_id',
        'sent_date',
        'title',
        'body',
        'sender',
        'attachment',
        'created_at',
        'updated_at',
    );

    public function findAllPost($order = '')
    {

        $sql = 'SELECT app_posts.post_id, app_posts.sent_date, app_posts.title, app_posts.body, app_posts.sender, app_posts.attachment, app_posts.created_at, app_posts.updated_at, app_categories.category_name
                FROM app_posts
                INNER JOIN app_posts_categories ON app_posts.post_id = app_posts_categories.post_id
                INNER JOIN app_categories ON app_posts_categories.category_id = app_categories.category_id
                ORDER BY app_posts.updated_at DESC;
                ';


        if (!empty($order)) {
            $sql .= $order;
        }

        return $this->query($sql);
    }

    public function lastInsertId(){
        return $this->pdo->lastInsertId();
    }

    public function findEditPost($id)
    {
        {

            $sql = 'SELECT * FROM app_posts WHERE post_id =' . $id;

            return $this->query($sql);
        }
    }

    public function updatePost($id ,$params)
    {
        $preparedParams = $this->changePreparedKeys($params);

        //SQLの生成
        $sql = 'UPDATE ' . $this->tableName . ' SET ';

        $updates = array();
        foreach ($params as $key => $value) {
            $updates[] = $key . '=' . ':' . $key;
        }
        $sql .= implode(',', $updates);

        $sql .= ' WHERE ' . $this->id . '=' . ':' . $this->id;

        $preparedParams[':' . $this->id] = $id;


        Logger::getInstance()->debug($sql);
        Logger::getInstance()->debug(print_r($preparedParams, true));

        $prepare = $this->pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        return $prepare->execute($preparedParams);
    }


    /**
     * :を付けたキーに変換する
     *
     * @param [type] $params
     * @return array
     */
    private function changePreparedKeys($params)
    {
        $result = array();

        foreach ($params as $key => $value) {
            $keysPrepared[':' . $key] = $value;
        }

        return $keysPrepared;
    }
}
