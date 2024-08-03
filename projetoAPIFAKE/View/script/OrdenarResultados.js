export default function OrdenarResultados() {
    const button = document.getElementById('sort-results-button');
    const container = document.getElementById('results-container');
    const sortOrder = document.getElementById('sort-order').value; // Captura o valor selecionado

    button.disabled = true;
    button.classList.add('loading');

    return fetch(`https://fakestoreapi.com/users?sort=${sortOrder}`)
        .then(res => res.json())
        .then(json => {
            container.innerHTML = json.map(user => `
                <div class="user-card">
                    <h3>${user.username}</h3>
                    <p>Id: ${user.id}</p>
                    <p>Email: ${user.email}</p>
                    <p>Telefone: ${user.phone}</p>
                    <p>Endereço: ${user.address.street}, ${user.address.city}</p>
                </div>
            `).join('');
        })
        .catch(error => console.error('Erro ao ordenar resultados:', error))
        .finally(() => {
            button.disabled = false;
            button.classList.remove('loading');
        });
}
