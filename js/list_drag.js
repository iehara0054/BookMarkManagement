document.addEventListener('DOMContentLoaded', () => {
  const list = document.getElementById('sortable-list');
  let draggingRow = null;

// ドラッグ開始（ハンドルだけで開始する）
  list.addEventListener('dragstart', (e) => {
    const handle = e.target.closest('.drag-handle');
    if (!handle) return;

    draggingRow = handle.closest('tr');
    if (!draggingRow) return;
    draggingRow.classList.add('dragging');
  });

  // ドラッグ終了
  list.addEventListener('dragend', () => {
    if (!draggingRow) return;
    draggingRow.classList.remove('dragging');
    draggingRow = null;
  });

  // ドラッグ中（並び替え）
  list.addEventListener('dragover', (e) => {
    e.preventDefault();
    if (!draggingRow) return;

    const targetRow = e.target.closest('tr');
    if (!targetRow) return;
    if (targetRow === draggingRow) return;

    const rect = targetRow.getBoundingClientRect();
    const isAfter = (e.clientY - rect.top) > rect.height / 2;

    list.insertBefore(draggingRow, isAfter ? targetRow.nextSibling : targetRow);
  });
});