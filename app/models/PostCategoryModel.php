<?php

require_once(Env::MODEL_PATH . 'AppModel.php');


/**
 * ポストのモデルクラス
 *
 * @author Yuji Seki
 * @version 1.0.0
 */
class PostCategoryModel extends AppModel
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
    protected string $tableName = 'app_categories';


    /**
     * フィールド設定
     * @var array
     */
    protected array $fields = array(
        'category_id',
        'category_name',
        'division',
        'display_order',
        'created_at',
        'updated_at',
    );


    public function findAllCategory($order = '')
    {

        $sql = 'SELECT * FROM app_categories ';

        if (!empty($order)) {
            $sql .= $order;
        }

        return $this->query($sql);
    }
}
