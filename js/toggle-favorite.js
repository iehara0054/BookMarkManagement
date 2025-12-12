async function toggleFavorite(button) {
  try {
    const itemId = button.getAttribute('data-item-id');

    const response = await fetch('API/toggleFavorite.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ id: itemId })
    });

    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Error:', error);
  }
}