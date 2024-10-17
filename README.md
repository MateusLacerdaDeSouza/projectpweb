# Sistema de Gerenciamento de Bebidas

Este é um sistema simples para gerenciar bebidas, permitindo a adição e edição de bebidas e a criação de usuários.

## Estrutura do Projeto

CreatePhp/ │ ├── db_connection.php # Conexão com o banco de dados ├── init.php # Script para inicializar o banco de dados ├── menu.php # Página principal do sistema ├── edit_bebida.php # Página para editar bebidas ├── delete_bebida.php # Página para excluir bebidas └── login.php # Página de login

markdown
Copy code

## Pré-requisitos

- **Servidor Web:** É necessário ter um servidor web (como Apache) instalado. Você pode usar o [XAMPP](https://www.apachefriends.org/index.html) ou [MAMP](https://www.mamp.info/en/) para facilitar a configuração.
- **Banco de Dados:** O sistema utiliza MySQL como banco de dados.

## Instruções de Instalação

1. **Baixe e Instale o XAMPP:**
   - Se ainda não tiver o XAMPP, baixe e instale a partir do [site oficial](https://www.apachefriends.org/index.html).

2. **Coloque o Projeto no Diretório `htdocs`:**
   - Copie a pasta `CreatePhp` para o diretório `htdocs` do XAMPP.
   - O caminho geralmente é: `C:\xampp\htdocs\CreatePhp` (Windows) ou `/Applications/XAMPP/htdocs/CreatePhp` (macOS).

3. **Inicie o Servidor:**
   - Abra o painel de controle do XAMPP e inicie os módulos **Apache** e **MySQL**.

4. **Criar o Banco de Dados:**
   - Acesse o `phpMyAdmin` através do seu navegador (geralmente em `http://localhost/phpmyadmin`).
   - Crie um novo banco de dados (por exemplo, `armazem_bebidas`):
     ```sql
     CREATE DATABASE armazem_bebidas;
     USE armazem_bebidas;

     CREATE TABLE usuario (
         id INT PRIMARY KEY AUTO_INCREMENT,
         nome VARCHAR(50),
         login VARCHAR(25),
         senha VARCHAR(40)
     );

     CREATE TABLE bebidas (
         id INT PRIMARY KEY AUTO_INCREMENT,
         nome VARCHAR(40) NOT NULL,
         tipo VARCHAR(100),
         price DECIMAL(10,2),
         usuario_id INT,
         descricao TEXT,
         FOREIGN KEY (usuario_id) REFERENCES usuario(id) ON DELETE CASCADE
     );
     ```

5. **Configuração da Conexão:**
   - No arquivo `db_connection.php`, configure suas credenciais de banco de dados:
     ```php
     $servername = "localhost";
     $username = "root"; // padrão do XAMPP
     $password = ""; // padrão do XAMPP (geralmente está vazio)
     $dbname = "armazem_bebidas"; // nome do banco de dados que você criou
     ```

6. **Inicialização do Banco de Dados:**
   - Acesse o script de inicialização através do navegador em `http://localhost/CreatePhp/init.php`.
   - Este script criará as tabelas `usuario` e `bebida` e inserirá um usuário padrão (`admin`) e 10 bebidas no banco de dados.

## Acesso ao Sistema

- Após a inicialização, você pode acessar o sistema através da página principal em `http://localhost/CreatePhp/menu.php`.

## Funcionalidades

- **Cadastro de Bebidas:** Adicione novas bebidas ao sistema.
- **Edição de Bebidas:** Edite informações sobre bebidas já cadastradas.
- **Exclusão de Bebidas:** Exclua bebidas do sistema.
- **Login:** Acesse o sistema com o usuário admin.