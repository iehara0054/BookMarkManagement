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
    public function is_valid_url(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false && preg_match('@^https?+://@i', $url) > 0;
    }
}