export default function BuscarUnico() {
  const userIdInput = document.getElementById('user-id');
  const fetchUnicoButton = document.getElementById('fetchUnicoButton');
  const singleUserContainer = document.getElementById('single-user-container');
  
  const userId = userIdInput.value;

  if (!userId) {
    alert('Por favor, insira o ID do usuário');
    return Promise.resolve(); // Resolve imediatamente para evitar erro
  }

  if (!fetchUnicoButton || !singleUserContainer) {
    console.error('Botão ou container não encontrados.');
    return Promise.resolve(); // Resolve imediatamente para evitar erro
  }

  fetchUnicoButton.disabled = true;
  fetchUnicoButton.classList.add('loading');

  return fetch(`https://fakestoreapi.com/users/${userId}`)
    .then(res => res.json())
    .then(user => {
      if (user.id) {
        singleUserContainer.innerHTML = `
          <div class="user-card">
            <h3>${user.username}</h3>
            <p>Email: ${user.email}</p>
            <p>Phone: ${user.phone}</p>
            <p>Address: ${user.address.street}, ${user.address.city}</p>
          </div>
        `;
      } else {
        singleUserContainer.innerHTML = '<p>Usuário não encontrado</p>';
      }
    })
    .catch(error => console.error('Erro ao buscar usuário:', error))
    .finally(() => {
      fetchUnicoButton.disabled = false;
      fetchUnicoButton.classList.remove('loading');
    });
}
