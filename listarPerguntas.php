<?php
require_once "funcoes.php";

$perguntas = lerRegistros(ARQ_PERGUNTAS, CAB_PERGUNTAS);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Perguntas</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<?php include "menu.php"; ?>
<main>
<h1>Listar Perguntas e Respostas</h1>

<?php if (count($perguntas) == 0) { ?>
    <p>Nenhuma pergunta cadastrada.</p>
<?php } else { ?>
<table>
    <tr><th>#</th><th>Tipo</th><th>Enunciado</th><th>Respostas</th><th>Ações</th></tr>
    <?php foreach ($perguntas as $colunaDados) {
        $id = $colunaDados[0];
        $tipo = $colunaDados[1];
        $telaAlterar = $tipo == "multipla" ? "alterarPerguntaMultipla.php" : "alterarPerguntaTexto.php";
    ?>
    <tr>
        <td><?php echo escapar($id) ?></td>
        <td><?php echo nomeTipo($tipo) ?></td>
        <td><?php echo escapar($colunaDados[2]) ?></td>
        <td>
            <?php if ($tipo == "multipla") {
                foreach (buscarAlternativas($id) as $letra => $texto) {
                    $classe = $letra == $colunaDados[3] ? "correta" : "";
                    echo "<div class='$classe'>" . escapar($letra) . ") " . escapar($texto) . "</div>";
                }
            } else {
                echo "<div class='correta'>" . escapar($colunaDados[3]) . "</div>";
            } ?>
        </td>
        <td>
            <a href="listarPergunta.php?id=<?php echo escapar($id) ?>">Ver</a> |
            <a href="<?php echo $telaAlterar ?>?id=<?php echo escapar($id) ?>">Alterar</a> |
            <a href="excluirPergunta.php?id=<?php echo escapar($id) ?>">Excluir</a>
        </td>
    </tr>
    <?php } ?>
</table>
<p>A resposta correta aparece em <span class="correta">verde</span>.</p>
<?php } ?>
</main>
</body>
</html>
