export default function BuscarUsuario() {
  const botaoBuscarUsuarios = document.getElementById('fetch-users');
  const containerUsuarios = document.getElementById('users-container');
  
  botaoBuscarUsuarios.disabled = true;
  botaoBuscarUsuarios.classList.add('loading');
  
  return fetch('https://fakestoreapi.com/users')
    .then(res => res.json())
    .then(json => {
      containerUsuarios.innerHTML = json.map(usuario => `
        <div class="user-card">
          <h3>${usuario.username}</h3>
          <p>Id: ${usuario.id}</p>
          <p>Email: ${usuario.email}</p>
          <p>Telefone: ${usuario.phone}</p>
          <p>Endereço: ${usuario.address.street}, ${usuario.address.city}</p>
        </div>
      `).join('');
    })
    .catch(error => console.error('Erro ao buscar usuários:', error))
    .finally(() => {
      botaoBuscarUsuarios.disabled = false;
      botaoBuscarUsuarios.classList.remove('loading');
    });
}
