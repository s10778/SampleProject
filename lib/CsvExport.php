<?php

/**
 * CSVエクスポート用クラス
 *
 * @version 1.0.0
 */
class CsvExport {

    public static function export($outputData, $filename)
    {
        // HTTPヘッダを設定します。
        header('Content-Type: application/csv');
        header("Content-Disposition: attachment; filename=$filename");

        // 出力バッファリングを開始します。
        ob_start();

        // ファイルポインタを開きます（メモリ内の出力ストリームに対して）。
        $file = fopen('php://output', 'w');

        // データをCSVフォーマットに変換して書き込みます。
        fputcsv($file, $outputData);

        // ファイルを閉じます。
        fclose($file);

        // 出力バッファをフラッシュしてデータをブラウザに送信します。
        ob_end_flush();
    }

}
