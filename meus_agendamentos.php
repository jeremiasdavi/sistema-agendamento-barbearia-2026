
<?php
require_once "conexao.php";

$mensagem = "";

// Cancelar agendamento
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["cancelar"])) {
    $id = (int) $_POST["cancelar"];

    $stmt = $pdo->prepare(
        "UPDATE agendamento
         SET status = 'Cancelado'
         WHERE id_agendamento = :id
         AND status <> 'Cancelado'"
    );

    $stmt->execute([":id" => $id]);

    $mensagem = "Agendamento cancelado!";
}

// Consultar agendamentos
$sql = "SELECT
            a.id_agendamento,
            c.nome AS cliente,
            b.nome AS barbeiro,
            s.nome AS servico,
            a.data,
            a.horario,
            a.status
        FROM agendamento a
        INNER JOIN cliente c ON a.id_cliente = c.id_cliente
        INNER JOIN barbeiro b ON a.id_barbeiro = b.id_barbeiro
        INNER JOIN servico s ON a.id_servico = s.id_servico
        ORDER BY a.data, a.horario";

$agendamentos = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Agendamentos</title>
</head>
<body>

<h1>Agendamentos da Barbearia</h1>

<p><?= htmlspecialchars($mensagem) ?></p>

<table border="1" cellpadding="10">
    <tr>
        <th>Cliente</th>
        <th>Barbeiro</th>
        <th>Serviço</th>
        <th>Data</th>
        <th>Horário</th>
        <th>Status</th>
        <th>Ação</th>
    </tr>

    <?php foreach ($agendamentos as $a): ?>
    <tr>
        <td><?= htmlspecialchars($a["cliente"]) ?></td>
        <td><?= htmlspecialchars($a["barbeiro"]) ?></td>
        <td><?= htmlspecialchars($a["servico"]) ?></td>
        <td><?= date("d/m/Y", strtotime($a["data"])) ?></td>
        <td><?= htmlspecialchars($a["horario"]) ?></td>
        <td><?= htmlspecialchars($a["status"]) ?></td>
        <td>
            <?php if ($a["status"] !== "Cancelado"): ?>
                <form method="POST">
                    <button
                        type="submit"
                        name="cancelar"
                        value="<?= $a["id_agendamento"] ?>"
                        onclick="return confirm('Deseja cancelar este agendamento?')">
                        Cancelar
                    </button>
                </form>
            <?php else: ?>
                Cancelado
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>

</table>

</body>
</html>
