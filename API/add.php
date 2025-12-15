<?php
require_once __DIR__ . '/../class/BookMarkManager.php';
require_once __DIR__ . '/../class/Helper.php';
session_start();

// var_dump($_POST);
$BookMarkManager = new BookMarkManager;
$Helper = new Helper;
// var_dump($BookMarkManager);

/**
 * タスクをJSONファイルに保存する
 * 
 * @param array $tasks 保存するタスクの配列
 * @return void
 */

// ========================================
// 定数定義とヘルパー関数
// ========================================
$Helper::BOOKMARKS_JSON_FILE;

//============================================================
// POSTリクエストの処理
// ============================================================
$tags = '';
$enteredBookMarkData =[];
$enteredBookMarkData = $_POST;
// var_dump($SplitTags);
// var_dump($bookMarks);
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
	if (!hash_equals($_SESSION["csrf_token"], $_POST['csrf_token'] ?? ''))
	{
		// トークンが一致しない場合は400エラーを返す
		http_response_code(400);
		$errors[] = 'Invalid CSRF token.';
	}
	else
	{
		$bookMarkList = $BookMarkManager->load_bookmarkLists();
		// var_dump($bookMarks);
		$now = date('c');
		// var_dump($bookMarks);
		//============================================================================
		// アクション: タスクの追加
		// ============================================================================
		// フォームからPOSTされた値を受け取る
		$splitTags = [];
		$title = trim((string)($_POST['title'] ?? ''));
		$url = trim((string)($_POST['url'] ?? ''));
		$memo = trim((string)($_POST['memo'] ?? ''));
		$tags = trim((string)($_POST['tags'] ?? ''));
		$userEnteredLowTags = trim((string)($_POST['tags'] ?? ''));
		$favorite = false;

		if ($title === '' || $url === '')
		{
			$errors[] = 'Title is required.';
		}
		else
		{
			// $splitTags = [];
			$splitTags = explode(",", $tags);
			// var_dump($splitTags);
			$splitTags = array_filter($splitTags);

			$enteredBookMarkData = array(
				'id' => bin2hex(random_bytes(8)),
				'title' => $title,
				'url' => $url,
				'memo' => $memo,
				'favorite' => false,
				'tags' => $splitTags,
				'created_at' => $now,
				'updated_at' => $now,
			);
			// var_dump($enteredBookMarkData);
			$valid_url = $Helper->is_valid_url($url);
			if ($valid_url === false)
			{
				if ($_POST['url'] ?? '')
				{
					$_SESSION['error_url'] = 'URLの形式ではありません';
					$_SESSION['detectedErrorUrl'] = $enteredBookMarkData;
					$_SESSION['detectedErrorUrl']['userEnteredLowTags'] = $userEnteredLowTags;
				}

				header('Location: http://localhost/iehara/BookMarkManegiment/index.php');
				exit();
			}
			// var_dump($bookMarks);
			$bookMarkList[] = $enteredBookMarkData;

			$BookMarkManager->save_bookMarks($bookMarkList);

			// unset($enteredBookMarkData['tags']);

			// var_dump($bookMarks);
			//PRGパターン
			$_SESSION['success_message'] = 'ブックマークを追加しました';

			header('Location: ../index.php');
			exit();
		}
	}
}
