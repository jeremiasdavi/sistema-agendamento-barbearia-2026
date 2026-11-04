
<?php
require_once "conexao.php";

$mensagem = "";

// Buscar clientes, barbeiros e serviços
$clientes = $pdo->query(
    "SELECT id_cliente, nome FROM cliente ORDER BY nome"
)->fetchAll(PDO::FETCH_ASSOC);

$barbeiros = $pdo->query(
    "SELECT id_barbeiro, nome FROM barbeiro ORDER BY nome"
)->fetchAll(PDO::FETCH_ASSOC);

$servicos = $pdo->query(
    "SELECT id_servico, nome, preco FROM servico ORDER BY nome"
)->fetchAll(PDO::FETCH_ASSOC);

// Registrar agendamento
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cliente = $_POST["cliente"];
    $barbeiro = $_POST["barbeiro"];
    $servico = $_POST["servico"];
    $data = $_POST["data"];
    $horario = $_POST["horario"];

    try {
        // Verificar se o barbeiro já possui agendamento
        $verificar = $pdo->prepare(
            "SELECT COUNT(*) FROM agendamento
             WHERE id_barbeiro = :barbeiro
             AND data = :data
             AND horario = :horario
             AND status <> 'Cancelado'"
        );

        $verificar->execute([
            ":barbeiro" => $barbeiro,
            ":data" => $data,
            ":horario" => $horario
        ]);

        if ($verificar->fetchColumn() > 0) {
            $mensagem = "Esse horário já está ocupado!";
        } else {
            $sql = "INSERT INTO agendamento
                    (id_cliente, id_barbeiro, id_servico, data, horario)
                    VALUES (:cliente, :barbeiro, :servico, :data, :horario)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ":cliente" => $cliente,
                ":barbeiro" => $barbeiro,
                ":servico" => $servico,
                ":data" => $data,
                ":horario" => $horario
            ]);

            $mensagem = "Agendamento realizado com sucesso!";
        }

    } catch (PDOException $e) {
        $mensagem = "Erro ao realizar agendamento.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Agendamento - Barbearia</title>
</head>
<body>

<h1>Agendar horário</h1>

<p><?= htmlspecialchars($mensagem) ?></p>

<form method="POST">

    <label>Cliente:</label><br>
    <select name="cliente" required>
        <option value="">Selecione</option>
        <?php foreach ($clientes as $c): ?>
            <option value="<?= $c['id_cliente'] ?>">
                <?= htmlspecialchars($c['nome']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Barbeiro:</label><br>
    <select name="barbeiro" required>
        <option value="">Selecione</option>
        <?php foreach ($barbeiros as $b): ?>
            <option value="<?= $b['id_barbeiro'] ?>">
                <?= htmlspecialchars($b['nome']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Serviço:</label><br>
    <select name="servico" required>
        <option value="">Selecione</option>
        <?php foreach ($servicos as $s): ?>
            <option value="<?= $s['id_servico'] ?>">
                <?= htmlspecialchars($s['nome']) ?>
                - R$ <?= number_format($s['preco'], 2, ',', '.') ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Data:</label><br>
    <input type="date" name="data" min="<?= date('Y-m-d') ?>" required><br><br>

    <label>Horário:</label><br>
    <input type="time" name="horario" required><br><br>

    <button type="submit">Confirmar agendamento</button>

</form>

</body>
</html>
