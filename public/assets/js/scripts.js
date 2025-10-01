document.addEventListener('DOMContentLoaded', () => {

    const route = currentRoute;

    // Carregar scripts específicos com base na rota
    switch (route) {
        case 'users.index':
            console.log('Scripts da página de users.index carregados.');
            initUsersPage(); // Função específica para users.index
            break;

        case 'users.show':
            console.log('Scripts da página users.show carregados.');
            initUsersShowPage(); // Função específica para users.show
            break;

        case 'users.create':
            console.log('Scripts da página users.create carregados.');
            initUsersCreatePage(); // Função específica para users.show
            break;

        case 'users.edit':
            console.log('Scripts da página users.edit carregados.');
            initUsersEditPage(); // Função específica para users.show
            break;

        case 'profile.edit':
            console.log('Scripts da página profile.edit carregados.');
            initProfileEditPage(); // Função específica para users.show
            break;

        default:
            console.log('Nenhum script específico para esta rota.');
            break;
    }

});

// Funções específicas por página
function initUsersPage() {
    document.addEventListener('DOMContentLoaded', () => {
        const usersTableBody = document.getElementById('usersTableBody');

        // Botões de ordenação
        const sortName = document.getElementById('sortName');
        const sortCreated = document.getElementById('sortCreated');

        let nameOrder = 'asc'; // Estado inicial da ordenação por nome

        // Função para buscar e atualizar os usuários
        function fetchUsers(sort) {
            fetch(`/users/filter?sort=${sort}`)
                .then(response => response.json())
                .then(data => {
                    usersTableBody.innerHTML = ''; // Limpa a tabela
                    data.forEach(user => {
                        const telefone = user.celular.replace(/\+|\s/g, '');
                        const row = `
                            <div class="row row-cols-12 mb-md-0 mb-4 bg-linha">
                                <div class="col-md-4 py-2 col-12 border-bottom d-flex align-items-center text-uppercase small fw-bold">
                                    <a class="text-primary" href="/users/${user.id}">${user.nome}</a>
                                    &nbsp;<span class="material-symbols-outlined text-vermelho symbol-filled h6 m-0">error</span>
                                </div>
                                <div class="col-md-3 py-2 col-12 border-bottom d-flex align-items-center small">
                                    <a class="text-primary" target="_blank" href="https://wa.me/${telefone}">${user.celular}</a>
                                </div>
                                <div class="col-md-3 py-2 col-12 border-bottom d-flex align-items-center small">
                                    <a class="text-primary" href="mailto:${user.email}">${user.email}</a>
                                </div>
                                <div class="col-md-2 py-2 col-12 border-bottom d-flex align-items-center small justify-content-center">
                                    <a href="/contributions/create/${user.id}" class="text-secondary px-3 border-end">Contribuições</a>
                                    <a href="/users/${user.id}/edit" class="text-secondary px-3">Editar</a>
                                </div>
                            </div>
                        `;
                        usersTableBody.innerHTML += row;
                    });
                })
                .catch(error => console.error('Erro ao buscar usuários:', error));
        }

        // Event Listener para ordenação por nome
        sortName.addEventListener('click', () => {
            nameOrder = nameOrder === 'asc' ? 'desc' : 'asc';
            fetchUsers(`name_${nameOrder}`);
        });

        // Event Listener para ordenação por cadastro
        sortCreated.addEventListener('click', () => {
            fetchUsers('created_at');
        });
    });
}

function initUsersCreatePage() {

    $(function() {
        // Elementos do formulário
        const phoneInput = document.getElementById('telephone');
        // Inicializar máscara com valor padrão
        IMask(phoneInput, {
            mask: '+55(00)00000-0000'
          });
    });

}

function initUsersEditPage() {
    $(function() {
        // Elementos do formulário
        const phoneInput = document.getElementById('telephone');
        // Inicializar máscara com valor padrão
        IMask(phoneInput, {
            mask: '+55(00)00000-0000'
          });
    });
}

function initProfileEditPage() {
    $(function() {
        // Elementos do formulário
        const phoneInput = document.getElementById('telephone');
        // Inicializar máscara com valor padrão
        IMask(phoneInput, {
            mask: '+55(00)00000-0000'
          });
    });
}
