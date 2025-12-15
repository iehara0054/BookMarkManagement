async function toggleFavorite(button) {
  try {
    const itemId = button.getAttribute('data-item-id');

    const requestBody = { id: itemId };

    const response = await fetch('API/toggleFavorite.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(requestBody),
    });

    const json = await response.json();
    // const obj = JSON.parse(json);
    json.forEach(function(value){
      const obj = JSON.parse(value);
      // if ((value.favorite == false) && (value.id == "556381f0c07ba834")){
        console.log('==========')
        console.log(obj.result);
      // }
    });
  } catch (error) {
    console.error('❌ エラー発生:', error);
  }
}