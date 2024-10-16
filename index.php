<?php
session_start();

// Verifica se já está logado, caso esteja redireciona para menu.php
if (isset($_SESSION['nome'])) {
    header("Location: menu.php");
    exit;
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    // Simulação de autenticação (substitua pelo código de verificação com o banco de dados)
    $usuario_valido = "admin";
    $senha_valida = "1234";

    if ($usuario === $usuario_valido && $senha === $senha_valida) {
        // Login bem-sucedido, armazena o nome na sessão e redireciona
        $_SESSION['nome'] = $usuario;
        header("Location: menu.php");
        exit;
    } else {
        // Login falhou, define a mensagem de erro
        $_SESSION['msg'] = "Usuário ou senha inválidos.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Exibe mensagem de erro se houver
if (isset($_SESSION['msg'])) {
    echo "<p style='color: red;'>" . $_SESSION['msg'] . "</p>";
    unset($_SESSION['msg']); // Limpa a mensagem após exibi-la
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST" action=""> <!-- Ação do formulário para o próprio script -->
        <label for="usuario">Usuário:</label>
        <input type="text" id="usuario" name="usuario" required>
        <br>
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
        <br>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
