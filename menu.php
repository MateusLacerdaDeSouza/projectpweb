<?php
session_start();
require 'C:\xampp\htdocs\CreatePhp\db_connection.php';

// Verifique se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

// Armazene o ID do usuário logado
$usuario_id = $_SESSION['usuario_id'];

// Processar a adição da bebida
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nome'], $_POST['tipo'], $_POST['price'], $_POST['descricao'])) {
        $nome = $_POST['nome'];
        $tipo = $_POST['tipo'];
        $price = $_POST['price'];
        $descricao = $_POST['descricao'];

        try {
            // Preparar e executar a inserção da bebida
            $stmt = $conn->prepare("INSERT INTO bebidas (nome, tipo, price, descricao, usuario_id) VALUES (:nome, :tipo, :price, :descricao, :usuario_id)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':tipo', $tipo);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':usuario_id', $usuario_id);

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

// Consulta para listar bebidas do usuário logado
$stmt = $conn->prepare("
    SELECT b.id, b.nome AS nome_bebida, u.nome AS nome_usuario, b.tipo, b.price, b.descricao
    FROM bebidas b
    JOIN usuario u ON b.usuario_id = u.id
    WHERE b.usuario_id = :usuario_id
");
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->execute();
$bebidas = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <h5>Bem-vindo, <?= htmlspecialchars($_SESSION['nome_usuario']); ?></h5>

        <!-- Exibir mensagem de erro/sucesso -->
        <?php if (isset($_SESSION['msg'])): ?>
            <div class="alert alert-warning">
                <?= htmlspecialchars($_SESSION['msg']); ?>
            </div>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

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
                    <th>Preço</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bebidas as $index => $bebida): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($bebida['nome_usuario']) ?></td>
                        <td><?= htmlspecialchars($bebida['nome_bebida']) ?></td>
                        <td><?= htmlspecialchars($bebida['tipo']) ?></td>
                        <td><?= htmlspecialchars($bebida['price']) ?></td>
                        <td><?= htmlspecialchars($bebida['descricao']) ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editBeverageModal-<?= $bebida['id']; ?>">Editar</button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteBeverageModal-<?= $bebida['id']; ?>">Excluir</button>
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
                        <form method="post">
                            <div class="mb-3">
                                <label for="nome_bebida" class="form-label">Nome da Bebida</label>
                                <input type="text" class="form-control" id="nome_bebida" name="nome" required>
                            </div>
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo</label>
                                <input type="text" class="form-control" id="tipo" name="tipo" required>
                            </div>
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Preço</label>
                                <input type="text" class="form-control" id="price" name="price" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Adicionar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para editar e excluir bebidas (um para cada bebida) -->
        <?php foreach ($bebidas as $bebida): ?>
            <!-- Modal para editar bebida -->
            <div class="modal fade" id="editBeverageModal-<?= $bebida['id']; ?>" tabindex="-1" aria-labelledby="editBeverageModalLabel-<?= $bebida['id']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editBeverageModalLabel-<?= $bebida['id']; ?>">Editar Bebida</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post" action="edit_bebida.php">
                                <input type="hidden" name="id" value="<?= $bebida['id']; ?>">
                                <div class="mb-3">
                                    <label for="nome_bebida_<?= $bebida['id']; ?>" class="form-label">Nome da Bebida</label>
                                    <input type="text" class="form-control" id="nome_bebida_<?= $bebida['id']; ?>" name="nome" value="<?= htmlspecialchars($bebida['nome_bebida']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="tipo_<?= $bebida['id']; ?>" class="form-label">Tipo</label>
                                    <input type="text" class="form-control" id="tipo_<?= $bebida['id']; ?>" name="tipo" value="<?= htmlspecialchars($bebida['tipo']); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="descricao_<?= $bebida['id']; ?>" class="form-label">Descrição</label>
                                    <textarea class="form-control" id="descricao_<?= $bebida['id']; ?>" name="descricao" required><?= htmlspecialchars($bebida['descricao']); ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="price_<?= $bebida['id']; ?>" class="form-label">Preço</label>
                                    <input type="text" class="form-control" id="price_<?= $bebida['id']; ?>" name="price" value="<?= htmlspecialchars($bebida['price']); ?>" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para excluir bebida -->
            <div class="modal fade" id="deleteBeverageModal-<?= $bebida['id']; ?>" tabindex="-1" aria-labelledby="deleteBeverageModalLabel-<?= $bebida['id']; ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteBeverageModalLabel-<?= $bebida['id']; ?>">Excluir Bebida</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Tem certeza de que deseja excluir a bebida "<?= htmlspecialchars($bebida['nome_bebida']); ?>"?</p>
                            <form method="post" action="delete_bebida.php">
                                <input type="hidden" name="id" value="<?= $bebida['id']; ?>">
                                <button type="submit" class="btn btn-danger">Excluir</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
