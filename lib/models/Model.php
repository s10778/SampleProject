<?php


/**
 * Modelの既定クラス
 * 
 * @author Yuji Seki
 * @version 1.0.0
 */
class Model
{

    /**
     * PDO
     *
     * @var [type]
     */
    protected PDO $pdo;


    /**
     * テーブル名
     *
     * @var string
     */
    protected string $tableName;


    /**
     * PKのフィールド
     *
     * @var string
     */
    protected string $id;


    /**
     * フィールドの配列
     *
     * @var array
     */
    protected array $fields;

    /**
     * 論理削除フラグ
     * @var boolean
     */
    protected $isSoftDelete;

    /**
     * 論理削除のフィールド名
     *
     * @var string
     */
    protected $softDeleteField;


    /**
     * バリデーション設定
     *
     * @var array
     */
    protected array $validateFields;


    /**
     * バリデーションのエラー
     *
     * @var array
     */
    public $validateErrors;

    /**
     * コンストラクタ
     */
    public function __construct($dsn, $user, $password)
    {

        try {
            $this->pdo = new PDO($dsn, $user, $password);

            //エラー時にはExceptionになるよう設定。
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // $this->pdo = new PDO("mysql:host=sample-db; port=3306; dbname=sample_project; charset=utf8", 'sample_project', 'sample_project');
        } catch (PDOException $e) {
            //暫定エラー対応
            Logger::getInstance()->error('database connect error' . $e->getMessage());
            die('datebase connect error');
        }
    }


    /**
     * クエリの実行
     * パラメータはSQL文内に、:id のようにプレースホルダで記載して、
     * パラメータとして渡す
     *
     * @param string $sql
     * @param array $params 例：array( 'id' => 3 )
     * @return array
     */
    public function query($sql, $params = array())
    {

        $prepare = $this->pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        $preparedParams = $this->changePreparedKeys($params);
        $prepare->execute($preparedParams);

        Logger::getInstance()->debug($sql);
        Logger::getInstance()->debug(print_r($preparedParams, true));

        //データは全件取得
        return $prepare->fetchAll();
    }


    /**
     * 検索処理
     *
     * @param array $params
     * @return array
     */
    public function find($params = array())
    {
        //論理削除のため、フラグを条件に追加。ここで追加するのは適切ではないが、、暫定対応
        if ($this->isSoftDelete) {
            $params[$this->softDeleteField] = 0;
        }

        $sql = 'select ' . implode(',', $this->fields) . ' FROM ' . $this->tableName . ' ';

        if (!empty($params)) {
            $sql .= 'WHERE ';

            //WHERE文の生成
            //TODO LIKEとか他諸々の検索も実施したいが。。
            $wheres = array();
            foreach ($params as $key => $value) {
                $wheres[] =  $key . '=' . ':' . $key;
            }

            //条件はANDで連結
            $sql .= implode(' AND ', $wheres);
        }

        return $this->query($sql, $params);
    }


    /**
     * IDで検索してデータを取得
     *
     * @param [type] $id
     * @return void
     */
    public function findById($id)
    {

        $params = array(
            $this->id => $id
        );

        $result = $this->find($params);

        if (!empty($result[0])) {
            return $result[0];
        } else {
            return null;
        }
    }



    /**
     * データ挿入
     *
     * @param string $table テーブル名
     * @param array $params 挿入パラメータの配列
     * @return void
     */
    public function insert($params)
    {

        $preparedParams = $this->changePreparedKeys($params);

        //SQLの生成
        $sql = 'INSERT INTO ' . $this->tableName . ' (';
        $sql .=  implode(',', array_keys($params));
        $sql .= ')VALUES(';
        $sql .=  implode(',', array_keys($preparedParams));
        $sql .= ')';

        Logger::getInstance()->debug($sql);
        Logger::getInstance()->debug(print_r($preparedParams, true));

        $prepare = $this->pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        return $prepare->execute($preparedParams);
    }


    /**
     * データ更新
     *
     * @param Blog $blog
     * @return void
     */
    public function update($id, $params)
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
     * データ削除
     *
     * @param $id
     * @return void
     */
    public function delete($id)
    {
        $params = array('is_deleted' => '1');
        return $this->update($id, $params);
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function beginTransaction()
    {
    }


    /**
     * Undocumented function
     *
     * @return void
     */
    public function commit()
    {
    }


    /**
     * バリデーションの実行
     * validateFields
     *  arary( 
     *     'フィールド名' => array( 
     *      'required' => array( 'message'=>'入力必須です' )
     *      'maxLength' => array( 'value'=> 100, 'message'=>'最大100文字までで入力してください' )
     *     ) 
     *  )
     *  などの設定から、バリデーションを実行する
     * @return boolean
     */
    public function validate($params)
    {
        $retAll = true;
        foreach( $this->validateFields as $field => $validationSettings ){
            foreach( $validationSettings as $key => $value ){
            
                $rClass = new ReflectionClass('ValidateUtil');
                //第一引数をnullにするとstaticなメソッド呼び出し。フィールド名と、設定の値を引数として渡す
                $checkParams = array();
                $checkParams[] = $params[$field];

                if( !empty( $value['value']) ){
                    $checkParams[] = $value['value'];
                }
                
                $ret = $rClass->getMethod($key)->invokeArgs(null, $checkParams);

                if( $ret === false ){
                    $retAll = false;
                    $this->validateErrors[$field] = $value['message'];
                }
            }
        }

        return $retAll;
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
