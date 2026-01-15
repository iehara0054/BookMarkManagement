document.addEventListener('DOMContentLoaded', async () =>  {
  try {
    const response = await fetch('data/bookmarks_file.json');

    if (!response.ok) {
      // レスポンスがOKでない場合、エラーを投げる
      throw new Error(`HTTPエラー! ステータス: ${response.status}`);
    }

    const data = await response.json();

    const favoriteBtns = document.querySelectorAll('.favorite-btn');
    favoriteBtns.forEach(favoriteBtn => {
      const itemId = favoriteBtn.dataset.itemId;
    
        data.forEach(value => {
          const icon = favoriteBtn.querySelector('.icon');
          if ((value.id === itemId) && (value.favorite === false))
          {
            if(favoriteBtn.classList.contains('is-favorited'))
            {
              favoriteBtn.classList.remove('is-favorited');
            }
            if (icon) icon.textContent = '☆';
          } else if ((value.id === itemId) && (value.favorite === true))
          {
            if(!favoriteBtn.classList.contains('is-favorited'))
            {
              favoriteBtn.classList.add('is-favorited');
            }
            if (icon) icon.textContent = '★';
          }
        });
        });
      } catch (error) {
        console.error('データの取得中にエラーが発生しました:', error);
        return null;
      }
});
 

