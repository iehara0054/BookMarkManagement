<?php
require_once __DIR__ . '../class/BookMarkManager.php';
$BookMarkManager = new BookMarkManager;

$json = file_get_contents('php://input');
// $data = json_decode($json, true);
$itemId = $data['id'] ?? null;

$getJsonValue = $BookMarkManager->getJsonValue($BookMarkManager::BOOKMARKS_JSON_FILE, $itemId);

var_dump($getJsonValue);