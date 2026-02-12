# コードレビュー 4.01

## 全体の評価

PHPとバニラJSで構成されたシンプルなブックマーク管理アプリケーション。
CSRF対策やXSS対策の基本は押さえられており、良い基盤ができている。
以下、カテゴリ別に指摘事項をまとめる。

---

## 1. セキュリティ

| 重要度 | ファイル | 行 | 問題 | 状態 |
|--------|----------|----|------|------|
| **高** | `index.php` | 129 | 検索フォームにCSRFトークンがあるが、POST受信時にCSRF検証をしていない。`$_POST['searchValue']`をそのまま利用している | **修正済み** |
| **高** | `index.php` | 135-137 | 「絞り込み解除」フォームにもCSRF検証がない | **修正済み** |
| **高** | `js/button_control.js` | 21 | `fetch('data/bookmarks_file.json')` でJSONファイルを直接公開している。`deleteKey`などの秘密情報がクライアントに漏洩する。deleteKeyが分かれば誰でもブックマークを削除可能 | |
| **中** | `API/add.php` | 14 | `$enteredBookMarkData = $_POST;` で全POSTデータを一旦変数に代入しており、意図しないデータが混入する可能性がある（後で上書きされるが、不要な処理） | **修正済み** |
| **中** | `API/add.php` | 70 | URLエラー時にセッションへ `$enteredBookMarkData` をそのまま保存している。この中に `deleteKey` や `id` も含まれており、不要な情報がセッションに残る | **修正済み** |

---

## 2. バグ・不具合

| 重要度 | ファイル | 行 | 問題 | 状態 |
|--------|----------|----|------|------|
| **高** | `index.php` | 29 | `<!DOCTYPE html lang="ja">` は不正なHTML。正しくは `<!DOCTYPE html>` と `<html lang="ja">` に分ける必要がある。現状では `<html>` タグに `lang` 属性がなく、アクセシビリティに影響する | **修正済み** |
| **高** | `index.php` | 254 | `$_SESSION['showModal']` は46行目の `unset` で既に削除済み。この条件は常に `false` になり、`modal_close.js` が読み込まれない | **修正済み** |
| **中** | `class/BookMarkManager.php` | 108 | `stripos($item['memo'], $targetValue)` - `memo` が空文字列や `null` の場合、PHP 8.x では警告が出る可能性がある | **修正済み** |
| **中** | `class/BookMarkManager.php` | 116 | `foreach ($item['tags'] as $tag)` - `tags` が `null` や文字列の場合に `TypeError` が発生する | **修正済み** |
| **低** | `index.php` | 57-61 | モーダル内の `success_message` 表示後にセッションを `unset` していない。デスクトップ側（99行目）では `unset` しているが、モーダル側では残り続ける | **修正済み** |
| **低** | `index.php` | 65-69 | モーダル内の `error_url` も同様に `unset` がない（デスクトップ側108行目にはある） | **修正済み** |

---

## 3. アーキテクチャ・設計

| 重要度 | ファイル | 行 | 問題 | 状態 |
|--------|----------|----|------|------|
| **中** | `index.php` | 53, 91 | `id="inputForm"` が2箇所で重複している。HTMLの仕様上、IDはページ内で一意でなければならない。CSSやJSの動作に予期しない影響を与える | **修正済み** |
| **中** | `class/BookMarkManager.php` | 78 | `delete_bookMarks` 内で `file_get_contents` を直接呼んでいる。既に `load_bookmarkLists()` メソッドがあるのに使っていない（責務の重複） | **修正済み** |
| **中** | `API/toggleFavorite.php` | 31 | 同様に `file_get_contents` を直接使用。`load_bookmarkLists()` を活用すべき | **修正済み** |
| **中** | 全体 | - | JSONファイルによるデータ永続化は、同時書き込み時にデータ競合が発生しうる。`flock()` による排他制御がない | |
| **低** | `class/BookMarkManager.php` | 49 | `save_bookMarks` 内のインデントが一部崩れている（`if ($fp === false)` のブロック） | **修正済み** |

---

## 4. JavaScript

| 重要度 | ファイル | 行 | 問題 | 状態 |
|--------|----------|----|------|------|
| **中** | `js/toggle_favorite.js` | 11 | `fetch('API/toggleFavorite.php')` は相対パス。index.php以外のページやサブディレクトリから呼ぶ場合に壊れる。`button_control.js:21` の `data/bookmarks_file.json` も同様 | |
| **中** | `js/toggle_favorite.js` | 40 | `button.disabled = false` をcatchブロックで復元しているが、disabledにする処理がない。連打防止が不完全 | **修正済み** |
| **低** | `js/filter_scroll.js`, `js/unfilter_scroll.js`, `js/scroll_after_deletion.js` | - | 3ファイルとも完全に同一の内容。1つのファイルに統合すべき | **修正済み** |
| **低** | `js/button_control.js` | 4-13 | `clearText()` でデスクトップ側の要素（`#title` 等）がモバイル時に存在しない場合、`null` に対する `.value` アクセスでエラーになりうる | |

---

## 5. CSS

| 重要度 | ファイル | 行 | 問題 | 状態 |
|--------|----------|----|------|------|
| **低** | `css/style.css` | 574-576 | `dialog { display: none; }` がデスクトップで設定された後、メディアクエリ内で `dialog[open] { display: block; }` としている。`<dialog>` のネイティブ動作と競合しうる | **修正済み** |
| **低** | `css/style.css` | 753, 755 | `#closeBtn` に `border` プロパティが2回宣言されている。後の `border: none` が優先され、最初の `border: 2px solid #999` は無意味 | **修正済み** |
| **低** | `css/style.css` | 218-226 | `.empty-state`, `.empty-icon`, `.empty-text`, `.empty-subtext` のクラスが定義されているが、HTMLで使われていない（未使用CSS） | **修正済み** |

---

## 6. 改善提案（優先度順）

1. **`data/bookmarks_file.json` の直接アクセスを防ぐ** - `.htaccess` でdataディレクトリへのアクセスを制限するか、お気に入り状態の初期化をAPI経由にする
2. **`<!DOCTYPE html lang="ja">` を修正** - `<!DOCTYPE html>` + `<html lang="ja">` に分離 → **修正済み**
3. **`$_SESSION['showModal']` の unset タイミングを修正** - 254行目の判定前に unset されている問題を解消 → **修正済み**
4. **`id="inputForm"` の重複を解消** - モーダル側に別のIDを付与 → **修正済み**
5. **検索時のCSRFトークン検証を追加** - または検索をGETメソッドに変更 → **修正済み**
6. **`search_bookmarks` の null/型安全性を向上** - `$item['memo'] ?? ''` のような null 合体演算子を使用 → **修正済み**
7. **ファイルロック（`flock()`）の追加** - 同時リクエスト時のデータ破損防止
8. **スクロール用JSの統合** - 3つの同一ファイルを1つに → **修正済み**
