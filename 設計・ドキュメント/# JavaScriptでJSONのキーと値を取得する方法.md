# JavaScriptでJSONのキーと値を取得する方法

## 概要

JavaScriptでJSONのキーと値を取得するには、`JSON.parse()`でJSON文字列をオブジェクトに変換後、`Object.keys()`でキーの配列を取得し、`for...in`ループや`Object.entries()`、またはキー指定（`obj.key`や`obj['key']`）で値を取得します。

**基本的な流れ:**
文字列化されたJSON → JavaScriptオブジェクト → キーと値の操作

---

## 1. JSON文字列をJavaScriptオブジェクトに変換する

まず、`JSON.parse()`を使ってJSON文字列をJavaScriptのオブジェクト（連想配列のようなもの）に変換します。

```javascript
const jsonString = '{"name": "Taro", "age": 30, "city": "Tokyo"}';
const data = JSON.parse(jsonString); // dataは { name: 'Taro', age: 30, city: 'Tokyo' }
```

---

## 2. キーと値を取得する方法

変換後の`data`オブジェクトからキーと値を取得する方法はいくつかあります。

### a. `for...in`ループ（キーと値を両方取得）

オブジェクトの各プロパティ（キー）をループ処理するのに便利です。

```javascript
for (const key in data) {
  console.log(`キー: ${key}, 値: ${data[key]}`);
}
// 出力:
// キー: name, 値: Taro
// キー: age, 値: 30
// キー: city, 値: Tokyo
```

### b. `Object.keys()`（キーのみ取得）

すべてのキーを配列として取得します。

```javascript
const keys = Object.keys(data);
console.log(keys); // ['name', 'age', 'city']
```

### c. `Object.entries()`（キーと値のペアを配列で取得）

キーと値のペアを`[key, value]`の配列として取得できます。

```javascript
const entries = Object.entries(data);
console.log(entries); // [['name', 'Taro'], ['age', 30], ['city', 'Tokyo']]
```

### d. 特定のキーの値を取得する（ドット記法/ブラケット記法）

キーがわかっている場合は直接アクセスできます。

```javascript
console.log(data.name);    // Taro (ドット記法)
console.log(data['age']);  // 30 (ブラケット記法)
```

---

## 3. 全体の流れの例

```javascript
// 1. JSON文字列
const jsonStr = '{"product": "Laptop", "price": 150000, "inStock": true}';

// 2. JavaScriptオブジェクトに変換
const product = JSON.parse(jsonStr);

// 3. キーと値の取得と表示
console.log("--- 全てのキーと値 ---");
for (const key in product) {
  console.log(`Key: ${key}, Value: ${product[key]}`);
}

console.log("\n--- キーのみ ---");
const productKeys = Object.keys(product);
console.log(productKeys);

console.log("\n--- 特定の値 ---");
console.log(`商品名: ${product.product}`);
```

---

## まとめ

| メソッド/記法 | 用途 | 戻り値 |
|-------------|------|--------|
| `JSON.parse()` | JSON文字列をオブジェクトに変換 | オブジェクト |
| `for...in` | すべてのキーをループ処理 | - |
| `Object.keys()` | すべてのキーを取得 | キーの配列 |
| `Object.entries()` | キーと値のペアを取得 | `[key, value]`の配列 |
| `obj.key` / `obj['key']` | 特定のキーの値を取得 | 値 |
