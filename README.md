# Sistema de Gestão de Formulários Acadêmicos 📋

Este é um projeto desenvolvido como parte de uma extensão da **Universidade Estadual de Mato Grosso do Sul (UEMS)**. O objetivo é criar um sistema de gerenciamento de formulários acadêmicos, permitindo que professores criem e avaliem questionários com diferentes tipos de perguntas (múltipla escolha e dissertativas). O sistema foi pensado para ser utilizado em ambientes sem conexão com a internet, rodando em **intranet**, sendo uma solução ideal para atividades extensionistas em regiões com acesso limitado à internet.

## Visão Geral 🌐

Este sistema foi criado para permitir que professores de instituições de ensino gerenciem formulários acadêmicos de maneira fácil e intuitiva, com foco no uso offline. O sistema foi desenvolvido em **Laravel** para o backend e utiliza **PostgreSQL** como banco de dados principal.

## Tecnologias Utilizadas 🛠️

- **Laravel 8**: Framework PHP para o desenvolvimento do backend.
- **Bootstrap 5**: Framework CSS para estilização e responsividade.
- **PostgreSQL 16.4**: Banco de dados relacional utilizado para armazenar as informações.
- **Composer**: Gerenciador de dependências do PHP.
- **Node.js**: Ambiente de execução JavaScript para o frontend.
- **Docker**: Plataforma para criação e gerenciamento de contêineres.
- **Docker Compose**: Ferramenta para definir e executar aplicativos Docker multi-contêiner.

## Instalação e Configuração ⚙️

Siga os passos abaixo para instalar o projeto localmente usando Docker:

### Pré-requisitos

- **Docker** instalado na máquina.
- **Docker Compose** instalado.
- **Git** para clonar o repositório.

> **Nota:** Certifique-se de que o Docker e o Docker Compose estão instalados e funcionando corretamente em seu sistema antes de iniciar. Para instalar o Docker, acesse [Docker Desktop](https://www.docker.com/products/docker-desktop) ou [Docker Engine](https://docs.docker.com/engine/install/), dependendo do seu sistema operacional.

### Passos para Instalação

1. **Clone o repositório:**

   ```bash
   git clone https://github.com/MoriHiroshi0619/formularios-offline.git
   cd formularios-offline

2. Crie um arquivo `.env`:
   ```bash
   cp .env.example .env
   
3. Inicie os contêineres Docker:
   ```bash
    docker-compose up --build
   
4. Instale as dependências do PHP e do Node.js:
   ```bash
   docker-compose exec app composer install
   docker-compose exec app npm install
   
5. Gere a chave da aplicação Laravel:
   ```bash
   docker-compose exec app php artisan key:generate
   
6. Execute as migrações do banco de dados:
   ```bash
   docker-compose exec app php artisan migrate

7. Compile os assets do frontend:
   ```bash
   docker-compose exec app npm run dev

8. Crie um usuário administrador (comando solicitará o nome, CPF e senha do usuário):
   ```bash
   docker-compose exec app php artisan user:admin-custom

9. Acesse a aplicação no navegador:
   ```bash
   http://localhost:8080

### Observações 📝
- Dependências: Todo o ambiente necessário para executar o projeto está encapsulado em contêineres Docker. Não é necessário instalar PHP, Composer, Node.js ou PostgreSQL na sua máquina local.
- Volumes Persistentes: Os dados do banco de dados são persistidos usando volumes do Docker, garantindo que as informações não sejam perdidas ao reiniciar os contêineres.

#### Comandos Úteis:
1. Acessar o contêiner do aplicativo:
   ```bash
   docker-compose exec app bash
2. Visualizar logs:
   ```bash
   docker-compose logs -f 

#### Projeto desenvolvido como parte de uma iniciativa acadêmica da Universidade Estadual de Mato Grosso do Sul (UEMS)








