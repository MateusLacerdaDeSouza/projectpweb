<?php
session_start();
require 'C:\xampp\htdocs\CreatePhp\db_connection.php'; // Certifique-se de que o caminho está correto

// Mensagem de erro (se houver)
if (isset($_SESSION['msg'])) {
    echo '<div class="alert alert-warning">' . $_SESSION['msg'] . '</div>';
    unset($_SESSION['msg']);
}

// Verifica se o usuário já está logado
if (isset($_SESSION['usuario_id'])) {
    header("Location: menu.php"); // Redireciona para a página do menu se já estiver logado
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Página de Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Login</h2>
        <form action="processa_login.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">Nome de Usuário</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Entrar</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
