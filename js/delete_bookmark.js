const favoriteBtns = button.querySelector('.favorite-btn');
const deleteBtns = button.querySelector('.delete-btn');

async function toggleFavorite(favoriteBtns, deleteBtns) {
  try {
    const itemId = favoriteBtns.getAttribute('data-item-id');

    const requestBody = { id: itemId };

    const response = await fetch('API/deleteBookMark.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(requestBody),
    });

    const data = await response.json();
//ここから処理を書きます






      } catch (error) {
    console.error('❌ エラー発生:', error);
    deleteBtns.disabled = false;
  }
}