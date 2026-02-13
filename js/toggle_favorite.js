/**
 * お気に入りボタン点滅ロジック
 */
async function toggleFavorite(button) {
    const itemId = button.getAttribute('data-item-id');
    const icon = button.querySelector('.icon');
    const csrfToken = document.querySelector('input[name="csrf_token"]').value;

    const requestBody = { id: itemId, csrf_token: csrfToken };

    button.disabled = true;

try {
    // [レビュー指摘:中] 相対パスのため、index.php以外から呼ぶ場合に壊れる　→ 修正済み
    const response = await fetch('API/toggleFavorite.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(requestBody),
    });

    const data = await response.json();

    const item = data.find(value => value.id === itemId);

    if (item) {

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
     // [レビュー指摘:中] disabledに設定する処理がないため、この復元は意味がない。連打防止が不完全 → 修正済み
  } finally {
    button.disabled = false;
  }
}
