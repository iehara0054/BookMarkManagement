document.addEventListener('DOMContentLoaded', async () => {
  // window.addEventListener("load", async function() {
  try {
// 1. 指定したURLからデータを取得
     const requestBody = { key: "value" };
     const response = await fetch('json/bookmarks_file.json', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(requestBody),
    });

    // 2. 取得したレスポンスをJSONとして解析
    const data = await response.json();
    
      if (data.favorite === false)
      {
        favoriteBtn.querySelectorAll('.is-favorited');
        favoriteBtn.classList.remove('.is-favorited');
      }

    data.forEach(value => {

      console.log(data.favorite);

    const favoriteBtn = document.querySelector('.favorite-btn');
    const itemId = favoriteBtn.getAttribute('data-item-id');
    const favoriteClass = favoriteBtn.classList.contains('.is-favorited');

      // console.log(favoriteClass);
    if (favoriteClass ?? '')
    {
      if ((itemId === value.id) && (favoriteClass === true))
      {
        favoriteBtn.classList.add('is-favorited');
      } else if ((itemId === value.id) && (favoriteClass === false))
      {
        favoriteBtn.classList.remove('.is-favorited');
      }
    }
    });

    // 3. 取得したデータの使用
    // console.log(data);
  } catch (error) {
    console.error('データの取得に失敗しました:', error);
  }
});

