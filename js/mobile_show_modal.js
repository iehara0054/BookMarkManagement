// セッションフラグがあればモーダルを開く（モバイルのみ）
document.addEventListener('DOMContentLoaded', function() {
    // モバイル（768px以下）の場合のみモーダルを開く
    if (window.matchMedia('(max-width: 768px)').matches) {
        const modal = document.getElementById('myModal');
         modal.showModal();
    }
  });