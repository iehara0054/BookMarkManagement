/**
 * クリアボタン
 */
// [レビュー指摘:低] モバイル時にデスクトップ側の要素(#title等)が存在しない場合、nullに対する.valueアクセスでエラーになりうる
function clearText() {
            document.getElementById('title').value = '';
            document.getElementById('url').value = '';
            document.getElementById('memo').value = '';
            document.getElementById('tags').value = '';

            document.getElementById('titleModal').value = '';
            document.getElementById('urlModal').value = '';
            document.getElementById('memoModal').value = '';
            document.getElementById('tagsModal').value = '';
        }

/**
 * フェイバリットボタンコントロール
 */
async function initializeFavoriteButtons() {
  try {
    // [レビュー指摘:高] JSONファイルを直接公開している。deleteKeyなどの秘密情報がクライアントに漏洩する
    // [レビュー指摘:中] 相対パスのため、index.php以外から呼ぶ場合に壊れる
    const response = await fetch('data/bookmarks_file.json?t=' + Date.now());

    if (!response.ok) {
      throw new Error(`HTTPエラー! ステータス: ${response.status}`);
    }

    const data = await response.json();

    const favoriteBtns = document.querySelectorAll('.favoriteBtn');

    favoriteBtns.forEach(favoriteBtn => {

      const itemId = favoriteBtn.dataset.itemId;
      const icon = favoriteBtn.querySelector('.icon');
      const bookmark = data.find(value => value.id === itemId);

      if (!bookmark) return;

      if (bookmark.favorite === true) {
        favoriteBtn.classList.add('is-favorited');
        if (icon) icon.textContent = '★';
      } else {
        favoriteBtn.classList.remove('is-favorited');
        if (icon) icon.textContent = '☆';
      }
    });
  } catch (error) {
    console.error('データの取得中にエラーが発生しました:', error);
    return null;
  }
};