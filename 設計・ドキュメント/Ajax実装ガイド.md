# Ajax実装ガイド - お気に入りボタン点滅機能

## 必要な技術要素の1行ずつ解説

### 1. Fetch API - 基本構文

```javascript
fetch(url, options)
```
- `fetch()`: ブラウザ組み込みの非同期HTTP通信関数です
- `url`: リクエスト先のURL（文字列）を指定します
- `options`: リクエストの詳細設定（オブジェクト）を指定します（省略可）

```javascript
  .then(response => response.json())
```
- `.then()`: Promiseが成功した時に実行される処理を定義します
- `response`: サーバーからのレスポンスオブジェクトです
- `response.json()`: レスポンスのボディをJSON形式として解析します（これも非同期処理でPromiseを返します）

```javascript
  .then(data => {
```
- 2つ目の`.then()`: 1つ目の`.then()`で返されたPromise（JSONパース結果）を受け取ります
- `data`: パースされたJSONデータ（JavaScriptのオブジェクトや配列）です

```javascript
    // データ処理
```
- ここでサーバーから受け取ったデータを使った処理を記述します

```javascript
  })
  .catch(error => {
```
- `.catch()`: Promise処理中にエラーが発生した時に実行されます
- `error`: エラーオブジェクトが渡されます

```javascript
    // エラー処理
  });
```
- エラー時の処理（エラーメッセージ表示など）を記述します

---

### 2. async/await構文

```javascript
async function toggleFavorite(itemId) {
```
- `async`: この関数が非同期処理を含むことを宣言します（必ずPromiseを返します）
- `function toggleFavorite`: 関数名の定義です
- `itemId`: 引数（ブックマーク対象のID）です

```javascript
  try {
```
- `try`: エラーが発生する可能性のあるコードブロックを囲みます

```javascript
    const response = await fetch('API/toggleFavorite.php', {
```
- `const response`: レスポンスを格納する定数です
- `await`: Promiseの処理完了を待ちます（この行で処理が一時停止します）
- `'API/toggleFavorite.php'`: リクエスト先のURLです

```javascript
      method: 'POST',
```
- `method`: HTTPメソッドを指定します（POST = データ送信）

```javascript
      headers: {
```
- `headers`: HTTPリクエストヘッダーを設定するオブジェクトです

```javascript
        'Content-Type': 'application/json'
```
- `Content-Type`: 送信データの形式をサーバーに伝えます（JSON形式を指定）

```javascript
      },
      body: JSON.stringify({ id: itemId })
```
- `body`: 送信するデータ本体です
- `JSON.stringify()`: JavaScriptオブジェクトをJSON文字列に変換します
- `{ id: itemId }`: 送信するオブジェクト（idプロパティにitemIdの値を設定）

```javascript
    });
```
- fetchのオプションオブジェクトの終了です

```javascript
    const data = await response.json();
```
- `const data`: パース後のJSONデータを格納する定数です
- `await response.json()`: レスポンスのJSON解析完了を待ちます

```javascript
    return data;
```
- `return`: 関数の呼び出し元にデータを返します（async関数なのでPromiseでラップされます）

```javascript
  } catch (error) {
```
- `catch`: tryブロック内でエラーが発生した場合に実行されます
- `error`: キャッチされたエラーオブジェクトです

```javascript
    console.error('Error:', error);
```
- `console.error()`: ブラウザのコンソールにエラーを出力します

```javascript
  }
}
```
- catch文とfunction定義の終了です

---

### 3. 主要なFetchオプション

```javascript
method: 'POST'
```
- `method`: HTTPリクエストの種類を指定します
- `'POST'`: データをサーバーに送信する際に使用します（他に`'GET'`（取得）、`'PUT'`（更新）、`'DELETE'`（削除）などがあります）

```javascript
headers: { 'Content-Type': 'application/json' }
```
- `headers`: サーバーに送る追加情報（メタデータ）を設定します
- `'Content-Type'`: 送信データの形式を指定するヘッダーです
- `'application/json'`: JSON形式でデータを送ることをサーバーに伝えます

```javascript
headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
```
- `'application/x-www-form-urlencoded'`: HTML formの通常送信と同じ形式です（`key1=value1&key2=value2`形式）

```javascript
body: JSON.stringify({key: value})
```
- `body`: 実際に送信するデータです
- `JSON.stringify()`: JavaScriptオブジェクトを文字列化します（例: `{"key":"value"}`）
- GETリクエストではbodyは使用できません（URLパラメータを使います）

```javascript
body: new FormData(formElement)
```
- `new FormData()`: HTML form要素からFormDataオブジェクトを作成します
- `formElement`: `<form>`要素のDOM参照です
- ファイルアップロード時などに使用します

```javascript
credentials: 'include'
```
- `credentials`: Cookie送信の挙動を制御します
- `'include'`: クロスオリジンでもCookieを送信します（セッション維持に必要）
- デフォルトは`'same-origin'`（同一オリジンのみCookie送信）です

---

### 4. レスポンス処理

```javascript
const response = await fetch(url);
```
- `response`: サーバーからの応答全体を表すオブジェクトです
- まだデータ本体（body）は読み込まれていません（ヘッダー情報のみ取得済み）

```javascript
if (!response.ok) {
```
- `response.ok`: HTTPステータスが200-299の範囲ならtrue、それ以外はfalseです
- `!`: 論理否定演算子（falseの場合にif文が実行されます）

```javascript
  throw new Error(`HTTP error! status: ${response.status}`);
```
- `throw`: 例外（エラー）を発生させます
- `new Error()`: 新しいエラーオブジェクトを作成します
- `` `文字列` ``: テンプレートリテラル（変数埋め込み可能な文字列）です
- `${response.status}`: 変数の値を文字列に埋め込みます（例: 404, 500など）

```javascript
}
```
- if文の終了です

```javascript
const data = await response.json();
```
- `response.json()`: レスポンスボディをJSONとして解析します
- サーバーが`{"success": true}`を返した場合、JavaScriptオブジェクトに変換されます

```javascript
const text = await response.text();
```
- `response.text()`: レスポンスボディを文字列として取得します
- HTML、プレーンテキストなどJSON以外のレスポンス用です

---

### 5. PHP側のJSON出力

```php
<?php
```
- PHPコードの開始タグです

```php
header('Content-Type: application/json; charset=utf-8');
```
- `header()`: HTTPレスポンスヘッダーを設定する関数です
- `Content-Type: application/json`: クライアントにJSON形式で返すことを伝えます
- `charset=utf-8`: 文字エンコーディングをUTF-8に指定します（日本語対応）

```php
$result = [
```
- `$result`: 変数名（PHPでは`$`が必要）です
- `[`: 配列（連想配列）の開始です

```php
    'success' => true,
```
- `'success'`: キー（プロパティ名）です
- `=>`: キーと値を結びつける演算子です
- `true`: 値（論理型）です
- `,`: 要素の区切りです

```php
    'message' => 'ブックマークを更新しました',
```
- `'message'`: メッセージ用のキーです
- `'ブックマークを更新しました'`: 文字列の値です

```php
    'bookmarked' => true
```
- `'bookmarked'`: ブックマーク状態を表すキーです
- 最後の要素にはカンマ不要（あっても可）です

```php
];
```
- 配列定義の終了です

```php
echo json_encode($result);
```
- `echo`: 出力する命令です
- `json_encode()`: PHP配列をJSON文字列に変換します（例: `{"success":true,"message":"..."}`）
- この文字列がクライアントに送信されます

```php
exit;
```
- `exit`: スクリプトの実行を終了します
- これ以降のコードは実行されません（不要な出力を防ぎます）

```php
?>
```
- PHPコードの終了タグです（ファイル末尾では省略推奨）

---

### 6. エラーハンドリング

```javascript
try {
```
- `try`: エラーが発生する可能性のあるコードブロックの開始です

```javascript
  const response = await fetch(url, options);
```
- ネットワークリクエストを実行します（通信エラーの可能性があります）

```javascript
  if (!response.ok) {
```
- HTTPステータスコードが成功範囲外（400, 500番台など）かチェックします

```javascript
    throw new Error(`サーバーエラー: ${response.status}`);
```
- 明示的にエラーを投げてcatchブロックに処理を移します

```javascript
  }
```
- if文の終了です

```javascript
  const data = await response.json();
```
- JSON解析を実行します（不正なJSON形式だとエラーになります）

```javascript
} catch (error) {
```
- tryブロック内で発生したすべてのエラーをキャッチします

```javascript
  if (error instanceof TypeError) {
```
- `instanceof`: オブジェクトの型を判定する演算子です
- `TypeError`: ネットワークエラーや構文エラーの場合に発生します

```javascript
    console.error('通信エラー:', error);
```
- ネットワーク関連のエラーメッセージを出力します

```javascript
  } else {
```
- TypeError以外のエラーの場合です

```javascript
    console.error('エラー:', error);
```
- その他のエラーメッセージを出力します

```javascript
  }
}
```
- catch文とtry-catch全体の終了です

---

## ボタン点滅の実装パターン

### CSSアニメーション

```css
@keyframes blink {
```
- `@keyframes`: CSSアニメーションを定義するルールです
- `blink`: アニメーション名（任意の名前）です

```css
  0%, 100% { opacity: 1; }
```
- `0%, 100%`: アニメーション開始時(0%)と終了時(100%)の状態です
- `opacity: 1`: 完全に不透明（通常の状態）です

```css
  50% { opacity: 0.3; }
```
- `50%`: アニメーション中間地点の状態です
- `opacity: 0.3`: 30%の透明度（薄く表示）です

```css
}
```
- keyframes定義の終了です

```css
.bookmarking {
```
- `.bookmarking`: このクラスが付いた要素に適用されるスタイルです

```css
  animation: blink 0.5s ease-in-out infinite;
```
- `animation`: アニメーションを適用するプロパティです
- `blink`: 使用するアニメーション名（上で定義したもの）です
- `0.5s`: アニメーション1回の長さ（0.5秒）です
- `ease-in-out`: 緩急をつける方式（開始と終了がゆっくり）です
- `infinite`: 無限に繰り返します

```css
}
```
- クラス定義の終了です

---

### JavaScriptでの制御

```javascript
button.classList.add('bookmarking');
```
- `button`: ボタン要素のDOM参照です
- `.classList`: 要素のclass属性を操作するプロパティです
- `.add()`: 指定したクラス名を追加します
- `'bookmarking'`: 追加するクラス名（CSSアニメーションが開始されます）

```javascript
await toggleFavorite(id);
```
- `await`: 非同期関数の完了を待ちます
- `toggleFavorite(id)`: ブックマーク切り替え処理を実行します（サーバーとの通信）
- この行で処理が一時停止し、通信完了まで次の行に進みません

```javascript
button.classList.remove('bookmarking');
```
- `.remove()`: 指定したクラス名を削除します
- クラスが削除されることでアニメーションが停止します

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
