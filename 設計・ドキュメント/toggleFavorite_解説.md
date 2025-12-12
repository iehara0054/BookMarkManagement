# toggleFavorite.php 詳細解説

## ファイル概要
- **ファイルパス**: `API/toggleFavorite.php`
- **目的**: クライアントから送信されたJSON形式のデータを受け取り、お気に入りのトグル処理を行うAPI
- **作成日**: 2025-12-11

---

## コード全文
```php
<?php
// JSON形式で送られてきたデータを受け取る
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// idを取得
$itemId = $data['id'] ?? null;

// 確認用の出力
var_dump($itemId);
```

---

## 行ごとの詳細解説

### 1行目: `<?php`
- **機能**: PHPコードの開始タグ
- **説明**: このタグ以降、サーバー側でPHPとして実行される

---

### 2行目: `// JSON形式で送られてきたデータを受け取る`
- **機能**: コメント行
- **説明**: プログラムの動作に影響しない説明文。次の処理の目的を明示している

---

### 3行目: `$json = file_get_contents('php://input');`
- **機能**: HTTPリクエストボディの取得
- **説明**:
  - `file_get_contents('php://input')` - HTTPリクエストのボディ（生データ）を取得
  - `php://input` は特殊なストリームで、POSTリクエストで送信された生のリクエストボディを読み取る
  - JavaScriptの`fetch()`などで送信されたJSONデータを受け取るために使用
  - 取得したデータ（文字列形式）を変数`$json`に格納

**例**:
```javascript
// クライアント側（JavaScript）
fetch('API/toggleFavorite.php', {
  method: 'POST',
  body: JSON.stringify({id: 123})
});
```
このとき、`$json`には文字列`'{"id": 123}'`が格納される

---

### 4行目: `$data = json_decode($json, true);`
- **機能**: JSON文字列のデコード（PHPの配列に変換）
- **説明**:
  - `json_decode()` - JSON文字列をPHPの配列やオブジェクトに変換する関数
  - **第1引数**: デコードするJSON文字列（`$json`）
  - **第2引数**: `true` - 結果を連想配列として返す
    - `true`: 連想配列（`['id' => 123]`）
    - `false`または省略: stdClassオブジェクト（`$data->id`でアクセス）
  - 変換結果を変数`$data`に格納

**変換例**:
```php
// 入力JSON: '{"id": 123, "name": "test"}'
// 出力配列: ['id' => 123, 'name' => 'test']
```

---

### 7行目: `$itemId = $data['id'] ?? null;`
- **機能**: 配列から安全にID値を取得
- **説明**:
  - `$data['id']` - 配列`$data`から`'id'`というキーの値を取得
  - `??` - Null合体演算子（Null Coalescing Operator、PHP 7.0以降）
  - **動作**:
    - 左辺（`$data['id']`）が存在し、かつnullでない → 左辺の値を返す
    - 左辺が存在しない、またはnull → 右辺の値（`null`）を返す
  - エラー（undefined index）を防止するための安全な記述方法

**動作例**:
```php
// ケース1: idが存在する
$data = ['id' => 123];
$itemId = $data['id'] ?? null;  // $itemId = 123

// ケース2: idが存在しない
$data = ['name' => 'test'];
$itemId = $data['id'] ?? null;  // $itemId = null

// ケース3: idがnull
$data = ['id' => null];
$itemId = $data['id'] ?? null;  // $itemId = null
```

**従来の書き方との比較**:
```php
// PHP 5.x（古い書き方）
$itemId = isset($data['id']) ? $data['id'] : null;

// PHP 7.0以降（推奨）
$itemId = $data['id'] ?? null;
```

---

### 10行目: `var_dump($itemId);`
- **機能**: 変数の内容をデバッグ出力
- **説明**:
  - `var_dump()` - 変数の型と値を詳細に表示するデバッグ用関数
  - **用途**: 開発中の動作確認
  - **注意**: 本番環境では削除すべき（セキュリティリスク、不要な出力）

**出力例**:
```php
// 整数の場合
int(123)

// NULL の場合
NULL

// 文字列の場合
string(5) "test"

// 真偽値の場合
bool(true)
```

---

## 全体の処理フロー

```
[クライアント（JavaScript）]
         ↓ fetch() でJSON送信
         ↓ {"id": 123}
         ↓
[サーバー（PHP）]
         ↓
    ① file_get_contents('php://input')
       → 生のリクエストボディを取得
       → $json = '{"id": 123}'
         ↓
    ② json_decode($json, true)
       → JSON文字列を配列に変換
       → $data = ['id' => 123]
         ↓
    ③ $data['id'] ?? null
       → 安全にID値を取得
       → $itemId = 123
         ↓
    ④ var_dump($itemId)
       → デバッグ出力
       → int(123)
```

---

## 現状の問題点

このコードは基本的なデータ受信処理のみで、以下が未実装です:

### 1. データベース接続がない
```php
// 必要な処理
require_once('../class/BookmarkDB.php');
$db = new BookmarkDB();
```

### 2. お気に入りのトグル処理がない
- データベースでお気に入り状態を確認
- 状態に応じて追加/削除を実行

### 3. レスポンスの返却がない
```php
// 必要な処理
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'お気に入りに追加しました']);
```

### 4. エラーハンドリングがない
- `$itemId`がnullの場合の処理
- JSONデコード失敗時の処理
- データベースエラー時の処理

### 5. セキュリティ対策がない
- SQLインジェクション対策（プリペアドステートメント使用）
- CSRF対策
- 入力値のバリデーション

---

## 完成版の実装例

```php
<?php
header('Content-Type: application/json; charset=UTF-8');

try {
    // JSON形式で送られてきたデータを受け取る
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    // JSONデコードエラーチェック
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON format');
    }

    // idを取得
    $itemId = $data['id'] ?? null;

    // バリデーション
    if ($itemId === null || !is_numeric($itemId)) {
        throw new Exception('Invalid item ID');
    }

    // データベース接続
    require_once('../class/BookmarkDB.php');
    $db = new BookmarkDB();

    // お気に入り状態をトグル
    $isFavorite = $db->toggleFavorite($itemId);

    // 成功レスポンス
    echo json_encode([
        'success' => true,
        'isFavorite' => $isFavorite,
        'message' => $isFavorite ? 'お気に入りに追加しました' : 'お気に入りから削除しました'
    ]);

} catch (Exception $e) {
    // エラーレスポンス
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
```

---

## 関連ファイル
- **フロントエンド**: `js/main.js` - お気に入りボタンのクリックイベント処理
- **データベースクラス**: `class/BookmarkDB.php` - データベース操作
- **API設計書**: `設計・ドキュメント/API仕様書.md`

---

## 参考資料

### PHP関数リファレンス
- [file_get_contents()](https://www.php.net/manual/ja/function.file-get-contents.php)
- [json_decode()](https://www.php.net/manual/ja/function.json-decode.php)
- [Null合体演算子](https://www.php.net/manual/ja/migration70.new-features.php#migration70.new-features.null-coalesce-op)
- [var_dump()](https://www.php.net/manual/ja/function.var-dump.php)

### ベストプラクティス
- REST API設計
- JSONハンドリング
- エラーハンドリング
- セキュリティ対策（OWASP Top 10）
