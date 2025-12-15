async function toggleFavorite(button) {
  try {
    const itemId = button.getAttribute('data-item-id');

    const requestBody = { id: itemId };

    const response = await fetch('API/toggleFavorite.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(requestBody),
    });

    const data = await response.json();
    const favoriteBtn =  document.getElementById('favorite-button');
      favoriteBtn.addEventListener('click', function() {
        if ((data.favorite == true) && (favoriteBtn.getAttribute('data-item-id') == data.id)) {
          console.log('OK');
        }
      });

  } catch (error) {
    console.error('❌ エラー発生:', error);
  }
}