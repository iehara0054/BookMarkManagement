<?php
// JSON形式で送られてきたデータを受け取る
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// idを取得
$itemId = $data['id'] ?? null;

// 確認用の出力
var_dump($itemId);