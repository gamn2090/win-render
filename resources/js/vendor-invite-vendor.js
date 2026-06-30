document.addEventListener('DOMContentLoaded', () => {
  const noteField = document.getElementById('cc-vendor-note');
  const noteCount = document.getElementById('cc-vendor-note-count');

  if (noteField && noteCount) {
    const updateCount = () => {
      noteCount.textContent = `${noteField.value.length}/250 CHARACTERS`;
    };
    noteField.addEventListener('input', updateCount);
    updateCount();
  }
});
