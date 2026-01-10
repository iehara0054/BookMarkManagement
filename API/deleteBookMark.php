<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . '/../class/BookMarkManager.php';
require_once __DIR__ . '/../class/Helper.php';
$BookMarkManager = new BookMarkManager;
$Helper = new Helper;

// JSONデータをPHP連想配列にデコードする
$items = json_decode($jsonData, true);

// AjaxからPOSTされたJSONデータを受け取る
$postData = json_decode(file_get_contents('php://input'), true);

if ($postData && isset($postData['key']) && isset($postData['value']))
{
    $targetKey = $postData['key'];
    $targetValue = $postData['value'];

    // array_filterを使って特定のキーと値を持つ要素を全て除外する
    $filteredItems = array_filter($items, function ($item) use ($targetKey, $targetValue)
    {
        // 条件に合わない（削除しない）要素だけを保持する
        return !(isset($item[$targetKey]) && $item[$targetKey] == $targetValue);
    });

    // キーが連番になるように配列のインデックスを振り直す
    $filteredItems = array_values($filteredItems);

    // 更新されたデータをJSON形式に戻す
    $updatedJsonData = json_encode($filteredItems, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    // （必要に応じて）元のファイルに書き戻す処理
    // file_put_contents('data.json', $updatedJsonData);

    // クライアントに成功レスポンスを返す
    echo $updatedJsonData;
}
else
{
    // エラーハンドリング
    echo json_encode(['status' => 'error', 'message' => '無効な入力データです']);
}

// $jsonData = file_get_contents('php://input');
// $dataArray = json_decode($jsonData, true);

// if ($dataArray === null && json_last_error() !== JSON_ERROR_NONE)
// {
//     echo json_encode(['status' => 'error', 'message' => '無効なJSONデータです。']);
//     exit;
// }

// $keyToRemove = $request_data['key'];
// $valueToRemove = $request_data['value'];

// $getJsonData = file_get_contents(Helper::BOOKMARKS_JSON_FILE);
// $getJsonDataDecoded = json_decode($getJsonData, true);

// $filteredArray = array_filter($getJsonDataDecoded, function ($item) use ($keyToRemove, $valueToRemove)
// {
//     if (is_array($item) && isset($item[$keyToRemove]) && $item[$keyToRemove] == $valueToRemove)
//     {
//         return false;
//     }
//     return true;
// });