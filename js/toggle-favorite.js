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

    const jsonString = await response.json();

    // console.log(data);

    // const jsonString = '[{"name": "Alice", "age": 30}, {"name": "Bob", "age": 25}]';
    // const testdata = JSON.parse(jsonString);
    // console.log(testdata[0].name);

    const obj = JSON.parse(jsonString);

    console.log(obj['id']);
    // $i = 0;
    // obj.forEach((value) => {
      // console.log(value[0].id);
      // $i++;
    // });

  } catch (error) {
    console.error('❌ エラー発生:', error);
  }
}