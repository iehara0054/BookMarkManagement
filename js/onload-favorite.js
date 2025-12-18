document.addEventListener('DOMContentLoaded', async () => {
  try {
    const itemId = button.getAttribute('data-item-id');

    const requestBody = { id: itemId };
// 1. 指定したURLからデータを取得
     const response = await fetch('API/Favorite.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(requestBody),
    });
    
    // 2. 取得したレスポンスをJSONとして解析
    const data = await response.json();
    // 3. 取得したデータの使用
    console.log(data);
  } catch (error) {
    console.error('データの取得に失敗しました:', error);
  }
});

