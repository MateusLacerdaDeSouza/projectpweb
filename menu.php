<?php
session_start();
require 'C:\xampp\htdocs\CreatePhp\db_connection.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    // Redireciona para a página de login se o usuário não estiver logado
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nome_bebida'], $_POST['tipo'], $_POST['descricao'])) {
        $nome_bebida = $_POST['nome_bebida'];
        $tipo = $_POST['tipo'];
        $descricao = $_POST['descricao'];

        // Obtém o ID do usuário logado
        $usuario_id = $_SESSION['usuario_id'];

        try {
            // Preparar a instrução SQL para adicionar a bebida
            $stmt = $conn->prepare("INSERT INTO bebidas (nome, tipo, descricao, usuario_id) VALUES (:nome_bebida, :tipo, :descricao, :usuario_id)");
            $stmt->bindParam(':nome_bebida', $nome_bebida);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':usuario_id', $usuario_id); // Usa o ID do usuário logado
            
            // Executar a instrução
            if ($stmt->execute()) {
                $_SESSION['msg'] = "Bebida adicionada com sucesso!";
                header("Location: menu.php");
                exit;
            } else {
                $_SESSION['msg'] = "Erro ao adicionar a bebida.";
            }
        } catch (PDOException $e) {
            $_SESSION['msg'] = "Erro: " . $e->getMessage();
        }
    } else {
        $_SESSION['msg'] = "Preencha todos os campos!";
    }
}

// Consulta as bebidas do usuário logado
$usuario_id = $_SESSION['usuario_id']; // Obtem o ID do usuario logado
$stmt = $conn->prepare("SELECT b.nome, b.tipo, b.descricao FROM bebidas b WHERE b.usuario_id = :usuario_id");
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->execute();
$bebidas = $stmt->fetchAll(PDO::FETCH_ASSOC); // Armazena o resultado na variável $bebidas

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Bebidas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Listar Bebidas</h2>
        <h5>Bem-vindo, <?= htmlspecialchars($_SESSION['nome']); ?></h5>

        <!-- Botão para abrir o modal de adicionar bebida -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addBeverageModal">
            Adicionar Bebida
        </button>

        <!-- Tabela de bebidas -->
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome do Usuário</th>
                    <th>Nome da Bebida</th>
                    <th>Tipo</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bebidas as $index => $bebida): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($_SESSION['nome']) ?></td> <!-- Nome do usuário -->
                        <td><?= htmlspecialchars($bebida['nome']) ?></td>
                        <td><?= htmlspecialchars($bebida['tipo']) ?></td>
                        <td><?= htmlspecialchars($bebida['descricao']) ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm">Editar</button>
                            <button class="btn btn-danger btn-sm">Excluir</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Modal para adicionar bebida -->
        <div class="modal fade" id="addBeverageModal" tabindex="-1" aria-labelledby="addBeverageModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addBeverageModalLabel">Adicionar Bebida</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="nome_bebida" class="form-label">Nome da Bebida</label>
                                <input type="text" class="form-control" id="nome_bebida" name="nome_bebida" required>
                            </div>
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo</label>
                                <input type="text" class="form-control" id="tipo" name="tipo" required>
                            </div>
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary" name="add_beverage">Adicionar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
