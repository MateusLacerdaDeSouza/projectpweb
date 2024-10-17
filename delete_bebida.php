<?php
session_start();
require 'C:\xampp\htdocs\CreatePhp\db_connection.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    try {
        $stmt = $conn->prepare("DELETE FROM bebidas WHERE id = :id AND usuario_id = :usuario_id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':usuario_id', $_SESSION['usuario_id']);
        if ($stmt->execute()) {
            $_SESSION['msg'] = "Bebida excluída com sucesso!";
        } else {
            $_SESSION['msg'] = "Erro ao excluir a bebida.";
        }
    } catch (PDOException $e) {
        $_SESSION['msg'] = "Erro: " . $e->getMessage();
    }
}

header("Location: menu.php");
exit;
