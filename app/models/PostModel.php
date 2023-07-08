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
    protected string $id = 'id';


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

        $sql = 'SELECT * FROM app_posts ';

        if (!empty($order)) {
            $sql .= $order;
        }

        return $this->query($sql);
    }

    public function lastInsertId(){
        return $this->pdo->lastInsertId();
    }
}
