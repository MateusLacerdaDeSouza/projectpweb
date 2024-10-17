<?php
session_start();
require 'C:\xampp\htdocs\CreatePhp\db_connection.php';

// Verifique se o método é POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['nome'], $_POST['tipo'], $_POST['price'], $_POST['descricao'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $tipo = $_POST['tipo'];
    $price = $_POST['price'];
    $descricao = $_POST['descricao'];

    try {
        // Preparar a instrução SQL para atualizar a bebida
        $stmt = $conn->prepare("UPDATE bebidas SET nome = :nome_b, tipo = :tipo, price = :price, descricao = :descricao WHERE id = :id");
        $stmt->bindParam(':nome_b', $nome);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':id', $id);

        // Executar a instrução
        if ($stmt->execute()) {
            $_SESSION['msg'] = "Bebida atualizada com sucesso!";
        } else {
            $_SESSION['msg'] = "Erro ao atualizar a bebida.";
        }
    } catch (PDOException $e) {
        $_SESSION['msg'] = "Erro: " . $e->getMessage();
    }
}

header("Location: menu.php");
exit;
