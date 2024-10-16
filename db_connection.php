<?php
$host = 'localhost';
$db = 'armazem_bebidas';
$user = 'root';
$password = '';


try{
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user,$password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexão bem-sucedida!";
} catch (PDOException $e) {

    echo "Erro na conexão " . $e->getMessage();
}

?>