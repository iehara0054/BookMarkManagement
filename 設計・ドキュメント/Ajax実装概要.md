# Ajax実装概要 - お気に入りボタン点滅機能

純粋なJavaScriptとJSONだけで作成するための基本情報

---

## 必要な技術要素

### 1. Fetch API

モダンなブラウザで標準的なAjax通信の方法です。

```javascript
fetch(url, options)
  .then(response => response.json())
  .then(data => {
    // データ処理
  })
  .catch(error => {
    // エラー処理
  });
```

---

### 2. async/await（推奨）

Fetch APIをより読みやすく書けます。

```javascript
async function toggleFavorite(itemId) {
  try {
    const response = await fetch('API/toggleFavorite.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ id: itemId })
    });

    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Error:', error);
  }
}
```

---

### 3. 主要なFetchオプション

- **method**: `'GET'`, `'POST'`, `'PUT'`, `'DELETE'`など
- **headers**: リクエストヘッダー
  - `'Content-Type': 'application/json'` - JSON送信時
  - `'Content-Type': 'application/x-www-form-urlencoded'` - フォームデータ送信時
- **body**: 送信データ（GETでは使用不可）
  - JSON: `JSON.stringify({key: value})`
  - FormData: `new FormData(formElement)`
- **credentials**: `'include'` - Cookie/セッション送信時

---

### 4. レスポンス処理

```javascript
const response = await fetch(url);

// ステータス確認
if (!response.ok) {
  throw new Error(`HTTP error! status: ${response.status}`);
}

// JSONパース
const data = await response.json();

// テキスト取得
const text = await response.text();
```

---

### 5. PHP側のJSON出力

```php
<?php
header('Content-Type: application/json; charset=utf-8');

$result = [
    'success' => true,
    'message' => 'ブックマークを更新しました',
    'bookmarked' => true
];

echo json_encode($result);
exit;
?>
```

---

### 6. エラーハンドリング

```javascript
try {
  const response = await fetch(url, options);

  if (!response.ok) {
    throw new Error(`サーバーエラー: ${response.status}`);
  }

  const data = await response.json();

} catch (error) {
  if (error instanceof TypeError) {
    // ネットワークエラー
    console.error('通信エラー:', error);
  } else {
    // その他のエラー
    console.error('エラー:', error);
  }
}
```

---

## ボタン点滅の実装パターン

### CSSアニメーション

```css
@keyframes blink {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.3; }
}

.bookmarking {
  animation: blink 0.5s ease-in-out infinite;
}
```

### JavaScriptでの制御

```javascript
// 点滅開始
button.classList.add('bookmarking');

// Ajax通信
await toggleFavorite(id);

// 点滅終了
button.classList.remove('bookmarking');
```

---

## ブラウザ対応

- **Fetch API**: IE以外のモダンブラウザ全て対応
- **async/await**: IE以外のモダンブラウザ全て対応
- **IEサポート必要な場合**: polyfillまたはXMLHttpRequest使用

---

## セキュリティ考慮点

1. **CSRF対策**: トークン検証
2. **XSS対策**: JSON出力時のエスケープ
3. **認証確認**: セッションチェック
4. **入力検証**: サーバー側で必ず実施

---

作成日: 2025-12-10
