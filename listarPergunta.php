<?php
require_once "funcoes.php";

$id = $_GET["id"] ?? "";
$erro = "";
$pergunta = null;
$alternativas = array();

if ($id != "") {
    $pergunta = buscarPorId(lerRegistros(ARQ_PERGUNTAS, CAB_PERGUNTAS), $id);
    if ($pergunta == null) {
        $erro = "Pergunta #$id não encontrada.";
    } elseif ($pergunta[1] == "multipla") {
        $alternativas = buscarAlternativas($id);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar uma Pergunta</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<?php include "menu.php"; ?>
<main>
<h1>Listar uma Pergunta</h1>

<form action="listarPergunta.php" method="GET">
    <label for="id">Digite o número da pergunta:</label>
    <input type="number" id="id" name="id" min="1" value="<?php echo escapar($id) ?>">
    <input type="submit" value="Buscar">
</form>

<p class="erro"><?php echo escapar($erro) ?></p>

<?php if ($pergunta != null) { ?>
    <h2>Pergunta #<?php echo escapar($pergunta[0]) ?> - <?php echo nomeTipo($pergunta[1]) ?></h2>
    <p><?php echo escapar($pergunta[2]) ?></p>

    <?php if ($pergunta[1] == "multipla") { ?>
        <ul>
        <?php foreach ($alternativas as $letra => $texto) { ?>
            <li class="<?php echo $letra == $pergunta[3] ? "correta" : "" ?>">
                <?php echo escapar($letra) ?>) <?php echo escapar($texto) ?>
                <?php echo $letra == $pergunta[3] ? "(correta)" : "" ?>
            </li>
        <?php } ?>
        </ul>
        <a href="alterarPerguntaMultipla.php?id=<?php echo escapar($pergunta[0]) ?>">Alterar</a> |
    <?php } else { ?>
        <p><strong>Resposta esperada:</strong> <span class="correta"><?php echo escapar($pergunta[3]) ?></span></p>
        <a href="alterarPerguntaTexto.php?id=<?php echo escapar($pergunta[0]) ?>">Alterar</a> |
    <?php } ?>
    <a href="excluirPergunta.php?id=<?php echo escapar($pergunta[0]) ?>">Excluir</a>
<?php } ?>
</main>
</body>
</html>
