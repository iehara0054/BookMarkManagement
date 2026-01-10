async function deleteBookMark(button) {
  try {
        const deleteKey = button.getAttribute('data-delete-item-key');

    const requestBody = { id: deleteKey };
    console.log('delete_Key：', deleteKey);
    const response = await fetch('API/deleteBookMark.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(requestBody),
    });

    const data = await response.json();
//ここから処理を書きます






      } catch (error) {
    console.error('❌ エラー発生:', error);
    button.disabled = false;
  }
}