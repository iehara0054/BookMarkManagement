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
     * @return void
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
}