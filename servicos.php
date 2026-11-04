
<?php
require_once "conexao.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $descricao = trim($_POST["descricao"]);
    $preco = $_POST["preco"];
    $duracao = $_POST["duracao"];

    try {
        $sql = "INSERT INTO servico (nome, descricao, preco, duracao)
                VALUES (:nome, :descricao, :preco, :duracao)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":nome" => $nome,
            ":descricao" => $descricao,
            ":preco" => $preco,
            ":duracao" => $duracao
        ]);

        $mensagem = "Serviço cadastrado com sucesso!";

    } catch (PDOException $e) {
        $mensagem = "Erro ao cadastrar serviço.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Serviços</title>
</head>
<body>

    <h1>Cadastro de Serviços</h1>

    <p><?= htmlspecialchars($mensagem) ?></p>

    <form method="POST">
        <label>Nome do serviço:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Descrição:</label><br>
        <input type="text" name="descricao"><br><br>

        <label>Preço (R$):</label><br>
        <input type="number" name="preco" step="0.01" min="0" required><br><br>

        <label>Duração (minutos):</label><br>
        <input type="number" name="duracao" min="1" required><br><br>

        <button type="submit">Cadastrar serviço</button>
    </form>

</body>
</html>
