<?php
require_once __DIR__ . '/../class/BookMarkManager.php';
require_once __DIR__ . '/../class/Helper.php';
session_start();

$BookMarkManager = new BookMarkManager;
$Helper = new Helper;

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

        /** @var array $newData */
        $newData = $BookMarkManager->delete_bookMarks($targetId, $targetValue);

        $BookMarkManager->save_bookMarks($newData);

        $_SESSION['delete_message'] = 'ブックマークを削除しました';

        header('Location: ../index.php');
        exit();
    }
}