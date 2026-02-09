<?php
require_once __DIR__ . '/class/BookMarkManager.php';
require_once __DIR__ . '/class/Helper.php';

$BookMarkManager = new BookMarkManager();
$Helper = new Helper();

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
    return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?>

<!-- [レビュー指摘:高] DOCTYPEにlang属性は無効。正しくは <!DOCTYPE html> と <html lang="ja"> に分離すること -->
<!DOCTYPE html lang="ja">
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ブックマークマネージャー</title>
    <link rel="stylesheet" href="./css/style.css?v=3">
</head>

<body>
    <!-- ============================================================================
         モーダル機能
        ============================================================================ -->
    <?php if (isset($_SESSION['showModal']) && $_SESSION['showModal'] === $BookMarkManager::URL_ERROR): ?>
        <script src="./js/mobile_show_modal.js"></script>
    <?php endif; ?>
    <!-- [レビュー指摘:高] ここで showModal を unset しているため、254行目の判定(URL_VALID)が常にfalseになり modal_close.js が読み込まれない -->
    <?php unset($_SESSION['showModal']); ?>
    <!-- ボタン -->
    <button id="rotateBtn" class="animated-button"><span class="btn-icon">＋</span> 追加</button>

    <!-- モーダル（ネイティブダイアログ） -->
    <dialog id="myModal">
        <p>ブックマークを追加・更新ができます</p>
        <!-- [レビュー指摘:中] id="inputForm" が91行目のフォームと重複している。HTMLではIDはページ内で一意でなければならない -->
        <form id="inputForm" method="POST" action="./API/add.php">

            <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">

            <!-- [レビュー指摘:低] モーダル内の success_message 表示後に unset していない。デスクトップ側(99行目)では unset しているが、ここでは残り続ける -->
            <?php if (!empty($_SESSION['success_message'])): ?>
                <div class="successMessage">
                    <?= h($_SESSION['success_message']) ?>
                </div>
            <?php endif; ?>

            <input id="titleModal" type="text" name="title" placeholder="タイトル（必須）" value="<?= h(!empty($_SESSION['detected_error_url']['title']) ? $_SESSION['detected_error_url']['title'] : '') ?>" required>

            <!-- [レビュー指摘:低] モーダル内の error_url も unset がない（デスクトップ側108行目にはある） -->
            <?php if (!empty($_SESSION['error_url'])): ?>
                <div class="error-url">
                    <?= h($_SESSION['error_url']) ?>
                </div>
            <?php endif; ?>

            <input id="urlModal" type="text" name="url" placeholder="URL（必須）" value="<?= h(!empty($_SESSION['detected_error_url']['url']) ? $_SESSION['detected_error_url']['url'] : '') ?>" required>

            <input id="memoModal" type="text" name="memo" placeholder="メモ（任意）" value="<?= h(!empty($_SESSION['detected_error_url']['memo']) ? $_SESSION['detected_error_url']['memo'] : '') ?>">

            <input id="tagsModal" type="text" name="tags" placeholder="タグ・カンマ区切り可(任意・全角カンマ可)" value="<?= h(!empty($_SESSION['detected_error_url']['user_entered_low_tags']) ? $_SESSION['detected_error_url']['user_entered_low_tags'] : '') ?>">

            <button type="submit">追加</button>
            <button type="button" onclick="clearText()">クリア</button>


        </form>
        <button id="closeBtn">閉じる</button>
    </dialog>


    <h1>ブックマークリスト</h1>
    <!-- ============================================================================
         入力フィールド
        ============================================================================ -->
    <p>ブックマークを追加・更新ができます</p>
    <form id="inputForm" class="mobileNone" method="POST" action="./API/add.php">

        <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">

        <?php if (!empty($_SESSION['success_message'])): ?>
            <div class="successMessage">
                <?= h($_SESSION['success_message']) ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <input id="title" type="text" name="title" placeholder="タイトル（必須）" value="<?= h(!empty($_SESSION['detected_error_url']['title']) ? $_SESSION['detected_error_url']['title'] : '') ?>" required>

        <?php if (!empty($_SESSION['error_url'])): ?>
            <div class="error-url">
                <?= h($_SESSION['error_url']) ?>
            </div>
            <?php unset($_SESSION['error_url']); ?>
        <?php endif; ?>

        <input id="url" type="text" name="url" placeholder="URL（必須）" value="<?= h(!empty($_SESSION['detected_error_url']['url']) ? $_SESSION['detected_error_url']['url'] : '') ?>" required>

        <input id="memo" type="text" name="memo" placeholder="メモ（任意）" value="<?= h(!empty($_SESSION['detected_error_url']['memo']) ? $_SESSION['detected_error_url']['memo'] : '') ?>">

        <input id="tags" type="text" name="tags" placeholder="タグ・カンマ区切り可(任意・全角カンマ可)" value="<?= h(!empty($_SESSION['detected_error_url']['user_entered_low_tags']) ? $_SESSION['detected_error_url']['user_entered_low_tags'] : '') ?>">

        <button type="submit">追加</button>
        <button type="button" onclick="clearText()">クリア</button>

        <?php unset($_SESSION['detected_error_url']); ?>
    </form>

    <div id='listTpl'>
        <!-- ============================================================================
         ブックマーク一覧の表示
        ============================================================================ -->
        <h2>ブックマーク一覧</h2>

        <!-- [レビュー指摘:高] CSRFトークンはあるが、POST受信時(156行目)にCSRF検証をしていない -->
        <form id="searchForm" name="search" method="POST" action="index.php">
            <input type="text" id="searchInput" name="searchValue" placeholder="検索したいタイトル、メモ、タグ">
            <button class="searchBtn">絞り込み検索</button>
            <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">
        </form>

        <!-- [レビュー指摘:高] 絞り込み解除フォームにCSRFトークンがなく、CSRF検証もない -->
        <form id="all" name="all" method="POST" action="index.php">
            <button type="submit" class="release-btn" name="submitButton">絞り込み解除</button>
        </form>
        <p class="search-hint">部分一致に対応しています</p>
        <?php
        $getBookMarkLists =  $BookMarkManager->load_bookmarkLists();
        ?>
        <?php if (empty($getBookMarkLists)): ?>
            <!-- ブックマークが1つもない場合の表示 -->
            <div class="empty">まだブックマークがありません。上のフォームから追加してください。</div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['delete_message'])): ?>
            <div class="delete_message">
                <?= h($_SESSION['delete_message']) ?>
            </div>
            <?php unset($_SESSION['delete_message']); ?>
        <?php endif; ?>

        <?php
        $searchValue = '';
        $filteredValue = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchValue']) && $_POST['searchValue'] !== '')
        {
            $searchValue = $_POST['searchValue'];
            $filteredValue = $BookMarkManager->search_bookmarks($searchValue);
        }
        ?>

        <!-- ブックマークが存在する場合、テーブルで表示 -->
        <script src="./js/button_control.js"></script>
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
                // ========================================
                // 絞り込み検索
                // ========================================
                if (isset($_POST['submitButton'])) //jsonからブックマークリストを読み込む
                {
                    $arrayBookMarkList =  $BookMarkManager->load_bookmarkLists();
                }
                else if (!empty($filteredValue)) //絞り込み検索の結果が存在する場合
                {
                    $arrayBookMarkList = $filteredValue;
                }
                else if (!empty($searchValue) && empty($filteredValue)) //絞り込み検索の結果が存在しない場合
                {
                    echo '<div class="errorMessage">その検索ワードは存在しません</div>';
                    $arrayBookMarkList = [];
                }
                else
                {
                    $arrayBookMarkList = $getBookMarkLists; //ブックマークがひとつもない場合
                }
                ?>
                <?php
                foreach (array_reverse($arrayBookMarkList) as $b): ?>
                    <tr>
                        <td>
                            <div>
                                <button class="favoriteBtn" data-item-id="<?= h($b['id']) ?>" onclick="toggleFavorite(this)">
                                    <span class="icon">☆</span></button>
                            </div>
                        </td>
                        <td>
                            <div>
                                <a href="<?= h($b['url'] ?? '') ?>" target="_blank"> <?= h($b['title'] ?? '') ?></a>
                                <a href="<?= h($b['url'] ?? '') ?>" target="_blank" class="openNewTab" title="新しいタブで開く">↗️</a>
                            </div>
                        </td>
                        <td>
                            <div>
                                <a href="<?= h($b['url'] ?? '') ?>" target="_blank"><?= h($b['url'] ?? '') ?></a>
                                <a href="<?= h($b['url'] ?? '') ?>" target="_blank" class="openNewTab" title="新しいタブで開く">↗️</a>
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
                            <form action="./API/deleteBookMark.php" method="post">
                                <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">
                                <input type="hidden" name="deleteKey" value="<?= h($b['deleteKey']) ?>">
                                <button class="deleteBtn" name="action" value="delete" data-delete-item-key="<?= h($b['deleteKey']) ?>">削除</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                    <script>
                        if (typeof initializeFavoriteButtons === 'function') {
                            initializeFavoriteButtons();
                        }
                    </script>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- [レビュー指摘:高] $_SESSION['showModal'] は47行目で既にunset済みのため、この条件は常にfalseになり modal_close.js が読み込まれない -->
    <?php if (isset($_SESSION['showModal']) && $_SESSION['showModal'] === $BookMarkManager::URL_VALID): ?>
        <script src="./js/modal_close.js"></script>
    <?php endif; ?>
    <script src="./js/onload_favorite.js"></script>
    <script src="./js/toggle_favorite.js"></script>
    <script src="./js/modal_control.js"></script>
    <!-- 絞り込み検索後のスクロール -->
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchValue'])): ?>
        <script src="./js/filter_scroll.js"></script>
    <?php endif; ?>
    <!-- 絞り込み検索解除後のスクロール -->
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitButton'])): ?>
        <script src="./js/unfilter_scroll.js"></script>
    <?php endif; ?>
    <!-- 削除後のスクロール -->
    <?php if (!empty($_SESSION['delete_flg'])): ?>
        <script src="./js/scroll_after_deletion.js"></script>
        <?php unset($_SESSION['delete_flg']) ?>
    <?php endif; ?>

</body>

</html>