<?php
class Helper
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
     * @array string $tags 画面からPOSTされた配列
     * @array string $splitTags 分割されたタグ
     */
    public function splitTags($tags)
    {
        return $splitTags = explode(',', $tags);
    }
}