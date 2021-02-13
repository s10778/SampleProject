<?php 

/**
 * アプリケーション全体の基底Model
 * 
 * @author Yuji Seki
 * @version 1.0.0 
 * 
 */
class AppModel extends Model{
    

    /**
     * 論理削除フラグ
     * @var boolean
     */
    protected $isSoftDelete = true;

    /**
     * 論理削除のフィールド名
     *
     * @var string
     */
    protected $softDeleteField = "is_deleted";

    /**
     * コンストラクタ
     */
    public function __construct()
    {

        //dsnの例：mysql:host=localhost; dbname=test_db1; charset=utf8
        $dsn = Env::DB_DATASOURCE . ':host=' . Env::DB_HOST . '; port=' . Env::DB_PORT .'; dbname='. Env::DB_NAME . '; charset=utf8;';
        // $this->pdo = new PDO("mysql:host=sample-db; port=3306; dbname=sample_project; charset=utf8", 'sample_project', 'sample_project');
        $user = Env::DB_USERNAME;
        $password = Env::DB_PASSWORD;

        parent::__construct($dsn, $user, $password);
    }


    

    
    


}