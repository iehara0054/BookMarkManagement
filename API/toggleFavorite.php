<?php
session_start();
header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . '/../class/BookMarkManager.php';
$BookMarkManager = new BookMarkManager;

$posted_data = json_decode(file_get_contents('php://input'), true);
$target_id = $posted_data['id'] ?? null;

if ($target_id === null)
{
    echo json_encode(['error' => 'ID is required']);
    exit;
}

$json_data = file_get_contents($BookMarkManager::BOOKMARKS_JSON_FILE);
$jsonDecodedData = json_decode($json_data, true);

foreach ($jsonDecodedData as $key => $item)
{
    if ($item['id'] === $target_id)
    {
        if ($jsonDecodedData[$key]['favorite'] === false)
        {
            $jsonDecodedData[$key]['favorite'] = true;
        }
        else if ($jsonDecodedData[$key]['favorite'] === true)
        {
            $jsonDecodedData[$key]['favorite'] = false;
        }

        break;
    }
}

header("Content-Type: application/json; charset=utf-8");
$updated_json_data = json_encode($jsonDecodedData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
file_put_contents($BookMarkManager::BOOKMARKS_JSON_FILE, $updated_json_data);

echo $updated_json_data;
// var_dump($updated_json_data);