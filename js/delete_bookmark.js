 async function toggleFavorite(button) {
  try {
    const itemId = button.getAttribute('data-item-id');
    const icon = button.querySelector('.icon');

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
    button.disabled = false;
  }
}