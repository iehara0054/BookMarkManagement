/**
 * お気に入りボタン点滅ロジック
 */
async function toggleFavorite(button) {
    const itemId = button.getAttribute('data-item-id');
    const icon = button.querySelector('.icon');

    const requestBody = { id: itemId };

    const response = await fetch('API/toggleFavorite.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(requestBody),
    });

    const data = await response.json();

const item = data.find(value => value.id === itemId);
// [問題] try-catchの位置が不適切
// - tryがfetch後の処理途中から始まっており、エラーハンドリングが不完全
// - fetchを含む全体を囲むべき
try {
    if (item) {

    // [問題] デバッグ用console.logが残存 - 本番コードには不要、削除すべき
    console.log('お気に入り状態:', item.favorite);
    console.log(itemId);

    // お気に入り状態を切り替え
    if (item.favorite === true)
    {
      button.classList.add('is-favorited');
      icon.textContent = '★';

    } 
    else
    {
      button.classList.remove('is-favorited');
      icon.textContent = '☆';
    }
  }
  } catch (error) {
    console.error('❌ エラー発生:', error);
    button.disabled = false;
  }
}
// [問題] コメントアウトされたコード - 不要なら削除すべき
// AIでCSSを当てた時に書かれました
// スパークルエフェクト関数
// function createSparkles(button) {
//   const sparkleCount = 6;
//   const rect = button.getBoundingClientRect();

//   for (let i = 0; i < sparkleCount; i++) {
//     const sparkle = document.createElement('div');
//     sparkle.innerHTML = '✨';
//     sparkle.style.position = 'fixed';
//     sparkle.style.left = rect.left + rect.width / 2 + 'px';
//     sparkle.style.top = rect.top + rect.height / 2 + 'px';
//     sparkle.style.pointerEvents = 'none';
//     sparkle.style.fontSize = '20px';
//     sparkle.style.zIndex = '9999';
//     sparkle.style.transition = 'all 0.8s ease-out';

//     document.body.appendChild(sparkle);

//     // ランダムな方向に飛び散るアニメーション
//     setTimeout(() => {
//       const angle = (360 / sparkleCount) * i;
//       const rad = angle * (Math.PI / 180);
//       const distance = 50;
//       const x = Math.cos(rad) * distance;
//       const y = Math.sin(rad) * distance;

//       sparkle.style.transform = `translate(${x}px, ${y}px)`;
//       sparkle.style.opacity = '0';
//     }, 10);

//     // アニメーション後に削除
//     setTimeout(() => {
//       sparkle.remove();
//     }, 900);
//   }
// }}