<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . '/../class/BookMarkManager.php';
require_once __DIR__ . '/../class/Helper.php';
$BookMarkManager = new BookMarkManager;
$Helper = new Helper;

$posted = json_decode(file_get_contents('php://input'), true);

$targetKey = 'id';
$targetValue = $posted['id'] ?? null;

if ($targetKey === null)
{
    echo json_encode(['error' => 'ID is required']);
    exit;
}

$searchedBookMark = $BookMarkManager->search_bookmarks($targetKey, $targetValue);

// $getJsonData = file_get_contents(Helper::BOOKMARKS_JSON_FILE);
// $getJsonDataDecode = json_decode($getJsonData, true);

// foreach ($getJsonDataDecode as $key => &$item)
//     {
//         if ($item['id'] === $postedId)
//         {
//             $item['favorite'] = !$item['favorite'];
//             break;
//         }
//     }
// unset($item);

// $json = $BookMarkManager->save_bookMarks($getJsonDataDecode);

echo $json = json_encode(array_values($searchedBookMark), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);