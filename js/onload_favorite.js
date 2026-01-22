/**
 * 画面表示時のお気に入りボタンのコントロール
 *
 * DOMContentLoadedが既に発火済みの場合も考慮
 */
if (document.readyState === 'loading') {
    // DOMがまだ読み込み中の場合、イベントを待つ
    document.addEventListener('DOMContentLoaded', initializeFavoriteButtons);
} else {
    // DOMが既に読み込み完了している場合、即座に実行
    initializeFavoriteButtons();
}