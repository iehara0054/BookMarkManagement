// 1. fetch API (推奨: モダンな方法)
// ブラウザの標準機能で、Promiseベースの非同期通信を実現します。 
// javascript
const url = 'https://api.example.com/data'; // 取得したいJSONのURL

fetch(url)
  .then(response => {
    if (!response.ok) { // 応答が成功したかチェック
      throw new Error('Network response was not ok');
    }
    return response.json(); // レスポンスをJSONとして解析
  })
  .then(data => {
    // JSONデータが 'data' 変数に格納される
    console.log(data);
    console.log(data.name); // 例: { "name": "太郎" } のようなデータの場合
  })
  .catch(error => {
    console.error('Fetch error:', error);
  });
13:31
