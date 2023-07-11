<?php

/**
 * CSVインポート用クラス
 *
 * @version 1.0.0
 */
class CsvImport {

    public static function import()
    {
        $csvMembers = [];  // 空の配列として初期化

        if(isset($_FILES)) {
            $csv_file = $_FILES['member_id']['tmp_name']; // アップロードされたCSVファイルの一時的な保存場所

            // CSVファイルを読み込む
            if (($handle = fopen($csv_file, "r")) !== false) {
                while (($data = fgetcsv($handle)) !== false) {
                    // CSVの各行から値を取得して配列に追加
                    $csvMembers[] = $data;
                    // ログを出力して確認する
                    // Logger::getInstance()->debug('文字列の確認');
                    // Logger::getInstance()->debug(print_r($data,true));
                }
                fclose($handle); // ファイルを閉じる
            }
        }
        return $csvMembers;
    }

}
