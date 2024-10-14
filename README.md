# TODO ✏️
- Relatórios PDF, criar um com carater de estastitica, mostrando a porcetagem de resposta para cada opção e para as respostas livre mostrat toas de uma vez.
(não precisa identificar no relatório o nome do aluno)
- Mudar migration e regra de negocio pra não ser obrigatório se identificar para responder os formularios. Deixar um check box na criação do formulario para o professor decidir se será anonimo ou não o formulário. Se for ai torna obrigatório prencher o nome para responder
- Melhorar tela de visitante. 1 - corrigir bug de paginação em pagina que não existe. 2 - botão de salvar some e no lugar deixar o de "Salver e ir pro próximo", ao paginar até a ultima questão ter botão de "revisar antes de enviar" aonde levará para uma tela de revisão aonde o usuario poderá rescvrever as respostas. Só assim poderá enviar as resposstas

# Sistema de Gestão de Formulários Acadêmicos 📋

Este é um projeto desenvolvido como parte de uma extensão da **Universidade Estadual de Mato Grosso do Sul (UEMS)**. O objetivo é criar um sistema de gerenciamento de formulários acadêmicos, permitindo que professores criem e avaliem questionários com diferentes tipos de perguntas (múltipla escolha e dissertativas). O sistema foi pensado para ser utilizado em ambientes sem conexão com a internet, rodando em **intranet**, sendo uma solução ideal para atividades extensionistas em regiões com acesso limitado à internet.

## Visão Geral 🌐

Este sistema foi criado para permitir que professores de instituições de ensino gerenciem formulários acadêmicos de maneira fácil e intuitiva, com foco no uso offline. O sistema foi desenvolvido em **Laravel** para o backend e utiliza **PostgreSQL** como banco de dados principal.

## Tecnologias Utilizadas 🛠️

- **Laravel 8**: Framework PHP para o desenvolvimento do backend.
- **Bootstrap 5**: Framework CSS para estilização e responsividade.
- **PostgreSQL**: Banco de dados relacional utilizado para armazenar as informações.
- **Composer**: Gerenciador de dependências do PHP.

## Instalação e Configuração ⚙️

Siga os passos abaixo para instalar o projeto localmente:

### Pré-requisitos

- **PHP >= 7.4**
- **Composer**
- **Node.js e NPM**
- **PostgreSQL**

### Passos para Instalação

1. Clone o repositório:
   ```bash
   git clone git@github.com:MoriHiroshi0619/formularios-offline.git
   cd formularios-offline
2. Instale as dependências do PHP e JavaScript:
   ```bash
   composer install
   npm install  
3. Crie um arquivo `.env`:
   ```bash
   cp .env.example .env
4. Configure as variáveis de ambiente no arquivo `.env` para o banco de dados:
   ```bash
    DB_CONNECTION=pgsql
    DB_HOST=127.0.0.1
    DB_PORT=5432
    DB_DATABASE=nome_do_banco
    DB_USERNAME=usuario
    DB_PASSWORD=senha
5. Gere a chave da aplicação Laravel:
   ```bash
   php artisan key:generate  
6. Execute as migrações
   ```bash
   php artisan migrate
7. (Opcional) Rode o seeder para criar dados fictícios:
   ```bash
   php artisan db:seed
8. Compile os assets do frontend:
   ```bash
   npm run dev
9. Inicie o servidor local: 
   ```bash
   php artisan serve
#### Projeto desenvolvido como parte de uma iniciativa acadêmica da Universidade Estadual de Mato Grosso do Sul (UEMS)








