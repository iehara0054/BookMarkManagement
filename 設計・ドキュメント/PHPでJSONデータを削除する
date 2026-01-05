PHPでJSONデータを「削除」する方法は、JSON文字列から特定のキー・値を削除する（json_decodeしてunsetやarray_splice）、JSONファイルを削除する（unlink）、またはファイル内容を空にする（file_put_contents）など、目的によって異なりますが、一般的にはjson_decodeでPHP配列にし、unset()やarray_splice()で要素を削除後、json_encode()で再変換します。
JSONファイル自体を消すならunlink()、ファイルの中身を消す（空にする）ならfile_put_contents($filename, '')を使います。
1. JSON文字列/配列の要素を削除する
JSONデータがPHPの文字列（json_encodeされたもの）や配列として扱われている場合、以下の方法で要素を削除します。
json_decode()でPHP配列に変換：まず$data = json_decode($jsonString, true);で連想配列にします。
unset()で特定のキーを削除：連想配列の場合、unset($data['削除したいキー']);で要素を削除します。インデックスは詰まりません。
array_splice()でインデックス指定削除：配列の場合、array_splice($data, 削除したいインデックス, 1);で要素を削除し、インデックスを詰めます。
array_values()でインデックスを詰め直す：unset()後にインデックスを振り直したい場合は$data = array_values($data);を実行します。
json_encode()でJSON文字列に戻す：$newJsonString = json_encode($data);で再度JSON形式に戻します。 
例（連想配列からキー「age」を削除）
php
$jsonString = '{"name": "Taro", "age": 30, "city": "Tokyo"}';
$data = json_decode($jsonString, true); // JSONをPHP連想配列に

unset($data['age']); // 'age'キーの要素を削除

$newJsonString = json_encode($data); // PHP配列をJSON文字列に
echo $newJsonString; // {"name":"Taro","city":"Tokyo"}
2. JSONファイル自体を削除する
unlink()関数：ファイルシステム上のJSONファイルを直接削除します。
php
$filePath = 'data.json';
if (file_exists($filePath)) {
    unlink($filePath); // ファイルを削除
    echo "JSONファイルが削除されました。";
} else {
    echo "ファイルが見つかりません。";
}
3. JSONファイルの内容を空にする（削除とみなす場合）
file_put_contents()で空文字列を書き込む：ファイルは残しつつ中身を空にしたい場合に利用します。
php
$filePath = 'data.json';
file_put_contents($filePath, ''); // ファイルの中身を空にする
echo "JSONファイルの内容が空になりました。";
まとめ
何をもって「削除」したいか（データの一部か、ファイルそのものか）で使う関数が変わります。多くの場合、JSONデータを操作する際はjson_decode -> unsetなど -> json_encodeのプロセスを使うことになります。