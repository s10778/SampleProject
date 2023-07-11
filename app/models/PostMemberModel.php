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
        $sql = 'INSERT INTO ' . $this->tableName . ' (post_id, member_id) VALUES ';

        $insertValues = [];
        $bindingParams = [];
        foreach ($members as $key => $member) {
            $insertValues[] = "(:postId, :memberId{$key})";
            $bindingParams[":memberId{$key}"] = $member;
        }
        $sql .= implode(', ', $insertValues);

        Logger::getInstance()->debug($sql);

        $prepare = $this->pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

        // Bind the lastPostId to the parameter.
        $bindingParams[":postId"] = $lastPostId;
        foreach ($bindingParams as $key => $value) {
            $prepare->bindValue($key, $value);
        }

        // 実行などの追加処理...

        // return文を追加する必要があります
        return $prepare->execute();
    }

    public function exportMember($id)
    {
        $sql = 'select app_posts_members.member_id FROM app_posts_members WHERE post_id = ' . $id;
        return $this->query($sql);
    }

    public function deleteMember($postId)
    {
        $sql = 'DELETE FROM ' . $this->tableName . ' WHERE post_id = :postId';

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':postId', $postId, PDO::PARAM_INT);

        $stmt->execute();
    }

}
