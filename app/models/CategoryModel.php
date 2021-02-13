<?php 


require_once( Env::MODEL_PATH . 'AppModel.php' );

/**
 * カテゴリーのモデルクラス
 * 
 * @author Yuji Seki
 * @version 1.0.0 
 */
class CategoryModel extends AppModel{



    /**
     * 主キーの設定
     */
    protected string $id = 'id';


    /**
     * テーブル名
     *
     * @var string
     */
    protected string $tableName = 'categories';


    /**
     * フィールド設定
     * @var array
     */
    protected array $fields = array(
        'id',
        'category_name',
        'created',
        'updated',
        'is_deleted'
    );


    /**
     * バリデーション設定
     * @var array
     */
    protected array $validateFields = array( 
         'category_name' => array( 
            'required' => array( 
                'message'=>'入力必須です' 
            ),
            'maxLength' => array( 
                'value'=> 100, 
                'message'=>'最大100文字までで入力してください'
            )
        ) 
    );



}