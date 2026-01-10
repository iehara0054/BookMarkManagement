<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . '/../class/BookMarkManager.php';
require_once __DIR__ . '/../class/Helper.php';
$BookMarkManager = new BookMarkManager;
$Helper = new Helper;

$postData = json_decode(file_get_contents('php://input'), true);

$target_key = $postData['key'];
$target_value = $postData['value'];

// ファイル読み込み
$data = json_decode(file_get_contents(Helper::BOOKMARKS_JSON_FILE), true);

// 削除実行（keyと値が両方一致するものを削除）
$new_data = array_values(array_filter($data, function ($item) use ($target_key, $target_value)
{
    return !(isset($item[$target_key]) && $item[$target_key] === $target_value);
}));

// 保存
file_put_contents(Helper::BOOKMARKS_JSON_FILE, json_encode($new_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));