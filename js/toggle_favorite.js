/**
 * お気に入りボタン点滅ロジック
 */
async function toggleFavorite(button) {
    const itemId = button.getAttribute('data-item-id');
    const icon = button.querySelector('.icon');

    const requestBody = { id: itemId };
try {
    const response = await fetch('API/toggleFavorite.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(requestBody),
    });

    const data = await response.json();

const item = data.find(value => value.id === itemId);
// [問題] try-catchの位置が不適切
// - tryがfetch後の処理途中から始まっており、エラーハンドリングが不完全
// - fetchを含む全体を囲むべき

    if (item) {

    // [問題] デバッグ用console.logが残存 - 本番コードには不要、削除すべき


    // お気に入り状態を切り替え
    if (item.favorite === true)
    {
      button.classList.add('is-favorited');
      icon.textContent = '★';

    } 
    else
    {
      button.classList.remove('is-favorited');
      icon.textContent = '☆';
    }
  }
  } catch (error) {
    console.error('❌ エラー発生:', error);
    button.disabled = false;
  }
}
// [問題] コメントアウトされたコード - 不要なら削除すべき
