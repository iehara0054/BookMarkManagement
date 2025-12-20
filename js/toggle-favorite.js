async function toggleFavorite(button) {
  try {
    const itemId = button.getAttribute('data-item-id');
    const icon = button.querySelector('.icon');

    // AIでCSSを当てた時に勝手に書かれました
    // ボタンを一時的に無効化（連続クリック防止）
    button.disabled = true;
    // AIでCSSを当てた時に勝手に書かれました
    // クリック時の即座のフィードバック
    button.style.transform = 'scale(0.95)';
    setTimeout(() => {
      button.style.transform = '';
    }, 150);

    const requestBody = { id: itemId };

    const response = await fetch('API/toggleFavorite.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(requestBody),
    });

    const data = await response.json();
    console.log('お気に入り状態:', data.favorite);

    // お気に入り状態を切り替え
    if (data.favorite === true) {
      button.classList.add('is-favorited');
      icon.textContent = '★';

      // パーティクルエフェクトを追加
      createSparkles(button);
    } else {
      button.classList.remove('is-favorited');
      icon.textContent = '☆';
    }

    // ボタンを再度有効化
    button.disabled = false;

  } catch (error) {
    console.error('❌ エラー発生:', error);
    button.disabled = false;
  }
}
// AIでCSSを当てた時に勝手に書かれました
// スパークルエフェクト関数
function createSparkles(button) {
  const sparkleCount = 6;
  const rect = button.getBoundingClientRect();

  for (let i = 0; i < sparkleCount; i++) {
    const sparkle = document.createElement('div');
    sparkle.innerHTML = '✨';
    sparkle.style.position = 'fixed';
    sparkle.style.left = rect.left + rect.width / 2 + 'px';
    sparkle.style.top = rect.top + rect.height / 2 + 'px';
    sparkle.style.pointerEvents = 'none';
    sparkle.style.fontSize = '20px';
    sparkle.style.zIndex = '9999';
    sparkle.style.transition = 'all 0.8s ease-out';

    document.body.appendChild(sparkle);

    // ランダムな方向に飛び散るアニメーション
    setTimeout(() => {
      const angle = (360 / sparkleCount) * i;
      const rad = angle * (Math.PI / 180);
      const distance = 50;
      const x = Math.cos(rad) * distance;
      const y = Math.sin(rad) * distance;

      sparkle.style.transform = `translate(${x}px, ${y}px)`;
      sparkle.style.opacity = '0';
    }, 10);

    // アニメーション後に削除
    setTimeout(() => {
      sparkle.remove();
    }, 900);
  }
}