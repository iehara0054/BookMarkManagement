PHPで特定のキーと値を持つJSONデータから要素を削除するには、JSONデータをPHPの配列にデコードし、array_filter()関数を使って条件に一致する要素をフィルタリング（除外）してから、再度JSON形式にエンコードするという手順が最も効率的です。 
手順
JSONデータをPHPの配列にデコードする: json_decode()関数を使用します。
特定の条件でフィルタリングする: array_filter()とコールバック関数を使用して、削除したい条件に一致しない要素のみを残します。
PHPの配列をJSONデータにエンコードする: json_encode()関数を使用します。 
サンプルコード
ここでは、idが2のオブジェクトをJSONデータから削除する例を示します。
php
<?php

// 元のJSONデータ（例）
$json_data = '[
    {"id": 1, "name": "Apple", "color": "red"},
    {"id": 2, "name": "Orange", "color": "orange"},
    {"id": 3, "name": "Blueberry", "color": "blue"}
]';

// 削除したいキーと値
$target_key = 'id';
$target_value = 2;

// 1. JSONデータをPHPの配列にデコードする (連想配列として取得するため、第2引数をtrueに設定)
$data_array = json_decode($json_data, true);

// 2. 特定の条件でフィルタリングする
// array_filterは、コールバック関数がtrueを返す要素のみを新しい配列に残します
$filtered_array = array_filter($data_array, function ($item) use ($target_key, $target_value) {
    // 指定されたキーが存在し、かつ値がターゲットの値と一致しない場合にtrueを返す
    return isset($item[$target_key]) && $item[$target_key] != $target_value;
});

// キーのインデックスを振り直す（オプション）
// array_filterはキーを保持するため、連番にしたい場合は array_values() を使用します
$reindexed_array = array_values($filtered_array);

// 3. PHPの配列をJSONデータにエンコードする
$updated_json_data = json_encode($reindexed_array, JSON_PRETTY_PRINT);

// 結果の出力
echo $updated_json_data;

?>
実行結果
上記のコードを実行すると、idが2のオブジェクトが削除された以下のJSONデータが出力されます。
json
[
    {
        "id": 1,
        "name": "Apple",
        "color": "red"
    },
    {
        "id": 3,
        "name": "Blueberry",
        "color": "blue"
    }
]
このように、一度PHPの配列に変換してから配列操作を行い、最後にJSON形式に戻すのが一般的なアプローチです。