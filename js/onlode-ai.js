async () =>  {
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

      // この各ボタンに対応するデータを探す
      data.forEach(value => {
        if (value.id === itemId) {
          if (value.favorite === false) {
            // お気に入りでない場合、is-favoritedクラスを削除
            if (favoriteBtn.classList.contains('is-favorited')) {
              favoriteBtn.classList.remove('is-favorited');
            }
          } else if (value.favorite === true) {
            // お気に入りの場合、is-favoritedクラスを追加
            if (!favoriteBtn.classList.contains('is-favorited')) {
              favoriteBtn.classList.add('is-favorited');
            }
          }
        }
      });
    });
    } catch (error) {
      // エラーハンドリング
      console.error('データの取得中にエラーが発生しました:', error);
      return null; // または適切なエラー値を返す
    }

  });