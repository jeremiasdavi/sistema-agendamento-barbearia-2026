<?php
require_once "conexao.php";

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $telefone = trim($_POST["telefone"]);
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO cliente (nome, email, telefone, senha)
                VALUES (:nome, :email, :telefone, :senha)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ":nome" => $nome,
            ":email" => $email,
            ":telefone" => $telefone,
            ":senha" => $senha
        ]);

        $mensagem = "Cliente cadastrado com sucesso!";

    } catch (PDOException $e) {
        $mensagem = "Erro ao cadastrar. Verifique se o e-mail já existe.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro - Barbearia</title>
</head>
<body>

    <h1>Cadastro de Cliente</h1>

    <p><?= htmlspecialchars($mensagem) ?></p>

    <form method="POST">
        <label>Nome:</label><br>
        <input type="text" name="nome" required><br><br>

        <label>E-mail:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Telefone:</label><br>
        <input type="text" name="telefone" required><br><br>

        <label>Senha:</label><br>
        <input type="password" name="senha" required minlength="6"><br><br>

        <button type="submit">Cadastrar</button>
    </form>

</body>
</html>