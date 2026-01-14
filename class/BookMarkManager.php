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
     * @return array ブックマーク一覧。ファイルが存在しない場合は空
     */
    public function load_bookmarkLists(): array
    {
        if (!file_exists(Helper::BOOKMARKS_JSON_FILE))
        {
            return [];
        }

        $json = file_get_contents(Helper::BOOKMARKS_JSON_FILE);
        $data = json_decode($json, true);

        return is_array($data) ? $data : [];
    }

    /**
     * タスクデータをJSONファイルに保存
     * 
     * @param array $bookMarks 保存するタスクの配列
     * @return string $json
     */
    public function save_bookMarks(array $enteredBookMarkData): string
    {
        try
        {
            $json = json_encode(array_values($enteredBookMarkData), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $tmp = Helper::BOOKMARKS_JSON_FILE . '.tmp';
        $fp = fopen($tmp, 'wb');

        if ($fp === false)
        {
            throw new RuntimeException('Cannot write temp file');
        }

        fwrite($fp, $json);

        fclose($fp);

        rename($tmp, Helper::BOOKMARKS_JSON_FILE);
        }
        catch (Exception $e)
        {
            echo $e->getMessage() . "<br>";
            exit();
        }
        return $json;
    }
    /**
     * 削除ボタンの押されたブックマークを削除する
     * 
     * @param $targetKey 文字列'target_key'
     * @param $targetValue　削除するブックマークのID
     * @return $newData 削除の完了したブックマークデータ
     */
    public function delete_bookMarks(string $targetKey, $targetValue): array
    {
        try
        {
            $getJsonData = file_get_contents(Helper::BOOKMARKS_JSON_FILE);
            $getJsonDataDecode = json_decode($getJsonData, true);

            $newData = array_values(array_filter($getJsonDataDecode, function ($item) use ($targetKey, $targetValue)
            {
                return !(isset($item[$targetKey]) && $item[$targetKey] === $targetValue);
            }));
        }
        catch (Exception $e)
        {
            echo $e->getMessage() . "<br>";
            exit();
        }
        return $newData;
    }

    /**
     * ブックマークデータの絞り込み検索の表示
     * 
     * @return array ブックマークデータの絞り込み検索の表示。ファイルが存在しない場合は空
     */
    public function search_bookmarks($searchItem): string
    {
        if ($searchItem ?? null)
        {
            $data = json_decode($searchItem, true);
        }
        return $data;
    }
}