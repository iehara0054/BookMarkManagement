<?php
require_once __DIR__ . '/Helper.php';

class BookMarkManager
{
    public $Helper;

    public function __construct()
    {
        $this->Helper = new Helper();
    }

    const URL_VALID = true;
    const URL_ERROR = false;

    /**
     * ブックマークデータをJSONファイルから読み込む
     * 
     * @return array ブックマーク一覧。ファイルが存在しない場合は空
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
     * @return string 保存したjsonファイル
     */
    public function save_bookMarks(array $enteredBookMarkData): string
    {
        try
        {
            $json = json_encode(array_values($enteredBookMarkData), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

            $tmp = $this->Helper::BOOKMARKS_JSON_FILE . '.tmp';
            $fp = fopen($tmp, 'wb');

        // [レビュー指摘:低] このif文のインデントが前後のコードと揃っていない
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
     * @return array 削除の完了したブックマークデータ
     */
    public function delete_bookMarks(string $targetKey, $targetValue): array
    {
        try
        {
            // [レビュー指摘:中] file_get_contentsを直接呼んでいる。load_bookmarkLists()メソッドを活用すべき（責務の重複）
            $getJsonDataDecode = $this->load_bookmarkLists();

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
    public function search_bookmarks($targetValue)
    {
        $getJsonDataDecodeA = $this->load_bookmarkLists();

        $filteredValue = array_filter($getJsonDataDecode, function ($item) use ($targetValue)
        {
            if (stripos($item['title'], $targetValue) !== false)
            {
                return true;
            }
            // [レビュー指摘:中] memoがnullや空の場合、PHP 8.xで警告が出る可能性がある。$item['memo'] ?? '' を使うべき
            if (stripos((string)($item['memo'] ?? ''), $targetValue) !== false)
            {
                return true;
            }
            // [レビュー指摘:中] tagsがnullや文字列の場合にTypeErrorが発生する。事前にis_arrayチェックを入れるべき
            if (is_array($item['tags']))
            {
            foreach ($item['tags'] as $tag)
            {
                    if (stripos(trim((string)(($tag ?? ''))), $targetValue) !== false)
                {
                    return true;
                }
            }
            }
        });
        return is_array($filteredValue) ? $filteredValue : [];
    }
}