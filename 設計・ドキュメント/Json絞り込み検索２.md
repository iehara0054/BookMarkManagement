PHPとJavaScriptのFetch APIを組み合わせて、JSONデータの非同期絞り込み検索を実装できます。クライアントサイド (JavaScript) から検索条件をサーバーサイド (PHP) に送信し、PHPでデータをフィルタリングしてJSON形式で返すという流れになります。 
実装手順
1. HTMLファイル (index.html)
検索フォームと結果表示エリアを設置します。
html
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>PHP Ajax 絞り込み検索 (Fetch API)</title>
</head>
<body>
    <h1>商品検索</h1>
    <input type="text" id="searchInput" placeholder="商品名を入力">
    <button onclick="searchData()">検索</button>
    <ul id="results"></ul>

    <script src="script.js"></script>
</body>
</html>
2. JavaScriptファイル (script.js)
Fetch APIを使用して非同期通信を行い、結果をDOMに表示します。 
javascript
function searchData() {
    const searchTerm = document.getElementById('searchInput').value;
    const resultsContainer = document.getElementById('results');
    resultsContainer.innerHTML = ''; // 結果をクリア

    // 検索条件をPOSTリクエストで送信
    fetch('search.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `searchTerm=${encodeURIComponent(searchTerm)}`
    })
    .then(response => response.json()) // JSON形式のレスポンスをパース
    .then(data => {
        // 絞り込まれたデータを表示
        if (data.length > 0) {
            data.forEach(item => {
                const li = document.createElement('li');
                li.textContent = `${item.name} - ${item.price}円`;
                resultsContainer.appendChild(li);
            });
        } else {
            resultsContainer.innerHTML = '<li>一致する商品が見つかりませんでした。</li>';
        }
    })
    .catch(error => {
        console.error('通信エラー:', error);
    });
}
3. PHPファイル (search.php)
クライアントから送られた検索条件を受け取り、データを絞り込んでJSON形式で出力します。 
php
<?php
// ヘッダーを設定し、JSONレスポンスであることを明示
header('Content-Type: application/json');

// 元となるデータ（データベースからの取得を想定）
$products = [
    ['id' => 1, 'name' => 'りんご', 'price' => 100],
    ['id' => 2, 'name' => 'みかん', 'price' => 80],
    ['id' => 3, 'name' => 'バナナ', 'price' => 120],
    ['id' => 4, 'name' => 'メロン', 'price' => 500],
    ['id' => 5, 'name' => 'ぶどう', 'price' => 200]
];

$filteredProducts = [];
$searchTerm = $_POST['searchTerm'] ?? ''; // POSTデータから検索条件を取得

if ($searchTerm) {
    // データを絞り込み
    foreach ($products as $product) {
        // 名前に検索キーワードが含まれているかチェック
        if (stripos($product['name'], $searchTerm) !== false) {
            $filteredProducts[] = $product;
        }
    }
} else {
    // 検索条件がない場合は全件返す、あるいは空にするなど要件に応じて
    $filteredProducts = $products;
}

// 結果をJSON形式で出力
echo json_encode($filteredProducts);
exit;
?>
仕組みの解説
ユーザーが index.html の入力フォームにキーワードを入力し、「検索」ボタンをクリックします。
script.js の searchData() 関数が実行され、Fetch APIを使ってPHPファイル (search.php) に検索キーワードをPOSTメソッドで送信します。
search.php はPOSTデータを受け取り、持っている商品データ（この例ではPHP配列）を基に絞り込み処理を行います。
絞り込んだ結果を json_encode() 関数でJSON形式の文字列に変換し、レスポンスとして返します。
script.js はJSONレスポンスを受け取り、response.json() でJavaScriptのオブジェクトに変換します。
変換されたデータを使ってDOM操作を行い、リアルタイムに検索結果を表示します。 
【Javascript】Ajaxを使ってデータをやりとりする方法を紹介
2020/10/05 — ... ; $data = json_encode($data); echo $data; };. サーバーサイドでは、...

logsuke

フェッチ API の使用 - MDN Web Docs
2025/09/10 — Response インターフェイスには、本体のコンテンツ全体を様々な形式で取得するためのメソッドがあります。 * Res...

MDN Web Docs
【PHP】Ajaxを使ってJSON形式で配列データを取得し - Qiita
2024/12/22 — 【PHP】Ajaxを使ってJSON形式で配列データを取得し、Webページ上に表示させる最低限の方法 #Database ...

Qiita

すべて表示
