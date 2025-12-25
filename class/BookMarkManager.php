<?php
require_once __DIR__ . '/Helper.php';

class BookMarkManager
{
    public $Helper;

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
    public function save_bookMarks(array $enteredBookMarkData): string
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
        // $enteredBookMarkData = array_merge($enteredBookMarkData, array('complete' => true))
        return $json;
    }

    public function toggle_favorite()
    {
        $posted_data = json_decode(file_get_contents('php://input'), true);
        $target_id = $posted_data['id'] ?? null;

        if ($target_id === null)
        {
            echo json_encode(['error' => 'ID is required']);
            exit;
        }

        $get_json_data = file_get_contents(Helper::BOOKMARKS_JSON_FILE);
        $get_json_data_decode = json_decode($get_json_data, true);

        foreach ($get_json_data_decode as $key => $item)
        {
            // if ($item['id'] == $clicked_id['id']) continue;
            // $item['favorite'] = !$item['favorite'];

            foreach ($get_json_data_decode as $key => $item)
            {
                if ($item['id'] === $target_id)
                {
                    if ($get_json_data_decode[$key]['favorite'] === false)
                    {
                        $get_json_data_decode[$key]['favorite'] = true;
                    }
                    else if ($get_json_data_decode[$key]['favorite'] === true)
                    {
                        $get_json_data_decode[$key]['favorite'] = false;
                    }

                    // break;
                }
            }
            // $item_array = [];
            // $item_array = array('id' => $item['id'], 'favorite' => $item['favorite']);

            header("Content-Type: application/json; charset=utf-8");

            $json = json_encode(array_values($get_json_data_decode), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
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
            echo $json;
            break;
        }
        unset($item);
    }
}