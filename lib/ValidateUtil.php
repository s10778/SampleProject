<?


/**
 * バリデーション用　Utility クラス
 * 
 * 
 * @author Yuji Seki
 * @version 1.0.0
 */
class ValidateUtil
{


    /**
     * 値の存在チェック
     *
     * @param string $param
     * @return boolean
     */
    public static function required( $param ){
        Logger::getInstance()->debug('required:'. $param );
        
        return !empty( $param );
    }

    /**
     * 最大長さチェック
     *
     * @param string $param
     * @return boolean
     */
    public static function maxLength( $param , $maxLength ){
        Logger::getInstance()->debug('maxLength:'. $param . ' length:' . $maxLength );

        return mb_strlen($param)<$maxLength;
    }
    
    /**
     * 最小長さチェック
     *
     * @param string $param
     * @return boolean
     */
    public static function minLength( $param , $minLength ){
        Logger::getInstance()->debug('minLength:'. $param  . ' length:' . $minLength);

        return mb_strlen($param)>$minLength;
    }

}