<?php
session_start();
require 'C:\xampp\htdocs\CreatePhp\db_connection.php'; // Certifique-se de que o caminho está correto

// Verifica se o usuário já está logado
if (isset($_SESSION['usuario_id'])) {
    header("Location: menu.php"); // Redireciona para a página do menu se já estiver logado
    exit;
}

// Processa o login se o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta para verificar o usuário no banco de dados
    $query = "SELECT id, senha FROM usuario WHERE login = :username"; // Corrigido aqui
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Verifica se o usuário foi encontrado
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica a senha diretamente (sem hash)
        if ($password === $user['senha']) {
            // Login bem-sucedido
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['nome_usuario'] = $username;
            header("Location: menu.php");
            exit;
        } else {
            $_SESSION['msg'] = "Senha incorreta.";
        }
    } else {
        $_SESSION['msg'] = "Usuário não encontrado.";
    }

    // Redireciona de volta para o formulário
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Mensagem de erro (se houver)
if (isset($_SESSION['msg'])) {
    echo '<div class="alert alert-warning">' . $_SESSION['msg'] . '</div>';
    unset($_SESSION['msg']);
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
        <form action="" method="post">
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
