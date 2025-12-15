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
    // const obj = Object.entries(data);
    //   console.log(obj['key']);
    const obj = JSON.parse(data);
    data.forEach((value) => {
      // const obj = JSON.parse(value);
      // const obj = Object.entries(value);
      console.log(obj['id']);
    });

  } catch (error) {
    console.error('❌ エラー発生:', error);
  }
}