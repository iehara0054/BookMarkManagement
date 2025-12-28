<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . '/../class/BookMarkManager.php';
require_once __DIR__ . '/../class/Helper.php';
$BookMarkManager = new BookMarkManager;
$Helper = new Helper;


// $BookMarkManager->toggle_favorite()

$posted = json_decode(file_get_contents('php://input'), true);
$posted_id = $posted['id'] ?? null;




if ($posted_id === null)
{
    echo json_encode(['error' => 'ID is required']);
    exit;
}

$get_json_data = file_get_contents(Helper::BOOKMARKS_JSON_FILE);
$get_json_data_decode = json_decode($get_json_data, true);

// if ($item['id'] == $clicked_id['id']) continue;
// $item['favorite'] = !$item['favorite'];
try
{


    foreach ($get_json_data_decode as $key => &$item)
    {
        if ($item['id'] === $posted_id)
        {
            // if ($item['favorite'] === false)
            // {
            $item['favorite'] = !$item['favorite'];
            // }
            // else if ($item['favorite'] === true)
            // {
            //     $item['favorite'] = !$item['favorite'];
            // }
            break;
        }
    }
    unset($item);
}
catch (Exception $e)
{
    echo $e->getMessage() . "<br>";
    exit();
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
