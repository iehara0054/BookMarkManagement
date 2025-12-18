<?php
require_once __DIR__ . '/../class/BookMarkManager.php';
require_once __DIR__ . '/../class/Helper.php';
session_start();

$BookMarkManager = new BookMarkManager;
$Helper = new Helper;
/**
* お気に入りの状態をJSONファイルから読み込む
*
* @return array お気に入りのtrue, falseの状態
*/
function onload_favorite()
{
    if (!file_exists(Helper::BOOKMARKS_JSON_FILE))
    {
        return [];
    }

    $json = file_get_contents(Helper::BOOKMARKS_JSON_FILE);

    $json = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    // file_put_contents(Helper::BOOKMARKS_JSON_FILE, $array_json);

    echo $json;
}