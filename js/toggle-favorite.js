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
    console.log(data.id);
    console.log(data.favorite);
    if (data.id === itemId && data.favorite === true) 
    {
        button.classList.add('is-favorited');
    } 
    else if (data.id === itemId && data.favorite === false)
    {
      button.classList.remove('is-favorited');
    } 


  } catch (error) {
    console.error('❌ エラー発生:', error);
  }
}