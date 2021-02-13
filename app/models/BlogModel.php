<?php

require_once(Env::MODEL_PATH . 'AppModel.php');


/**
 * ブログのモデルクラス
 * 
 * @author Yuji Seki
 * @version 1.0.0 
 */
class BlogModel extends AppModel
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
    protected string $tableName = 'blogs';


    /**
     * フィールド設定
     * @var array
     */
    protected array $fields = array(
        'id',
        'category_id',
        'title',
        'body',
        'created',
        'updated',
        'is_deleted'
    );

    /**
     * バリデーション設定
     * @var array
     */
    protected array $validateFields = array(
        'title' => array(
            'required' => array(
                'message' => '入力必須です'
            ),
            'maxLength' => array(
                'value' => 200,
                'message' => '最大200文字までで入力してください'
            ),
        )
    );

    /**
     * 全てのブログ記事を取得。カテゴリーもJOINして取得する。
     *
     * @param string $order OrderByの文字列
     * @return void
     */
    public function findAllBlog($order = '')
    {

        $sql = 'select blogs.id,blogs.title,blogs.body,blogs.updated,categories.category_name FROM blogs ';
        $sql .= ' LEFT OUTER JOIN categories ';
        $sql .= ' ON blogs.category_id = categories.id ';
        $sql .= ' WHERE blogs.is_deleted=0 ';

        if (!empty($order)) {
            $sql .= $order;
        }

        return $this->query($sql);
    }
}
