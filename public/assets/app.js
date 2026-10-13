function suggest(input, key) {
  const listId = input.getAttribute('list');
  const list = document.getElementById(listId);
  if (!list) return;
  let last = 0;
  input.addEventListener('input', () => {
    const q = input.value.trim();
    const ticket = ++last;
    if (q.length < 1) {
      list.replaceChildren();
      return;
    }
    const url = listId === 'villes' ? '/api/villes?q=' : '/api/marques?q=';
    fetch(url + encodeURIComponent(q))
      .then((r) => r.json())
      .then((rows) => {
        if (ticket !== last) return;
        list.replaceChildren();
        rows.forEach((row) => {
          const option = document.createElement('option');
          option.value = row[key];
          list.appendChild(option);
        });
      })
      .catch(() => {});
  });
}

const ville = document.querySelector('input[name="ville"]');
const marque = document.querySelector('input[name="marque"]');
if (ville) suggest(ville, 'ville_nom_reel');
if (marque) suggest(marque, 'nom');
