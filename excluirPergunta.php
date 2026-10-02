<?php
require_once "funcoes.php";

$msg = "";
$erro = "";
$pergunta = null;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // mostra a pergunta para o usuario confirmar a exclusao
    $id = $_GET["id"] ?? "";
    $pergunta = buscarPorId(lerRegistros(ARQ_PERGUNTAS, CAB_PERGUNTAS), $id);
    if ($pergunta == null) {
        $erro = "Pergunta não encontrada.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST["id"] ?? "";
    $perguntas = lerRegistros(ARQ_PERGUNTAS, CAB_PERGUNTAS);
    $mantidas = array();
    $encontrou = false;
    foreach ($perguntas as $colunaDados) {
        if ($colunaDados[0] == $id) {
            $encontrou = true;
        } else {
            $mantidas[] = $colunaDados;
        }
    }

    if ($encontrou) {
        gravarRegistros(ARQ_PERGUNTAS, CAB_PERGUNTAS, $mantidas);
        excluirAlternativas($id);
        $msg = "Pergunta #$id e suas respostas foram excluídas.";
    } else {
        $erro = "Pergunta não encontrada.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Pergunta</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<?php include "menu.php"; ?>
<main>
<h1>Excluir Pergunta e Respostas</h1>
<p class="msg"><?php echo escapar($msg) ?></p>
<p class="erro"><?php echo escapar($erro) ?></p>

<?php if ($pergunta != null) { ?>
    <p>Deseja realmente excluir a pergunta abaixo e todas as suas respostas?</p>
    <p><strong>#<?php echo escapar($pergunta[0]) ?> (<?php echo nomeTipo($pergunta[1]) ?>):</strong>
        <?php echo escapar($pergunta[2]) ?></p>

    <form action="excluirPergunta.php" method="POST">
        <input type="hidden" name="id" value="<?php echo escapar($pergunta[0]) ?>">
        <input type="submit" class="perigo" value="Confirmar Exclusão">
    </form>
<?php } ?>

<p><a href="listarPerguntas.php">Voltar para a lista de perguntas</a></p>
</main>
</body>
</html>
