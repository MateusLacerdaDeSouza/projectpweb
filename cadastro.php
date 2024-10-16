<?php
session_start();
require 'C:\xampp\htdocs\CreatePhp\db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nome']) && isset($_POST['login']) && isset($_POST['senha'])) {
        $nome = $_POST['nome'];
        $login = $_POST['login'];
        $senha = $_POST['senha']; // Senha sem hash (não recomendado)

        try {
            // Preparar a instrução SQL
            $stmt = $conn->prepare("INSERT INTO usuario (nome, login, senha) VALUES (:nome, :login, :senha)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':senha', $senha);
            
            // Executar a instrução
            if ($stmt->execute()) {
                $_SESSION['msg'] = "Usuário cadastrado com sucesso!";
                // Limpar os campos após o cadastro bem-sucedido
                $nome = $login = $senha = '';
                header("Location: menu.php");
                exit; // Adicionando exit após o redirecionamento
            } else {
                $_SESSION['msg'] = "Erro ao cadastrar o usuário.";
            }
        } catch (PDOException $e) {
            $_SESSION['msg'] = "Erro: " . $e->getMessage();
        }
    } else {
        $_SESSION['msg'] = "Preencha todos os campos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Cadastrar Usuário</h2>
        
        <!-- Exibe mensagem de sessão, se existir -->
        <?php if (isset($_SESSION['msg'])): ?>
            <div class="alert alert-warning text-center"><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></div>
        <?php endif; ?>
        
        <!-- Formulário de cadastro -->
        <form method="post" action="">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($nome ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="login" class="form-label">Login:</label>
                <input type="text" class="form-control" id="login" name="login" value="<?= htmlspecialchars($login ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" value="<?= htmlspecialchars($senha ?? '') ?>" required>
            </div>
            
            <!-- Centralizando apenas os botões -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Cadastrar</button>
            </div>
        </form>
        
        <!-- Botão para ir para a página de login -->
        <div class="mt-3 text-center">
            <a href="login.php" class="btn btn-secondary">Login</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
