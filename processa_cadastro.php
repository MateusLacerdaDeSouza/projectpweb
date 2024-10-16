<?php 
session_start();

require 'C:\xampp\htdocs\CreatePhp\db_connection.php';

if(isset($_POST['usuario']) && isset($_POST['senha']) && isset($_POST['nome'])) {

    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];
    $nome = $_POST['nome'];

    try {
        // Verifica se o usuário já existe
        $stmt = $conn->prepare("SELECT COUNT(*) FROM usuario WHERE login = :usuario");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            $_SESSION['msg'] = "Usuário já existe!";
            header("Location: cadastro.php");
            exit;
        }
        
        // Insere o usuário no banco de dados sem usar hash na senha
        $stmt = $conn->prepare("INSERT INTO usuario (login, senha, nome) VALUES (:usuario, :senha, :nome)");
        $stmt->bindParam(':usuario', $usuario);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':nome', $nome);

        if ($stmt->execute()) {
            $_SESSION['msg'] = "Cadastro realizado com sucesso!!";
            header("Location: index.php");
            exit;
        } else {
            $_SESSION['msg'] = "Erro ao cadastrar usuário!";
            header("Location: cadastro.php");
            exit;
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
        header("Location: cadastro.php"); // Redireciona em caso de erro
        exit;
    }

} else {
    $_SESSION['msg'] = "Preencha todos os campos!!!";
    header("Location: cadastro.php");
    exit;
}
