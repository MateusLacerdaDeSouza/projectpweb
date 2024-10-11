<?php
session_start();

if(isset($_POST['usuario'])){
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];


    //consulta bd
    if ($usuario == 'teste' && $senha == 'teste'){

        $_SESSION['nome'] = 'NOME';
    } else {
        $_SESSION['msg'] = "Usuário ou senha incorretos!!!";
        header("Location: index.php");
        exit;
    }

}else if (!isset($_SESSION['nome'])) {
    header("Location: index.php");
    exit;
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Página de Menu</title>
    </head>
</html>