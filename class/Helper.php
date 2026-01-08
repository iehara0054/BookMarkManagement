<?php
class Helper
{
    // ========================================
    // 定数定義とヘルパー関数
    // ========================================
    public const BOOKMARKS_JSON_FILE = __DIR__ . '/../data/bookmarks_file.json';

    /**
     * URLのバリデーション
     * 
     * @param string $url バリデーションする文字列
     */
    public function is_valid_url($url, $enteredBookMarkData, $userEnteredLowTags): bool
    {
        $validUrl = false !== filter_var($url, FILTER_VALIDATE_URL) && preg_match('@^https?+://@i', $url) > 0;

        if ($validUrl === false)
        {
            if ($_POST['url'] ?? '')
            {
                $_SESSION['error_url'] = 'URLの形式ではありません';
                $_SESSION['detectedErrorUrl'] = $enteredBookMarkData;
                $_SESSION['detectedErrorUrl']['userEnteredLowTags'] = $userEnteredLowTags;
            }

            header('Location: http://localhost/iehara/BookMarkManegiment/index.php');
            exit();
        }
        return $validUrl;
    }

    /**
     * タグをカンマで区切るり、配列としてわたす
     * 
     * @param string $tags 画面からPOSTされた配列
     * @return string $splitTags 分割されたタグ
     */
    public function splitTags($tags)
    {
        return $splitTags = explode(',', $tags);
    }

    public static function getJsonData(): array
    {
        $posted = json_decode(file_get_contents('php://input'), true);
        $postedId = $posted['id'] ?? null;

        if ($postedId === null)
        {
            echo json_encode(['error' => 'ID is required']);
            exit;
        }

        $getJsonData = file_get_contents(Helper::BOOKMARKS_JSON_FILE);
        return $getJsonDataDecode = json_decode($getJsonData, true);
    }
}