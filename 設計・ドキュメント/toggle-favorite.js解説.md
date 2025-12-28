# toggleFavorite.js 詳細解説

このドキュメントは、`js/toggleFavorite.js`のコードを初学者向けに1行ずつ解説したものです。

---

## コード全体

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

## 1行目: `async function toggleFavorite(itemId) {`

```javascript
async function toggleFavorite(itemId) {
```

**解説:**
- `async function` = 「非同期関数」を定義するキーワードです
- 非同期関数とは、時間がかかる処理（サーバーとの通信など）を待つ間、他の処理をブロック（停止）せずに実行できる関数です
- `toggleFavorite` = 関数の名前。「ブックマークを切り替える」という意味
- `(itemId)` = この関数の引数（パラメータ）。ブックマークしたいアイテムのIDを受け取ります
- `{` = 関数の本体の開始

---

## 2行目: `try {`

```javascript
  try {
```

**解説:**
- `try { ... }` = エラーハンドリング（エラー処理）のための構文の開始部分
- `try`ブロック内のコードを実行中にエラーが発生した場合、プログラムがクラッシュせず、`catch`ブロックでエラーをキャッチ（捕捉）できます
- インデント（字下げ）がされているのは、関数の中にあることを視覚的に分かりやすくするため

---

## 3行目: `const response = await fetch('API/toggleFavorite.php', {`

```javascript
    const response = await fetch('API/toggleFavorite.php', {
```

**解説:**
- `const` = 変数を宣言するキーワード。この変数は再代入できません
- `response` = 変数名。サーバーからの応答（レスポンス）を格納します
- `=` = 右側の値を左側の変数に代入します
- `await` = 非同期処理（`fetch`）が完了するまで待つキーワード。`async`関数内でのみ使用可能
- `fetch('API/toggleFavorite.php', {` = サーバーにHTTPリクエストを送信する関数
  - `'API/toggleFavorite.php'` = リクエスト先のURL（サーバー側のPHPファイル）
  - `{` = オプション設定の開始（次の行に続く）

---

## 4行目: `method: 'POST',`

```javascript
      method: 'POST',
```

**解説:**
- `method: 'POST'` = HTTPメソッドを指定しています
- `POST` = データをサーバーに送信するためのHTTPメソッド
  - `GET`はデータを取得、`POST`はデータを送信・更新する時に使います
- `,` = カンマ。次のオプションが続くことを示します

---

## 5-6行目: `headers: { 'Content-Type': 'application/json' }`

```javascript
      headers: {
        'Content-Type': 'application/json'
      },
```

**解説:**
- `headers:` = HTTPヘッダーを設定するオプション
- ヘッダーとは、リクエストに関する追加情報をサーバーに伝えるもの
- `'Content-Type': 'application/json'` = 送信するデータの形式を指定
  - 「これからJSON形式のデータを送りますよ」とサーバーに伝えています
  - JSONは`{"key": "value"}`のような形式のデータ
- `},` = headersオブジェクトの終了

---

## 8行目: `body: JSON.stringify({ id: itemId })`

```javascript
      body: JSON.stringify({ id: itemId })
```

**解説:**
- `body:` = サーバーに送信する実際のデータ本体
- `JSON.stringify()` = JavaScriptオブジェクトをJSON形式の文字列に変換する関数
  - 例: `{id: 123}` → `'{"id":123}'`
- `{ id: itemId }` = 送信するデータ（オブジェクト）
  - `id:` がキー（項目名）
  - `itemId` が値（1行目で受け取ったブックマークのID）
  - 例: `itemId`が`123`なら、`{"id": 123}`というデータになります

---

## 9行目: `});`

```javascript
    });
```

**解説:**
- `}` = `fetch`関数のオプション設定オブジェクトの終了
- `)` = `fetch`関数の呼び出しの終了
- `;` = この文（ステートメント）の終了

---

## 11行目: `const data = await response.json();`

```javascript
    const data = await response.json();
```

**解説:**
- `const data` = 新しい変数`data`を宣言
- `await` = 次の処理が完了するまで待ちます
- `response.json()` = サーバーからのレスポンスをJSON形式として解析（パース）する関数
  - サーバーから返ってきた文字列`'{"success": true}'`を、JavaScriptで使えるオブジェクト`{success: true}`に変換します
- `;` = 文の終了

---

## 12行目: `return data;`

```javascript
    return data;
```

**解説:**
- `return` = 関数の呼び出し元に値を返すキーワード
- `data` = サーバーから受け取って解析したデータを返します
- この関数を呼び出した場所で、この`data`を使うことができます
- `;` = 文の終了

---

## 13行目: `} catch (error) {`

```javascript
  } catch (error) {
```

**解説:**
- `}` = `try`ブロックの終了
- `catch (error)` = `try`ブロック内でエラーが発生した場合に実行されるブロック
- `error` = 発生したエラー情報を格納する変数
- `{` = `catch`ブロックの開始

---

## 14行目: `console.error('Error:', error);`

```javascript
    console.error('Error:', error);
```

**解説:**
- `console.error()` = ブラウザの開発者ツールのコンソールにエラーメッセージを表示する関数
- `'Error:'` = 表示する固定文字列
- `error` = 発生したエラーの詳細情報
- `,` = 複数の値を表示する場合のセパレーター
- 例: コンソールに「Error: 接続失敗」のように表示されます
- `;` = 文の終了

---

## 15-16行目: `} }`

```javascript
  }
}
```

**解説:**
- 最初の`}` = `catch`ブロックの終了
- 2番目の`}` = `toggleFavorite`関数全体の終了

---

## 全体の流れのまとめ

この関数は以下の処理を行います：

1. **ブックマークIDを受け取る**（引数`itemId`）
2. **サーバーにリクエストを送信**（`fetch`）
   - 送信先：`API/toggleFavorite.php`
   - メソッド：POST
   - データ：`{"id": itemId}`
3. **サーバーからの応答を待つ**（`await`）
4. **応答をJSON形式に変換**（`response.json()`）
5. **結果を返す**（`return data`）
6. **エラーが起きたら**コンソールに表示（`catch`ブロック）

---

## 使用例

```javascript
// この関数を呼び出す場合
toggleFavorite(123);  // ID=123のブックマークを切り替える

// 戻り値を使う場合
const result = await toggleFavorite(123);
console.log(result);  // サーバーからの応答データを表示
```

---

## 重要な概念

### 非同期処理（async/await）
- サーバーとの通信は時間がかかるため、`async/await`を使って他の処理をブロックしません
- `await`はPromiseが解決されるまで待機します

### エラーハンドリング（try/catch）
- ネットワークエラーなどが発生してもプログラムがクラッシュしないよう、`try/catch`でエラーを捕捉します

### fetch API
- モダンなHTTPリクエスト送信方法
- Promiseベースで、レスポンスを非同期で処理します

### JSON
- JavaScript Object Notation
- データ交換フォーマットとして広く使われています
- `JSON.stringify()`: オブジェクト → 文字列
- `response.json()`: 文字列 → オブジェクト

---

**作成日:** 2025-12-10
**対象ファイル:** [js/toggle-favorite.js](../js/toggle-favorite.js)
