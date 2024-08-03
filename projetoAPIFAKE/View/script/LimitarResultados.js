export default function LimitarResultados() {
  const limitCountInput = document.getElementById('limit-count');
  const fetchLimitedButton = document.getElementById('fetch-limited-users');
  const limitedUsersContainer = document.getElementById('limited-users-container');

  fetchLimitedButton.disabled = true;
  fetchLimitedButton.classList.add('loading');

  const limit = limitCountInput.value || 5; // Default to 5 if no input

  return fetch(`https://fakestoreapi.com/users?limit=${limit}`)
    .then(res => res.json())
    .then(json => {
      limitedUsersContainer.innerHTML = json.map(user => `
        <div class="user-card">
          <h3>${user.username}</h3>
          <p>Id: ${user.id}</p>
          <p>Email: ${user.email}</p>
          <p>Telefone: ${user.phone}</p>
          <p>Endereço: ${user.address.street}, ${user.address.city}</p>
        </div>
      `).join('');
    })
    .catch(error => console.error('Erro ao buscar usuários:', error))
    .finally(() => {
      fetchLimitedButton.disabled = false;
      fetchLimitedButton.classList.remove('loading');
    });
}
