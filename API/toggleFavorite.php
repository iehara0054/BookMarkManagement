<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . '/../class/BookMarkManager.php';
require_once __DIR__ . '/../class/Helper.php';
$BookMarkManager = new BookMarkManager;
$Helper = new Helper;


// $BookMarkManager->toggle_favorite()

$posted_data = json_decode(file_get_contents('php://input'), true);
$target_id = $posted_data['id'] ?? null;

if ($target_id === null)
{
    echo json_encode(['error' => 'ID is required']);
    exit;
}

$get_json_data = file_get_contents(Helper::BOOKMARKS_JSON_FILE);
$get_json_data_decode = json_decode($get_json_data, true);

foreach ($get_json_data_decode as $key => $item)
{
    // if ($item['id'] == $clicked_id['id']) continue;
    // $item['favorite'] = !$item['favorite'];

    foreach ($get_json_data_decode as $key => $item)
    {
        if ($item['id'] === $target_id)
        {
            if ($get_json_data_decode[$key]['favorite'] === false)
            {
                $get_json_data_decode[$key]['favorite'] = true;
            }
            else if ($get_json_data_decode[$key]['favorite'] === true)
            {
                $get_json_data_decode[$key]['favorite'] = false;
            }

            // break;
        }
    }
    // $item_array = [];
    // $item_array = array('id' => $item['id'], 'favorite' => $item['favorite']);

    header("Content-Type: application/json; charset=utf-8");

    $json = json_encode(array_values($get_json_data_decode), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    // var_dump($bookMarks);
    $tmp = Helper::BOOKMARKS_JSON_FILE . '.tmp';
    $fp = fopen($tmp, 'wb');

    if ($fp === false)
    {
        throw new RuntimeException('Cannot write temp file');
    }

    fwrite($fp, $json);

    fclose($fp);

    rename($tmp, Helper::BOOKMARKS_JSON_FILE);
    // $enteredBookMarkData = array_merge($enteredBookMarkData, array('complete' => true));
    echo $json;
    break;
}
unset($item);
