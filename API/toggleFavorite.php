<?php
// [問題] セキュリティ: CSRF対策がない
// - お気に入り切り替えAPIにCSRFトークン検証がない
// - 他のAPI（add.php, deleteBookMark.php）には実装されているのに、このAPIだけ欠落している
// - CSRFトークンをリクエストに含め、検証処理を追加すべき
session_start();
header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . '/../class/BookMarkManager.php';
require_once __DIR__ . '/../class/Helper.php';
$BookMarkManager = new BookMarkManager();
$Helper = new Helper();

$posted = json_decode(file_get_contents('php://input'), true);
$postedId = $posted['id'] ?? null;

if ($postedId === null)
{
    echo json_encode(['error' => 'ID is required']);
    exit;
}

$getJsonData = file_get_contents(Helper::BOOKMARKS_JSON_FILE);
$getJsonDataDecode = json_decode($getJsonData, true);

foreach ($getJsonDataDecode as $key => &$item)
    {
        if ($item['id'] === $postedId)
        {
            $item['favorite'] = !$item['favorite'];
            break;
        }
    }
unset($item);

$json = $BookMarkManager->save_bookMarks($getJsonDataDecode);

echo $json;
