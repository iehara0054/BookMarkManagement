<?php
require_once __DIR__ . '/class/BookMarkManager.php';
require_once __DIR__ . '/class/Helper.php';

$BookMarkManager = new BookMarkManager;
$Helper = new Helper;
// ========================================
// セッション管理とCSRF対策
// ========================================
session_start();

if (empty($_SESSION['csrf_token']))
{
    $csrf_token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $csrf_token;
}


// ========================================
// 定数定義とヘルパー関数
// ========================================
//エスケープ関数
function h($str)
{
    return htmlspecialchars($str);
}

// ========================================
// onload時のお気に入りの状態
// ========================================

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>ブックマークマネージャー</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <h1>ブックマークリスト</h1>
    <p>ブックマークを追加・更新ができます</p>
    <form id="inputForm" method="POST" action="./API/add.php">

        <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">

        <?php if (!empty($_SESSION['success_message'])): ?>
            <div class="success-message">
                <?= h($_SESSION['success_message']) ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <input id="title" type="text" name="title" placeholder="タイトル" value="<?= h(!empty($_SESSION['detectedErrorUrl']['title']) ? $_SESSION['detectedErrorUrl']['title'] : '') ?>" required>

        <?php if (!empty($_SESSION['error_url'])): ?>
            <div class="error-url">
                <?= h($_SESSION['error_url']) ?>
            </div>
            <?php unset($_SESSION['error_url']); ?>
        <?php endif; ?>

        <input id="url" type="text" name="url" placeholder="URL" value="<?= h(!empty($_SESSION['detectedErrorUrl']['url']) ? $_SESSION['detectedErrorUrl']['url'] : '') ?>" required>

        <input id="memo" type="text" name="memo" placeholder="メモ（任意）" value="<?= h(!empty($_SESSION['detectedErrorUrl']['memo']) ? $_SESSION['detectedErrorUrl']['memo'] : '') ?>">

        <input id="tags" type="text" name="tags" placeholder="タグ・カンマ区切り可(任意)" value="<?= h(!empty($_SESSION['detectedErrorUrl']['userEnteredLowTags']) ? $_SESSION['detectedErrorUrl']['userEnteredLowTags'] : '') ?>">



        <button type="submit">追加</button>
        <button type="button" onclick="clearText()">クリア</button>
        <!-- <input type="button" value="クリア" onclick="clearText()" /> -->
        <?php unset($_SESSION['detectedErrorUrl']); ?>
    </form>

    <div id='list_tpl'>
        <!-- ============================================================================
         ブックマーク一覧の表示
        ============================================================================ -->
        <h2>ブックマーク一覧</h2>
        <?php
        $getBookMarkLists =  $BookMarkManager->load_bookmarkLists();

        if (!empty($_POST['tags']))
        {
            $splitTags = $Helper->splitTags($_POST['tags']);
            $getBookMarkLists = $splitTags;
        }
        // var_dump($bookMarkLists);
        ?>
        <?php if (empty($getBookMarkLists)): ?>
            <!-- タスクが1つもない場合の表示 -->
            <div class="empty">まだタスクがありません。上のフォームから追加してください。</div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['delete_message'])): ?>
            <div class="delete_message">
                <?= h($_SESSION['delete_message']) ?>
            </div>
            <?php unset($_SESSION['delete_message']); ?>
        <?php endif; ?>
        <!-- ブックマークが存在する場合、テーブルで表示 -->
        <table>
            <thead>
                <tr>
                    <!-- テーブルヘッダー -->
                    <th>お気に入り</th>
                    <th>タイトル</th>
                    <th>URL</th>
                    <th>メモ</th>
                    <th>タグ</th>
                    <th>削除</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // $getBookMarkListsを配列として初期化
                if (empty($getBookMarkLists))
                {
                    $getBookMarkLists = [];
                }
                ?>
                <?php foreach (array_reverse($getBookMarkLists) as $b): ?>
                    <form action="./API/deleteBookMark.php" method="post">
                        <tr>
                            <td>
                                <div>
                                    <button class="favorite-btn" data-item-id="<?= h($b['id']) ?>" onclick="toggleFavorite(this)">
                                        <span class="icon">☆</span></button>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <a href="<?= h($b['url'] ?? '') ?>" target="_blank"> <?= h($b['title'] ?? '') ?></a>
                                    <a href="<?= h($b['url'] ?? '') ?>" target="_blank" class="open-new-tab" title="新しいタブで開く">↗️</a>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <a href="<?= h($b['url'] ?? '') ?>" target="_blank"><?= h($b['url'] ?? '') ?></a>
                                    <a href="<?= h($b['url'] ?? '') ?>" target="_blank" class="open-new-tab" title="新しいタブで開く">↗️</a>
                                </div>
                            </td>
                            <td>
                                <div><?= h($b['memo'] ?? '') ?></div>
                            </td>
                            <td>
                                <?php if (!empty($b['tags'])) : ?>
                                    <?php
                                    $currentTags = is_array($b['tags']) ? $b['tags'] : explode(',', $b['tags']);
                                    foreach ($currentTags as $t) :
                                    ?>
                                        <div><?= h(trim($t)) ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">
                                <input type="hidden" name="id" value="<?= h($b['id']) ?>">
                                <button class="delete-btn" name="action" value="delete">削除</button>
                            </td>
                        </tr>
                    </form>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="./js/onload_favorite.js"></script>
    <script src="./js/toggle_favorite.js"></script>
    <script src="./js/utilities.js"></script>
</body>

</html>