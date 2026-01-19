/**
 * クリアボタン
 */
function clearText() {
            let textForm = document.getElementById("inputForm");
            title.value = '';
            url.value = '';
            memo.value = '';
            tags.value = '';
        }

/**
 * フェイバリットボタンコントロール
 */
async function initializeFavoriteButtons() {
  try {
    const response = await fetch('data/bookmarks_file.json?t=' + Date.now());

    if (!response.ok) {
      throw new Error(`HTTPエラー! ステータス: ${response.status}`);
    }

    const data = await response.json();

    const favoriteBtns = document.querySelectorAll('.favorite-btn');

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
