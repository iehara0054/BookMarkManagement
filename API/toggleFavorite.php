<?php
require_once __DIR__ . '/../class/BookMarkManager.php';
$BookMarkManager = new BookMarkManager;

$jsonArray = file_get_contents('php://input');
$jsonDecoded = json_decode($jsonArray, true);

$itemId = $jsonDecoded['id'] ?? null;

$value = isset($jsonDecoded['id']) ? $jsonDecoded['favorite'] : '';



var_dump($getJsonValue);
var_dump($getJsonValue);