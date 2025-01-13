function loadUsers() {
    fetch('../models/loadList.php', {
        method: 'GET',
    })
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#userTable tbody');
            tableBody.innerHTML = '';

            data.forEach(user => {
                const row = document.createElement('tr');
                row.dataset.id = user.id;

                const cellName = document.createElement('td');
                cellName.textContent = user.nome;
                cellName.setAttribute("contenteditable", "false");

                const cellEmail = document.createElement('td');
                cellEmail.textContent = user.email;
                cellEmail.setAttribute("contenteditable", "false");

                const cellActions = document.createElement('td');
                const actionsDiv = document.createElement('div');
                actionsDiv.classList.add('actions');

                const editBtn = document.createElement('button');
                editBtn.textContent = 'Editar';
                editBtn.id = 'edit';
                editBtn.classList.add('btn', 'edit-btn');
                editBtn.onclick = () => editMode(user.id, row);
                actionsDiv.appendChild(editBtn);

                const deleteBtn = document.createElement('button');
                deleteBtn.textContent = 'Excluir';
                deleteBtn.id = 'delete';
                deleteBtn.classList.add('btn', 'delete-btn');
                deleteBtn.onclick = () => deleteUser(user.id);
                actionsDiv.appendChild(deleteBtn);

                cellActions.appendChild(actionsDiv);

                row.appendChild(cellName);
                row.appendChild(cellEmail);
                row.appendChild(cellActions);

                tableBody.appendChild(row);
            });
        });
}

function switchAllButtons(booleanOpt) {
    let buttons = document.querySelectorAll('button');
    buttons.forEach(button => button.disabled = booleanOpt);
}

function editMode(userId, row) {
    let chooseColumn = prompt("Escolha a coluna para editar: 1 (Nome), 2 (E-mail)");

    if (chooseColumn < 1 || chooseColumn > 2 || isNaN(chooseColumn)) {
        alert("Escolha inválida!");
        return;
    }

    const cell = row.querySelectorAll('td')[chooseColumn - 1];

    switchAllButtons(true);

    const currentText = cell.textContent;
    let input = document.createElement('input');
    input.value = currentText;
    cell.innerHTML = '';
    cell.appendChild(input);

    input.focus();

    input.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            saveUser(input.value, chooseColumn, userId);
        }
    });
}

function saveUser(updateInfo, edtOption, userId) {
    let userData = new FormData();

    userData.append('updateInfo', updateInfo);
    userData.append('edtOption', edtOption);
    userData.append('userId', userId);

    fetch('../controllers/userlist.php', {
        method: 'POST',
        body: userData
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadUsers();
                alert('Usuário atualizado!');
            } else {
                alert('Não foi possível atualizar o usuário! Verifique se o e-mail informado já está em uso.');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao editar informações do usuário!');
        });

}

function deleteUser(userId) {
    $confirm = confirm('Tem certeza que deseja excluir este usuário?');

    fetch(`../controllers/userlist.php?id=${userId}`, {
        method: 'GET',
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadUsers();
                alert('Usuário excluído com sucesso!');
            } else {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao excluir usuário!');
        });
}
