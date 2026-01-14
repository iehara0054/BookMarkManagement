<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . '/../class/BookMarkManager.php';
require_once __DIR__ . '/../class/Helper.php';
$BookMarkManager = new BookMarkManager;
$Helper = new Helper;

//============================================================
// POSTリクエストの処理
// ============================================================
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if (!hash_equals($_SESSION["csrf_token"], $_POST['csrf_token'] ?? ''))
    {
        http_response_code(400);
        $errors[] = 'Invalid CSRF token.';
    }
    else
    {
        $getJsonData = file_get_contents(Helper::BOOKMARKS_JSON_FILE);
        $getJsonDataDecode = json_decode($getJsonData, true);
        
        $searchTitle = $_POST['searchTitle'];

        $filteredTitle = array_filter($getJsonDataDecode, function ($item) use ($searchTitle)
        {
            if ($item['title'] === $searchTitle)
                {
                    return $item;
                }
        });
        var_dump($getJsonDataDecode);
        $BookMarkManager->search_bookmarks($filteredTitle);
    }
    
}