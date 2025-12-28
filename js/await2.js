async function getJsonData(url) {
  try {
    // 1. fetch()でGETリクエストを送信し、レスポンス（Promise）を待つ
    const response = await fetch(url); 

    // 2. レスポンスが成功（status 200-299）か確認する
    if (!response.ok) {
      // レスポンスがOKでない場合、エラーを投げる
      throw new Error(`HTTPエラー! ステータス: ${response.status}`);
    }

    // 3. response.json()でレスポンスボディをJSONとして解析（Promise）し、待つ
    const data = await response.json();

    // 4. 取得したJSONデータを返す
    return data;
  } catch (error) {
    // エラーハンドリング
    console.error('データの取得中にエラーが発生しました:', error);
    return null; // または適切なエラー値を返す
  }
}