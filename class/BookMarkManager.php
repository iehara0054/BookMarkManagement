<?php
require_once __DIR__ . '/Helper.php';

class BookMarkManager
{
    // [問題] 不要な依存注入 - $this->Helper はクラス内で一度も使用されていない
    // - 削除するか、実際に使用すべき
    public $Helper;

    public function __construct()
    {
        $this->Helper = new Helper();
    }

    /**
     * ブックマークデータをJSONファイルから読み込む
     * 
     * @return array　$data ブックマーク一覧。ファイルが存在しない場合は空
     */
    public function load_bookmarkLists(): array
    {
        if (!file_exists($this->Helper::BOOKMARKS_JSON_FILE))
        {
            return [];
        }

        $json = file_get_contents($this->Helper::BOOKMARKS_JSON_FILE);
        $data = json_decode($json, true);

        return is_array($data) ? $data : [];
    }

    /**
     * ブックマークデータをJSONファイルに保存
     * 
     * @param array $bookMarks 保存するタスクの配列
     * @return string $json　保存したjsonファイル
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
     * @param string $targetKey 文字列'target_key'
     * @param string $targetValue　削除するブックマークのID
     * @return array $newData 削除の完了したブックマークデータ
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
        return is_array($newData) ? $newData : [];
    }

    /**
     * ブックマークデータの絞り込み検索の表示
     * 
     * @param string $targetKey 検索するキー
     * @param string $targetValue 検索する文字列
     * @return array ブックマークデータの絞り込み検索の表示。ファイルが存在しない場合は空
     */
    // [問題] ファイル存在チェックなし
    // - load_bookmarkLists()にはfile_exists()チェックがあるのに、この関数にはない
    // - 一貫性がなく、ファイルが存在しない場合にエラーになる
    public function search_bookmarks($targetValue)
    {
        $getJsonData = file_get_contents(Helper::BOOKMARKS_JSON_FILE);
        $getJsonDataDecode = json_decode($getJsonData, true);

        $filteredValue = array_filter($getJsonDataDecode, function ($item) use ($targetValue)
        {
            if (stripos($item['title'], $targetValue) !== false)
            {
                return true;
            }
            if (stripos($item['memo'], $targetValue) !== false)
            {
                return true;
            }
            // [問題] tagsがnullや未定義の場合の考慮不足
            // - $item['tags']がnullや未定義の場合にエラーになる
            // - if (!empty($item['tags'])) でチェックすべき
            foreach ($item['tags'] as $tag)
            {
                if (stripos((trim($tag)), $targetValue) !== false)
                {
                    return true;
                }
            }
        });
        return is_array($filteredValue) ? $filteredValue : [];
    }
}