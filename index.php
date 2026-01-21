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

<!DOCTYPE html>
<!-- [問題] lang属性がない（アクセシビリティ）- <html lang="ja"> にすべき -->
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
            <!-- [問題] CSSクラス名の不一致 - HTMLでは "successMessage" だが、CSSでは ".success-message" のためスタイルが適用されていない -->
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

        <input id="tags" type="text" name="tags" placeholder="タグ・カンマ区切り可(任意)" value="<?= h(!empty($_SESSION['detected_error_url']['user_entered_low_tags']) ? $_SESSION['detected_error_url']['user_entered_low_tags'] : '') ?>">

        <button type="submit">追加</button>
        <button type="button" onclick="clearText()">クリア</button>

        <?php unset($_SESSION['detected_error_url']); ?>
    </form>

    <div id='listTpl'>
        <!-- ============================================================================
         ブックマーク一覧の表示
        ============================================================================ -->
        <h2>ブックマーク一覧</h2>

        <!-- [問題] フォームのaction属性が空 - action="" は現在のページにPOSTするが、明示的にすべき -->
        <form id="searchForm" name="search" method="POST" action="">
            <input type="text" id="searchInput" name="searchValue" placeholder="検索したいタイトル、メモ、タグ">
            <button class="searchBtn">絞り込み検索</button>
            <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">
        </form>

        <!-- [問題] フォームのaction属性が空 - action="" は現在のページにPOSTするが、明示的にすべき -->
        <form id="all" name="all" method="POST" action="">
            <button type="submit" class="release-btn" name="submitButton">絞り込み解除</button>
        </form>
        <p class="search-hint">部分一致に対応しています</p>
        <?php
        $getBookMarkLists =  $BookMarkManager->load_bookmarkLists();

        // [問題] 使用されていないコード / バグの可能性
        // - $_POST['tags']が存在する場合、タグ配列でブックマークリストを上書きしている
        // - この処理の意図が不明。おそらくバグ
        if (!empty($_POST['tags']))
        {
            $splitTags = $Helper->splitTags($_POST['tags']);
            $getBookMarkLists = $splitTags;
        }
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
                    // [問題] CSSクラス名の不一致 - HTMLでは "errorMessage" だが、CSSでは ".error-message" のためスタイルが適用されていない
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
                                <button class="favorite-btn" data-item-id="<?= h($b['id']) ?>" onclick="toggleFavorite(this)">
                                    <span class="icon">☆</span></button>
                            </div>
                        </td>
                        <td>
                            <div>
                                <a href="<?= h($b['url'] ?? '') ?>" target="_blank"> <?= h($b['title'] ?? '') ?></a>
                                <!-- [問題] この隠しinputは使用されていない - 削除を検討 -->
                                <input type="hidden" name="title" value="<?= h($b['title']) ?>">
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
                            <form action="./API/deleteBookMark.php" method="post">
                                <input type="hidden" name="csrf_token" value="<?= h($_SESSION['csrf_token']) ?>">
                                <input type="hidden" name="deleteKey" value="<?= h($b['deleteKey']) ?>">
                                <button class="delete-btn" name="action" value="delete" data-delete-item-key="<?= h($b['deleteKey']) ?>">削除</button>
                            </form>
                        </td>
                    </tr>
                    <!-- [問題] ループ内でのスクリプト読み込み（パフォーマンス問題）
                         - foreachループ内で毎回 innerloop_favorite.js を読み込んでいる
                         - ブックマークが100件あれば100回読み込まれる
                         - ループ外に移動すべき -->
                    <!-- 問題を理解しました。現在214行目のinnerloop_favorite.jsはループ内で毎回読み込まれており、パフォーマンス上の問題があります。

                    代替案
                    案1: MutationObserverを使用（推奨）
                    ループ外で一度だけスクリプトを読み込み、DOMの変更を監視して自動的にボタンを初期化します。


                    // onload_favorite.js を以下のように修正
                    document.addEventListener('DOMContentLoaded', function() {
                    initializeFavoriteButtons();

                    // 絞り込みでDOMが変更された時も再初期化
                    const observer = new MutationObserver(function(mutations) {
                    initializeFavoriteButtons();
                    });

                    const tbody = document.querySelector('#listTpl tbody');
                    if (tbody) {
                    observer.observe(tbody, { childList: true, subtree: true });
                    }
                    });
                    案2: カスタムイベントを使用
                    絞り込み処理の後にカスタムイベントを発火させ、それをトリガーに初期化を実行します。


                    // onload_favorite.js を以下のように修正
                    document.addEventListener('DOMContentLoaded', initializeFavoriteButtons);
                    document.addEventListener('filterApplied', initializeFavoriteButtons);
                    絞り込み処理側で document.dispatchEvent(new Event('filterApplied')) を呼び出します。

                    案3: 絞り込み後に直接呼び出し（最もシンプル）
                    PHPで絞り込みが行われた場合のみ、ループ外で一度だけ初期化を呼び出します。 -->


                    <!-- 214行目を削除し、ループ外に以下を追加 -->
                    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                        <script>
                            if (typeof initializeFavoriteButtons === 'function') {
                                initializeFavoriteButtons();
                            }
                        </script>
                    <?php endif; ?>
                    <!-- **案1（MutationObserver）**が最も堅牢で、今後の拡張にも対応しやすいです。どの案で進めますか？ -->
                    <!-- 絞り込み、絞り込み解除時のお気に入りボタンの状態保持 -->
                    <script src="./js/innerloop_favorite.js"></script>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="./js/onload_favorite.js"></script>
    <script src="./js/toggle_favorite.js"></script>
    <!-- 絞り込み検索後のスクロール -->
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchValue'])): ?>
        <script>
            document.querySelector('#listTpl').scrollIntoView({
                behavior: 'auto'
            });
        </script>
    <?php endif; ?>
    <!-- 絞り込み検索解除後のスクロール -->
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submitButton'])): ?>
        <script>
            document.querySelector('#listTpl').scrollIntoView({
                behavior: 'auto'
            });
        </script>
    <?php endif; ?>
    <!-- 削除後のスクロール -->
    <?php if (!empty($_SESSION['delete_flg'])): ?>
        <script>
            document.querySelector('.delete_message').scrollIntoView({
                behavior: 'auto'
            });
        </script>
        <?php unset($_SESSION['delete_flg']) ?>
    <?php endif; ?>
</body>

</html>