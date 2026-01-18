<?php
require_once __DIR__ . '/../class/BookMarkManager.php';
require_once __DIR__ . '/../class/Helper.php';
session_start();

$BookMarkManager = new BookMarkManager;
$Helper = new Helper;

//============================================================
// POSTリクエストの処理
// ============================================================

// var_dump($_POST['searchValue']);

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
        $searchValue = $_POST['searchValue'] ?? '';

        if ($searchValue === null)
        {
            echo json_encode(['error' => 'json value is required']);
            exit;
        }

        $filteredValue =  $BookMarkManager->search_bookmarks($searchValue);

        header('Location: ../index.php');
        exit();
        // $targetMemoKey = 'memo';
        // $targetMemo = $_POST['searchMemo'] ?? '';

        // if ($targetMemo === null)
        // {
        //     echo json_encode(['error' => 'delete_key is required']);
        //     exit;
        // }

        // $filteredMemo =  $BookMarkManager->search_bookmarks($targetMemoKey, $targetMemo);

        // $targetTagsKey = 'tags';
        // $targetTags = $_POST['searchTags'] ?? '';

        // if ($targetMemo === null)
        // {
        //     echo json_encode(['error' => 'delete_key is required']);
        //     exit;
        // }

        // $filteredTags =  $BookMarkManager->search_bookmarks($targetTagsKey, $targetTags);
    }
}