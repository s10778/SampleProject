<?php

/**
 * Utility クラス
 * 
 * 
 * @author Yuji Seki
 * @version 1.0.0
 */
class Util
{


    /**
     * HTMLエンティティの変換
     * セキュリティ対策（クロスサイトスクリプティング）のため、入力文言の表示時には本メソッドを通すこと。
     *
     * @param string|array $value 変換を実施する文字列
     * @return string|array 変換後の文字列
     */
    public static function h($value)
    {
        if (!empty($value)) {
            //配列の場合は、その子要素まで実施
            if (is_array($value)) {
                $ret = array();
                foreach ($value as $key => $val) {
                    $ret[$key] = Util::h($val);
                }
                return $ret;
            } else {
                return htmlspecialchars($value, ENT_QUOTES);
            }
        } else {
            return "";
        }
    }

    /**
     * NULLバイト除去　
     * NULLバイト攻撃対策
     * @param array $arr 除去する文字列の配列
     * @return array 除去後の配列
     */
    public static function sanitize($arr)
    {
        if (is_array($arr)) {
            return array_map( array( 'Util' ,'sanitize') , $arr);
        }
        return str_replace("\0", "", $arr);
    }


    /**
     * 32バイトのCSRFトークンを作成
     *
     * @return string トークン文字列
     */
    public static function generateCsrfToken()
    {
        $TOKEN_LENGTH = 16; //16*2=32バイト
        $bytes = openssl_random_pseudo_bytes($TOKEN_LENGTH);

        return bin2hex($bytes);
    }
}
