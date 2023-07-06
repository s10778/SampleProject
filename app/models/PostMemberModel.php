<?php

require_once(Env::MODEL_PATH . 'AppModel.php');


/**
 * ポストのモデルクラス
 *
 * @author Yuji Seki
 * @version 1.0.0
 */
class PostMemberModel extends AppModel
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
    protected string $tableName = 'app_posts_members';


    /**
     * フィールド設定
     * @var array
     */
    protected array $fields = array(
        'post_member_id',
        'post_id',
        'member_id',
        'created_at',
        'updated_at',
    );

    public function getPost()
    {

        $sql = 'select app_posts.post_id FROM app_posts ORDER BY app_posts.post_id DESC';

        return $this->getPostId($sql);
    }

    /**
     * データ挿入
     *
     * @param string $table テーブル名
     * @param array $params 挿入パラメータの配列
     * @return void
     */
    public function insertMember($members, $lastPostId)
    {
        // SQLの生成
        $sql = 'INSERT INTO ' . $this->tableName . ' (post_id, member_id) VALUES (:post_id, :member_id)';

        $prepare = $this->pdo->prepare($sql);

        foreach ($members as $member) {
            $prepare->bindValue(':post_id', $lastPostId, PDO::PARAM_INT);
            $prepare->bindValue(':member_id', $member, PDO::PARAM_INT);

            // 実行などの追加処理...
            $prepare->execute();
        }
    }
}
