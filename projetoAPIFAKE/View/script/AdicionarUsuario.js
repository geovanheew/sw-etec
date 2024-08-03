export default function AdicionarUsuario() {
  const usernameInput = document.getElementById('add-username')
  const emailInput = document.getElementById('add-email')
  const passwordInput = document.getElementById('add-password')
  const phoneInput = document.getElementById('add-phone')
  const addressInput = document.getElementById('add-address')
  const numberInput = document.getElementById('add-number')
  const zipcodeInput = document.getElementById('add-zipcode')
  const latInput = document.getElementById('add-lat')
  const longInput = document.getElementById('add-long')
  const addUserButton = document.getElementById('add-user')
  const addUserResponse = document.getElementById('add-user-response')

  addUserButton.disabled = true
  addUserButton.classList.add('loading')

  const addressParts = addressInput.value.split(',')

  const userData = {
    email: emailInput.value,
    username: usernameInput.value,
    password: passwordInput.value,
    name: {
      firstname: addressParts[0] || '',
      lastname: addressParts[1] || '',
    },
    address: {
      street: addressParts[2] || '',
      city: addressParts[3] || '',
      number: parseInt(numberInput.value, 10) || 0,
      zipcode: zipcodeInput.value || '',
      geolocation: {
        lat: latInput.value || '0.0',
        long: longInput.value || '0.0',
      }
    },
    phone: phoneInput.value
  }

  return fetch('https://fakestoreapi.com/users', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(userData),
  })
    .then(res => {
      if (!res.ok) {
        throw new Error('Erro ao adicionar usuário')
      }
      return res.json()
    })
    .then(user => {
      console.log('Resposta da API:', user)
      
      addUserResponse.innerHTML = `
        <div class="user-card">
          <h3>Usuário adicionado!</h3>
          <p>ID: ${user.id || 'N/A'}</p>
        </div>
      `
    })
    .catch(error => console.error('Erro ao adicionar usuário:', error))
    .finally(() => {
      addUserButton.disabled = false
      addUserButton.classList.remove('loading')
    })
}
