<?php
class BookMarkManager
{
    // ========================================
    // 定数定義とヘルパー関数
    // ========================================
    public const BOOKMARKS_JSON_FILE = __DIR__ . '/../json/bookmarks_file.json';
    /**
     * URLのバリデーション
     * 
     * @param string $url バリデーションする文字列
     */
    public function is_valid_url(string $url): bool
    {
        echo 'ここまでis_valid_url';
        return false !== filter_var($url, FILTER_VALIDATE_URL) && preg_match('@^https?+://@i', $url) > 0;
    }

    /**
     * タグをカンマで区切るり、配列としてわたす
     * 
     * @array string $tags 画面からPOSTされた配列
     * @array string $splitTags 分割されたタグ
     */
    public function splitTags($tags)
    {
        return $splitTags = explode(',', $tags);
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
        if (!file_exists($this::BOOKMARKS_JSON_FILE))
        {
            return [];
        }
        // file_get_contents() でファイル全体を文字列として読み込み
        $json = file_get_contents($this::BOOKMARKS_JSON_FILE);
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
        $tmp = $this::BOOKMARKS_JSON_FILE . '.tmp';
        $fp = fopen($tmp, 'wb');

        if ($fp === false)
        {
            throw new RuntimeException('Cannot write temp file');
        }

        fwrite($fp, $json);

        fclose($fp);

        rename($tmp, $this::BOOKMARKS_JSON_FILE);
        $enteredBookMarkData = array_merge($enteredBookMarkData, array('complete' => true));
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
    // public function encored_json(array $jsonData): string
    // {
    //     header("Content-Type: application/json; charset=utf-8");
    //     // var_dump($enteredBookMarkData);
    //     $json = json_encode(array_values($jsonData), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    //     // var_dump($bookMarks);
    //     $tmp = $this::BOOKMARKS_JSON_FILE . '.tmp';
    //     $fp = fopen($tmp, 'wb');

    //     if ($fp === false)
    //     {
    //         throw new RuntimeException('Cannot write temp file');
    //     }

    //     fwrite($fp, $json);

    //     fclose($fp);

    //     rename($tmp, $this::BOOKMARKS_JSON_FILE);


    //     // $updated_json_data = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    //     // file_put_contents($BookMarkManager::BOOKMARKS_JSON_FILE, $updated_json_data);

    //     return $updated_json_data;
    // }
}