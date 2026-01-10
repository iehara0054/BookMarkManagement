async function deleteBookMark(button) {
  try {
        const deleteKey = button.getAttribute('data-delete-item-key');

    const requestBody = { delete_key: deleteKey };
    console.log('delete_Key：', deleteKey);
    const response = await fetch('API/deleteBookMark.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        key: 'delete_Key',
        value: deleteKey 
      }),
    });

    const data = await response.json();
//ここから処理を書きます






      } catch (error) {
    console.error('❌ エラー発生:', error);
    button.disabled = false;
  }
}