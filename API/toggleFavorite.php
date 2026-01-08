<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . '/../class/BookMarkManager.php';
require_once __DIR__ . '/../class/Helper.php';
$BookMarkManager = new BookMarkManager;
$Helper = new Helper;

// $posted = json_decode(file_get_contents('php://input'), true);
// $postedId = $posted['id'] ?? null;

// if ($postedId === null)
// {
//     echo json_encode(['error' => 'ID is required']);
//     exit;
// }

// $getJsonData = file_get_contents(Helper::BOOKMARKS_JSON_FILE);
// $getJsonDataDecode = json_decode($getJsonData, true);

$getJsonDataDecode = $Helper::getJsonData();

foreach ($getJsonDataDecode as $key => &$item)
    {
        if ($item['id'] === $postedId)
        {
            $item['favorite'] = !$item['favorite'];
            break;
        }
    }
unset($item);

    header("Content-Type: application/json; charset=utf-8");
try
{
$json = json_encode(array_values($getJsonDataDecode), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    $tmp = Helper::BOOKMARKS_JSON_FILE . '.tmp';
    $fp = fopen($tmp, 'wb');

    if ($fp === false)
    {
        throw new RuntimeException('Cannot write temp file');
    }

    fwrite($fp, $json);

    fclose($fp);

    rename($tmp, Helper::BOOKMARKS_JSON_FILE);
}
catch (Exception $e)
{
    echo $e->getMessage() . "<br>";
    exit();
}
echo $json;
