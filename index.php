<?php
session_start();

// Verifica se já está logado, caso esteja redireciona para menu.php
if (isset($_SESSION['nome'])) {
    header("Location: menu.php");
    exit;
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
    <form method="POST" action="login_process.php"> <!-- Ação do formulário -->
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
