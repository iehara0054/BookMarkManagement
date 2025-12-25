async function main() {
    try {
        const response = await fetch('/api/hello');
        if (!response.ok) {
            throw new Error(`HTTPエラー: ${response.status}`);
        }
        const responseBody = await response.json();
        document.getElementById('message').innerText = responseBody.message;
    } catch (error) {
        console.error('エラーが発生しました:', error);
        document.getElementById('message').innerText = 'エラーが発生しました。';
    }
}