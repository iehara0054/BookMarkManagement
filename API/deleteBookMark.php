<?php
require_once __DIR__ . '/../class/BookMarkManager.php';
require_once __DIR__ . '/../class/Helper.php';
session_start();

$BookMarkManager = new BookMarkManager;
$Helper = new Helper;

//一時退避
// 削除実行（keyと値が両方一致するものを削除）
// $new_data = array_values(array_filter($data, function ($item) use ($target_key, $target_value)
// {
//     return !(isset($item[$target_key]) && $item[$target_key] === $target_value);
// }));

//============================================================
// POSTリクエストの処理
// ============================================================
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if (!hash_equals($_SESSION["csrf_token"], $_POST['csrf_token'] ?? ''))
    {
        http_response_code(400);
        $errors[] = 'Invalid CSRF token.';
    }
    else
    {
        $targetId = 'id';
        $targetValue = $_POST['id'] ?? null;

        if ($targetId === null)
        {
            echo json_encode(['error' => 'ID is required']);
            exit;
        }

        try
        {
            $getJsonData = file_get_contents(Helper::BOOKMARKS_JSON_FILE);
            $getJsonDataDecode = json_decode($getJsonData, true);

            $newData = array_values(array_filter($getJsonDataDecode, function ($item) use ($targetId, $targetValue)
            {
                return !(isset($item[$targetId]) && $item[$targetId] === $targetValue);
            }));
        }
        catch (Exception $e)
        {
            echo $e->getMessage() . "<br>";
            exit();
        }

        $BookMarkManager->save_bookMarks($newData);

        $_SESSION['delete_message'] = 'ブックマークを削除しました';

        header('Location: ../index.php');
        exit();
    }
}