document.addEventListener('DOMContentLoaded', () => {
  const noteField = document.getElementById('cc-refer-note');
  const noteCount = document.getElementById('cc-refer-note-count');
  const dateField = document.getElementById('cc-refer-wedding-date');

  if (noteField && noteCount) {
    const updateCount = () => {
      noteCount.textContent = `${noteField.value.length}/250 CHARACTERS`;
    };
    noteField.addEventListener('input', updateCount);
    updateCount();
  }

  if (dateField && typeof window.flatpickr === 'function') {
    window.flatpickr(dateField, {
      dateFormat: 'm/d/Y',
      allowInput: true,
    });
  }
});
