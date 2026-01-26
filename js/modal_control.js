const btn = document.getElementById('rotateBtn');
const modal = document.getElementById('myModal');
const closeBtn = document.getElementById('closeBtn');

btn.addEventListener('click', () => {
  // ボタンを回転させる
  btn.classList.toggle('is-rotated');
  
  // モーダルを表示
  if (btn.classList.contains('is-rotated')) {
    modal.showModal(); // ネイティブのモーダル表示機能
  }
});

closeBtn.addEventListener('click', () => {
  modal.close();
  btn.classList.remove('is-rotated'); // 閉じた時にボタンを戻す
});

// モーダルの背景をクリックしたら閉じる処理
modal.addEventListener('click', (e) => {
  if (e.target === modal) {
    modal.close();
    btn.classList.remove('is-rotated');
  }
});