<?php
require_once __DIR__ . '/Helper.php';

class BookMarkManager
{
    private $Helper;

    public function __construct()
    {
        $this->Helper = new Helper();
    }

    /**
     * ブックマークデータをJSONファイルから読み込む
     * 
     * @return array ブックマークの配列。ファイルが存在しない場合は空配列
     */
    public function load_bookmarkLists(): array
    {
        // ファイルが存在しない場合（初回起動時）は空配列を返す
        // これにより、ファイルがない状態でもエラーにならず、新規にタスクを追加できる
        if (!file_exists(Helper::BOOKMARKS_JSON_FILE))
        {
            return [];
        }
        // file_get_contents() でファイル全体を文字列として読み込み
        $json = file_get_contents(Helper::BOOKMARKS_JSON_FILE);
        // json_decode() の第2引数 true で、オブジェクトではなく連想配列として取得
        $data = json_decode($json, true);
        // json_decode() が失敗した場合は null を返すため、配列であることを確認
        // is_array() で検証し、配列でない場合は空配列を返して安全性を確保
        return is_array($data) ? $data : [];
    }
    /**
     * タスクデータをJSONファイルに保存
     * 
     * @param array $bookMarks 保存するタスクの配列
     * @return void
     */
    public function save_bookMarks(array $enteredBookMarkData): array
    {
        echo 'ここまで６';
        // var_dump($enteredBookMarkData);
        $json = json_encode(array_values($enteredBookMarkData), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        // var_dump($bookMarks);
        $tmp = Helper::BOOKMARKS_JSON_FILE . '.tmp';
        $fp = fopen($tmp, 'wb');

        if ($fp === false)
        {
            throw new RuntimeException('Cannot write temp file');
        }

        fwrite($fp, $json);

        fclose($fp);

        rename($tmp, Helper::BOOKMARKS_JSON_FILE);
        // $enteredBookMarkData = array_merge($enteredBookMarkData, array('complete' => true));
        return $enteredBookMarkData;
    }

    // public function getJsonValue($jsonFilePath, $key)
    // {
    //     // jsonファイルの中身を取得
    //     $jsonContent = file_get_contents($jsonFilePath);
    //     // jsonファイルのデコード（配列に変換）
    //     // $jsonData = json_decode($jsonContent, true);
    //     // 引数のキーにマッチする値をリターン
    //     $value = isset($jsonData[$key]) ? $jsonData[$key] : '';
    //     return $value;
    // }
    public function toggle_favorite()
    {
        header("Content-Type: application/json; charset=utf-8");

        $clicked_id = json_decode(file_get_contents('php://input'), true);
        $clicked_id = $clicked_id ?? null;

        if ($clicked_id === null)
        {
            echo json_encode(['error' => 'ID is required']);
            exit;
        }

        $get_json_data = file_get_contents(Helper::BOOKMARKS_JSON_FILE);

        $get_json_data_decode = json_decode($get_json_data, true);

        foreach ($get_json_data_decode as $key => $item)
        {
            if ($item['id'] !== $clicked_id) continue;
            $item['favorite'] = !$item['favorite'] ?? true;
        }

        // foreach ($get_json_data_decode as $key => $item)
        // {
        //     if ($item['id'] === $clicked_id['id'])
        //     {
        //         if ($get_json_data_decode[$key]['favorite'] === false)
        //         {
        //             $json_data_decode[$key]['favorite'] = true;
        //         }
        //         else if ($get_json_data_decode[$key]['favorite'] === true)
        //         {
        //             $get_json_data_decode[$key]['favorite'] = false;
        //         }

        //         $id_number = $get_json_data_decode[$key]['id'];
        //         $id_favorite = $get_json_data_decode[$key]['favorite'];

        //         $array_id = [];
        //         $array_id = array('id' => $id_number, 'favorite' => $id_favorite);

        //         header("Content-Type: application/json; charset=utf-8");
        //         $array_id_json = json_encode($array_id, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        //         $get_json_data_decode_json = json_encode($get_json_data_decode, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        //         file_put_contents(Helper::BOOKMARKS_JSON_FILE, $get_json_data_decode_json);

        //         echo $array_id_json;
        //     }
        // }
    }
}