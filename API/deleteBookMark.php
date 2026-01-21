<?php
require_once __DIR__ . '/../class/BookMarkManager.php';
require_once __DIR__ . '/../class/Helper.php';
session_start();

$BookMarkManager = new BookMarkManager();
$Helper = new Helper();

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
        $targetKey = 'deleteKey';
        $targetValue = $_POST['deleteKey'] ?? null;

        // [問題] 条件が常にfalse - $targetKeyは直前で'deleteKey'に固定されているため、この条件は常にfalse
        // - $targetValue をチェックすべき（if ($targetValue === null)）
        if ($targetKey === null)
        {
            echo json_encode(['error' => 'deleteKey is required']);
            exit;
        }

        /** @var array $newData */
        $newData = $BookMarkManager->delete_bookMarks($targetKey, $targetValue);

        $BookMarkManager->save_bookMarks($newData);

        $_SESSION['delete_message'] = 'ブックマークを削除しました';
        $_SESSION['delete_flg'] = 'delete_flg';

        header('Location: ../index.php');
        exit();
    }
}