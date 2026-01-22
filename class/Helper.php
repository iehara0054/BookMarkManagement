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
    public function is_valid_url(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false && preg_match('@^https?+://@i', $url) > 0;
    }
}