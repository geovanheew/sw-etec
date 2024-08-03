export default function DeletarUsuario() {
    const userIdInput = document.getElementById('delete-id');
    const deleteUserButton = document.getElementById('delete-user-button');
    const deleteUserResponse = document.getElementById('delete-user-response');
  
    deleteUserButton.disabled = true;
    deleteUserButton.classList.add('loading');
  
    const userId = userIdInput.value;
  
    if (!userId) {
      alert('Por favor, insira o ID do usuário');
      return Promise.resolve(); // Resolve imediatamente para evitar erro
    }
  
    return fetch(`https://fakestoreapi.com/users/${userId}`, {
      method: 'DELETE',
    })
      .then(() => {
        deleteUserResponse.innerHTML = `<p>Usuário ${userId} deletado com sucesso!</p>`;
      })
      .catch(error => console.error('Erro ao deletar usuário:', error))
      .finally(() => {
        deleteUserButton.disabled = false;
        deleteUserButton.classList.remove('loading');
      });
}