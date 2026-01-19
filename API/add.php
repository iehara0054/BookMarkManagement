<?php
require_once __DIR__ . '/../class/BookMarkManager.php';
require_once __DIR__ . '/../class/Helper.php';
session_start();

$BookMarkManager = new BookMarkManager();
$Helper = new Helper();

//============================================================
// POSTリクエストの処理
// ============================================================
$tags = '';
$enteredBookMarkData = [];
$enteredBookMarkData = $_POST;

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
		$bookMarkList = $BookMarkManager->load_bookmarkLists();

		$now = date('c');

		//============================================================================
		// アクション: ブックマークの追加
		// ============================================================================
		// フォームからPOSTされた値を受け取る
		$splitTags = [];
		$title = trim((string)($_POST['title'] ?? ''));
		$url = trim((string)($_POST['url'] ?? ''));
		$memo = trim((string)($_POST['memo'] ?? ''));
		$tags = trim((string)($_POST['tags'] ?? ''));
		$userEnteredLowTags = trim((string)($_POST['tags'] ?? ''));
		$favorite = false; // お気に入りは常にfalse

		if ($title === '' || $url === '')
		{
			$errors[] = 'Title is required.';
		}
		else
		{
			$splitTags = explode(",", $tags);
			$splitTags = array_filter($splitTags);

			$enteredBookMarkData = array(
				'id' => bin2hex(random_bytes(8)),
				'title' => $title,
				'url' => $url,
				'memo' => $memo,
				'favorite' => false,
				'tags' => $splitTags,
				'delete_key' => bin2hex(random_bytes(8)),
				'created_at' => $now,
				'updated_at' => $now,
			);

			$Helper->is_valid_url($url, $enteredBookMarkData, $userEnteredLowTags);

			$bookMarkList[] = $enteredBookMarkData;

			$BookMarkManager->save_bookMarks($bookMarkList);

			$_SESSION['success_message'] = 'ブックマークを追加しました';

			header('Location: ../index.php');
			exit();
		}
	}
}
