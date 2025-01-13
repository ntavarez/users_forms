
# Cadastro de usuários

O sistema permite que o usuário preencha um formulário de cadastro com suas informações. Após o cadastro, o usuário é direcionado a uma página que lista em uma tabela todos os usuários cadastrados.

## Instalação

Esse projeto foi desenvolvido no Windows e com o uso do Xampp para execução do PHP, MySQL e Apache. Então, se você é usuário Windows e a menos que você já tenha tudo isso já instalado na máquina, é preciso que você faça o download, instalação e inicialização do Xampp. Para esse projeto, o xampp foi instalado dentro da pasta (criada) Temp, no disco C:.

**Importante:** Caso esteja utilizando o Xampp, o clone do projeto do Github precisa ser feito dentro da pasta /htdocs do Xampp.

```bash
  cd C:\Temp\xampp\htdocs
  git clone https://github.com/ntavarez/users_forms.git [nome-da-pasta-do-projeto]
```
Pronto! As páginas HTML do projeto poderão ser acessadas no navegador via endereço http://127.0.0.1/[nome-da-pasta-do-projeto]/views/[nome-do-arquivo].html.

Para acessar o banco de dados no Xampp, basta clicar no botão Admin do MySQL, conforme imagem abaixo:





## Funcionamento do sistema: um panorama geral

O usuário irá preencher os respectivos campos do formulário (Nome, E-mail e Senha), ao clicar no botão Enviar, os dados serão enviados para o banco de dados.

Importante: Algumas validações são realizadas durante o preenchimento do formulário:
- Não pode ter o mesmo e-mail cadastrado duas vezes no sistema, então campo de E-mail do formulário verifica se o e-mail digitado já não existe no banco de dados. Caso um e-mail já cadastrado seja informado no campo, o usuário será notificado.
- A senha informada no campo precisa atender ao requisito de ter 6 ou mais caracteres. Caso uma senha menor que 6 caracteres seja informada no campo, o usuário será notificado.

Se atender todos os requisitos para o cadastro, o usuário será redirecionado para outra página onde é possível visualizar uma tabela com três colunas: Nome, E-mail e Ações. Essa tabela conterá Nome e E-mail de todos os usuários cadastrados no banco de dados, e na coluna Ações haverá dois botões (Editar e Excluir) para cada usuário listado.


### Listagem de usuários: botão Editar
Quando o usuário escolher um usuário para ser editado, irá clicar no botão Editar da linha correspondente àquele usuário.

Ao clicar, a tabela entrará em modo edição, os botões ficarão desativados, e o usuário será perguntado sobre qual coluna ele deseja realizar a alteração, oferecendo duas opções: digitar 1 para alterar a coluna Nome ou 2 para alterar a coluna E-mail.

Importante: Se o usuário digitar uma opção que não seja 1 e nem seja 2, o prompt identificará como opção inválida.

Após selecionar a opção, o campo correspondente à coluna se tornará um input para o usuário modificar o valor atual. A alteração poderá ser salva quando o usuário pressionar a tecla Enter.

O mesmo comportamento ocorrerá ao selecionar a coluna E-mail para alteração, com o diferencial de que ao digitar o novo e-mail no campo, será feita a validação se o e-mail digitado já não existe no banco de dados. Caso já exista, o valor não será salvo, mas o campo continuará em modo edição para o usuário inserir outro valor.
### Listagem de usuários: botão Excluir
Quando o usuário clicar no botão Excluir, receberá uma mensagem de confirmação do navegador perguntando se quer realmente excluir aquele usuário. 

Caso o usuário prossiga com a solicitação de exclusão, o usuário selecionado será excluído do banco de dados, e respectivamente, da listagem da página.
## Documentação da API: Fetch API
Todas as requisições do sistema foram feitas utilizando o Fetch API do Javascript. Trechos do código e para que foram utilizados serão vistos abaixo.

### Requisição GET
O método GET foi acionado algumas vezes durante o código:

  **1. Para validar o campo de e-mail do formulário de cadastro.**

```http
fetch(`../controllers/app.php?email=${encodeURIComponent(email)}`, {
      method: 'GET',
  })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              alert('E-mail já existente! Favor cadastrar outro e-mail.')
          }
      })
      .catch(error => {
          console.error('Erro:', error);
          alert('Erro ao pesquisar e-mail!');
      });
```

**Objetivo:** obter o endereço de e-mail do banco de dados para saber se o valor é correspondente ou não ao que o usuário informou no campo de e-mail do formulário de cadastro.

  **2. Gerar tabela dinâmica (listagem de usuários).**

```http
fetch('../models/loadList.php', {
        method: 'GET',
    })
        .then(response => response.json())
        .then(data => {...
        });
    });
```
**Objetivo:** Obter os dados de todos os usuários do banco de dados para gerar a listagem na página seguinte ao cadastro de usuários.

  **3. Excluir usuário da lista.**

```http
fetch(`../controllers/app.php?email=${encodeURIComponent(email)}`, {
      method: 'GET',
})
        .then(response => response.json())
          .then(data => {
            if (data.success) {
                alert('E-mail já existente! Favor cadastrar outro e-mail.')
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao pesquisar e-mail!');
        });
```
**Objetivo:** obter o ID do usuário para solicitar a exclusão do banco de dados.

### Requisição POST
O método POST foi acionado algumas vezes também, mais precisamente em todos os momentos que foi necessário enviar dados do navegador para o banco de dados.
 
   **1. Para enviar dados inseridos no formulário.**

```http
fetch('../controllers/app.php', {
      method: 'POST',
      body: userData
})
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Dados cadastrados com sucesso!');
                window.location.href = 'userList.html';
            } else {
                alert('Dados não cadastrados! É possível que o e-mail já esteja em uso e/ou a senha tenha menos de 6 dígitos.');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao enviar os dados!');
        });
```

**Objetivo:** enviar os dados válidos digitados no formulário para serem gravados no banco de dados. É possível identificar a variável correspondente aos dados no body da requisição (userData).

   **2. Enviar as alterações feitas em um usuário da listagem.**

```http
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
```

**Objetivo:** enviar os dados alterados de um usuário para serem atualizado também no banco de dados.




## Aprendizados

Sem dúvidas, não sou a mesma pessoa que era antes de começar esse projeto. Desenvolver esse sistema colocou não apenas meus conhecimentos de desenvolvimento web à prova, como também me deu a oportunidade de aprender muito, não só durante as pesquisas, vendo as diversas formas de executar a mesma ação, como com os erros (sempre!) de execução de ideias e também de escrita de código.


## Stack utilizada

**Front-end:** Javascript, CSS

**Back-end:** PHP

**Banco de Dados:** MySQL


