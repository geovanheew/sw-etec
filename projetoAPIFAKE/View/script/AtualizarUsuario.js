export default function AtualizarUsuario() {
  const userIdInput = document.getElementById('update-id');
  const usernameInput = document.getElementById('update-username');
  const emailInput = document.getElementById('update-email');
  const phoneInput = document.getElementById('update-phone');
  const addressInput = document.getElementById('update-address');
  const updateUserButton = document.getElementById('update-user-button'); // Corrigido o ID aqui
  const updateUserResponse = document.getElementById('update-user-response');

  if (!userIdInput || !usernameInput || !emailInput || !phoneInput || !addressInput || !updateUserButton || !updateUserResponse) {
    console.error('Um ou mais elementos do DOM não foram encontrados.');
    return;
  }

  updateUserButton.disabled = true;
  updateUserButton.classList.add('loading');

  const userId = userIdInput.value;

  if (!userId) {
    alert('Por favor, insira o ID do usuário');
    updateUserButton.disabled = false;
    updateUserButton.classList.remove('loading');
    return;
  }

  const userData = {
    username: usernameInput.value,
    email: emailInput.value,
    phone: phoneInput.value,
    address: {
      street: addressInput.value,
      city: addressInput.value,
    }
  };

  return fetch(`https://fakestoreapi.com/users/${userId}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(userData),
  })
    .then(res => {
      if (!res.ok) {
        throw new Error('Erro ao atualizar usuário');
      }
      return res.json();
    })
    .then(user => {
      updateUserResponse.innerHTML = `
        <div class="user-card">
          <h3>${user.username}</h3>
          <p>Email: ${user.email}</p>
          <p>Phone: ${user.phone}</p>
          <p>Address: ${user.address.street}, ${user.address.city}</p>
        </div>
      `;
    })
    .catch(error => {
      console.error('Erro ao atualizar usuário:', error);
      updateUserResponse.innerHTML = `<p>${error.message}</p>`;
    })
    .finally(() => {
      updateUserButton.disabled = false;
      updateUserButton.classList.remove('loading');
    });
}
