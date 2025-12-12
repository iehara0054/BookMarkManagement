<?php
echo 'ここまで-1';
require_once __DIR__ . '/../class/BookMarkManager.php';

session_start();

// var_dump($_POST);
$BookMarkManager = new BookMarkManager;
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
BookMarkManager::BOOKMARKS_FILE;

//============================================================
// POSTリクエストの処理
// ============================================================
$tags = '';
$enteredBookMarkData =[];
$enteredBookMarkData = $_POST;
// var_dump($SplitTags);
// var_dump($bookMarks);
$errors = [];
echo 'ここまで０';
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
	echo 'ここまで１';
	if (!hash_equals($_SESSION["csrf_token"], $_POST['csrf_token'] ?? ''))
	{
		echo 'トークンエラー';
		// トークンが一致しない場合は400エラーを返す
		http_response_code(400);
		$errors[] = 'Invalid CSRF token.';
	}
	else
	{
		$bookMarkList = $BookMarkManager->load_bookmarkLists();
		// var_dump($bookMarks);
		echo 'ここまで3';
		$now = date('c');
		// var_dump($bookMarks);
		//============================================================================
		// アクション: タスクの追加
		// ============================================================================

		echo 'ここまで４';

		// ========================================
		// エラーチェック
		// ========================================
		// フォームからPOSTされた値を受け取る
		$SplitTags = [];
		$title = trim((string)($_POST['title'] ?? ''));
		$url = trim((string)($_POST['url'] ?? ''));
		$memo = trim((string)($_POST['memo'] ?? ''));
		$tags = trim((string)($_POST['tags'] ?? ''));
		$userEnteredLowTags = trim((string)($_POST['tags'] ?? ''));
		$favorite = false;

		// フォームデータを配列として初期化
		// if (!is_array($_SESSION['form_data']))
		// {
		// 	$_SESSION['form_data'] = [];
		// }

		// $_SESSION['form_data']に値を代入
		// $_SESSION['form_data']['favorite'] = $favorite;
		// $_SESSION['form_data']['title'] = $title;
		// $_SESSION['form_data']['originUrl'] = $url;
		// $_SESSION['form_data']['url'] = $url;
		// $_SESSION['form_data']['memo'] = $memo;
		// $_SESSION['form_data']['tags'] = $tags;


		

		if ($title === '' || $url === '')
		{
			$errors[] = 'Title is required.';
		}
		else
		{
			$splitTags = [];
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
			$valid_url = $BookMarkManager->is_valid_url($url);
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
			echo 'ここまで５';
			// var_dump($bookMarks);
			$bookMarkList[] = $enteredBookMarkData;

			$BookMarkManager->save_bookMarks($bookMarkList);

			unset($enteredBookMarkData['tags']);
			
			// var_dump($bookMarks);
			//PRGパターン
			$_SESSION['success_message'] = 'ブックマークを追加しました';

			header('Location: ../index.php');
			exit();
		}
	}
}
