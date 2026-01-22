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
// [問題] 無意味な初期化 - 1行目の初期化は直後に上書きされるため不要
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
		/** @var array $bookMarkList */
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
			$normalized_comma_tags = str_replace(['，', '、'], ',', $tags);
			$splitTags = explode(',', $normalized_comma_tags);
			$splitTags = array_filter($splitTags);

			$enteredBookMarkData = array(
				'id' => bin2hex(random_bytes(8)),
				'title' => $title,
				'url' => $url,
				'memo' => $memo,
				'favorite' => false,
				'tags' => $splitTags,
				'deleteKey' => bin2hex(random_bytes(8)),
				'created_at' => $now,
				'updated_at' => $now,
			);

			// [問題] バリデーション後も処理が続行する可能性
			// - is_valid_url()は内部でexit()するが、関数の戻り値を使って明示的に処理を分岐すべき
			// - 現状では処理フローが分かりにくい
			// URLのバリデーション
			if (!$Helper->is_valid_url($url))
			{
				// 無効な場合：エラー情報をセッションに保存
				$_SESSION['error_url'] = 'URLの形式ではありません';
				$_SESSION['detected_error_url'] = $enteredBookMarkData;
				$_SESSION['detected_error_url']['user_entered_low_tags'] = $userEnteredLowTags;

				header('Location: ../index.php');
				exit();
			}

			$bookMarkList[] = $enteredBookMarkData;

			$BookMarkManager->save_bookMarks($bookMarkList);

			$_SESSION['success_message'] = 'ブックマークを追加しました';

			header('Location: ../index.php');
			exit();
		}
	}
}
