<?php
class Helper
{
    // ========================================
    // 定数定義とヘルパー関数
    // ========================================
    // [問題] セキュリティ: JSONファイルがWebアクセス可能
    // - data/bookmarks_file.json がWebから直接アクセス可能な場所にある
    // - .htaccessで保護するか、Webルート外に移動すべき
    public const BOOKMARKS_JSON_FILE = __DIR__ . '/../data/bookmarks_file.json';

    /**
     * URLのバリデーション
     *
     * @param string $url バリデーションする文字列
     */
    // [問題] 単一責任の原則違反
    // - この関数がバリデーションだけでなく、セッション設定とリダイレクトも行っている
    // - バリデーションは純粋にtrue/falseを返し、リダイレクト処理は呼び出し元で行うべき
    public function is_valid_url($url, $enteredBookMarkData, $userEnteredLowTags): bool
    {
        $validUrl = false !== filter_var($url, FILTER_VALIDATE_URL) && preg_match('@^https?+://@i', $url) > 0;

        if ($validUrl === false)
        {
            if ($_POST['url'] ?? '')
            {
                $_SESSION['error_url'] = 'URLの形式ではありません';
                $_SESSION['detected_error_url'] = $enteredBookMarkData;
                $_SESSION['detected_error_url']['user_entered_low_tags'] = $userEnteredLowTags;
            }

            // [問題] ハードコードされたURL - 本番環境で動作しない
            // - 相対パスまたは環境変数を使用すべき
            header('Location: ../index.php');
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
}