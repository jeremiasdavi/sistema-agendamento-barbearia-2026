
<?php
require_once "conexao.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $especialidade = trim($_POST["especialidade"]);
    $telefone = trim($_POST["telefone"]);

    try {
        $sql = "INSERT INTO barbeiro (nome, especialidade, telefone)
                VALUES (:nome, :especialidade, :telefone)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":nome" => $nome,
            ":especialidade" => $especialidade,
            ":telefone" => $telefone
        ]);

        $mensagem = "Barbeiro cadastrado com sucesso!";

    } catch (PDOException $e) {
        $mensagem = "Erro ao cadastrar barbeiro.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Barbeiros</title>
</head>
<body>

    <h1>Cadastro de Barbeiros</h1>

    <p><?= htmlspecialchars($mensagem) ?></p>

    <form method="POST">
        <label>Nome do barbeiro:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>Especialidade:</label><br>
        <input type="text" name="especialidade"><br><br>

        <label>Telefone:</label><br>
        <input type="text" name="telefone" required><br><br>

        <button type="submit">Cadastrar barbeiro</button>
    </form>

</body>
</html>
