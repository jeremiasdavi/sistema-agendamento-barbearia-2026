<?php

$host = "127.0.0.1";
$porta = "3307";
$banco = "barbearia";
$usuario = "root";
$senha = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$porta;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Conexão com o banco de dados realizada com sucesso!";

} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}