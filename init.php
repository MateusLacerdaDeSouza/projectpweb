<?php
require 'C:\xampp\htdocs\CreatePhp\db_connection.php'; 

// Adicionando usuário admin
$admin_nome = "Admin";
$admin_login = "admin";
$admin_senha = "admin123";

try {
    // Inserindo o usuário admin
    $sql_usuario = "INSERT INTO usuario (nome, login, senha) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql_usuario);
    $stmt->execute([$admin_nome, $admin_login, $admin_senha]);

    // Adicionando 10 bebidas
    $bebidas = [
        ["Cerveja", "cerveja", 5.00, "Cerveja gelada"],
        ["Refrigerante", "refrigerante", 3.00, "Refrigerante saboroso"],
        ["Suco", "suco", 4.50, "Suco natural"],
        ["Água", "agua", 1.00, "Água mineral"],
        ["Vinho", "vinho", 20.00, "Vinho tinto"],
        ["Whisky", "whisky", 50.00, "Whisky envelhecido"],
        ["Vodka", "vodka", 30.00, "Vodka premium"],
        ["Cachaça", "cachaca", 15.00, "Cachaça artesanal"],
        ["Coquetel", "coquetel", 10.00, "Coquetel tropical"],
        ["Champanhe", "champanhe", 70.00, "Champanhe para celebrações"]
    ];

    // Preparando a inserção das bebidas
    $sql_bebida = "INSERT INTO bebidas (nome, tipo, price, descricao, usuario_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_bebida);

    $usuario_id = $conn->lastInsertId(); // ID do admin, ajuste se necessário

    foreach ($bebidas as $bebida) {
        $stmt->execute([$bebida[0], $bebida[1], $bebida[2], $bebida[3], $usuario_id]);
    }

    // Redireciona para login.php
    header("Location: login.php");
    exit(); // Certifique-se de sair após o redirecionamento

} catch (PDOException $e) {
    echo "Erro ao executar a operação: " . $e->getMessage();
}

// Fechar a conexão
$conn = null;
?>
