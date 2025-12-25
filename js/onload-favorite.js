document.addEventListener('DOMContentLoaded', async () =>  {
  try {
    const response = await fetch('json/bookmarks_file.json');

    if (!response.ok) {
      // レスポンスがOKでない場合、エラーを投げる
      throw new Error(`HTTPエラー! ステータス: ${response.status}`);
    }

    const data = await response.json();

    const favoriteBtns = document.querySelectorAll('.favorite-btn');
    favoriteBtns.forEach(favoriteBtn => {
      const itemId = favoriteBtn.dataset.itemId;
    });

    data.forEach(value => {
    if ((value.id === itemId) && (value.favorite === false))
      { 
        const classBtn = favoriteBtn.querySelector('.favorite-btn');
        if(classBtn.classList.contains('is-favorited'))
        {
           classBtn.classList.remove('.is-favorited');
        } 
      } else if ((value.id === itemId) && (value.favorite === true))
      {
        const classBtn = favoriteBtn.querySelector('.favorite-btn');
        if(!classBtn.classList.contains('is-favorited'))
        {
           classBtn.classList.remove('.is-favorited');
        }
      }
    });
    } catch (error) {
      // エラーハンドリング
      console.error('データの取得中にエラーが発生しました:', error);
      return null; // または適切なエラー値を返す
    }

  });
      // if (data.favorite === false)
      // {
      //   favoriteBtn.querySelectorAll('.is-favorited');
      //   favoriteBtn.classList.remove('.is-favorited');
      // }

 

    // const favoriteBtn = document.querySelector('.favorite-btn');
    // const itemId = favoriteBtn.getAttribute('data-item-id');
    // const favoriteClass = favoriteBtn.classList.contains('.is-favorited');

      // console.log(favoriteClass);
    // if (favoriteClass ?? '')
    // {
    //   if ((itemId === value.id) && (favoriteClass === true))
    //   {
    //     favoriteBtn.classList.add('is-favorited');
    //   } else if ((itemId === value.id) && (favoriteClass === false))
    //   {
    //     favoriteBtn.classList.remove('.is-favorited');
 

