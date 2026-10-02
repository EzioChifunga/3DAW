<?php
require_once "funcoes.php";

$msg = "";
$erro = "";
$id = "";
$enunciado = "";
$alternativas = array_fill_keys(LETRAS, "");
$correta = "";
$encontrou = false;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // carrega a pergunta escolhida para o formulario
    $id = $_GET["id"] ?? "";
    $pergunta = buscarPorId(lerRegistros(ARQ_PERGUNTAS, CAB_PERGUNTAS), $id);
    if ($pergunta == null) {
        $erro = "Pergunta não encontrada.";
    } elseif ($pergunta[1] != "multipla") {
        $erro = "Esta pergunta é de texto. Use a tela de alterar pergunta de texto.";
    } else {
        $encontrou = true;
        $enunciado = $pergunta[2];
        $correta = $pergunta[3];
        $alternativas = array_merge($alternativas, buscarAlternativas($id));
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST["id"] ?? "";
    $enunciado = limparCampo($_POST["enunciado"] ?? "");
    $alternativas = lerAlternativasDoPost();
    $correta = $_POST["correta"] ?? "";
    $encontrou = true;

    $perguntas = lerRegistros(ARQ_PERGUNTAS, CAB_PERGUNTAS);
    $pergunta = buscarPorId($perguntas, $id);
    if ($pergunta == null || $pergunta[1] != "multipla") {
        $erro = "Pergunta não encontrada.";
        $encontrou = false;
    } else {
        $erro = validarPerguntaMultipla($enunciado, $alternativas, $correta);
    }

    if ($erro == "") {
        foreach ($perguntas as $i => $colunaDados) {
            if ($colunaDados[0] == $id) {
                $perguntas[$i] = array($id, "multipla", $enunciado, $correta);
            }
        }
        gravarRegistros(ARQ_PERGUNTAS, CAB_PERGUNTAS, $perguntas);

        // troca as alternativas antigas pelas novas
        excluirAlternativas($id);
        gravarAlternativas($id, $alternativas);
        $msg = "Pergunta #$id alterada com sucesso!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Pergunta - Múltipla Escolha</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<?php include "menu.php"; ?>
<main>
<h1>Alterar Pergunta de Múltipla Escolha</h1>
<p class="msg"><?php echo escapar($msg) ?></p>
<p class="erro"><?php echo escapar($erro) ?></p>

<?php if ($encontrou) { ?>
<form action="alterarPerguntaMultipla.php" method="POST">
    <input type="hidden" name="id" value="<?php echo escapar($id) ?>">
    <p>Pergunta #<?php echo escapar($id) ?></p>

    <label for="enunciado">Enunciado (situação / pergunta):</label>
    <textarea id="enunciado" name="enunciado"><?php echo escapar($enunciado) ?></textarea>

    <label>Alternativas (marque a correta):</label>
    <?php foreach (LETRAS as $letra) { ?>
        <div class="alternativa">
            <input type="radio" name="correta" value="<?php echo $letra ?>"
                <?php echo $correta == $letra ? "checked" : "" ?>>
            <strong><?php echo $letra ?>)</strong>
            <input type="text" name="alternativa[<?php echo $letra ?>]"
                value="<?php echo escapar($alternativas[$letra]) ?>">
        </div>
    <?php } ?>

    <input type="submit" value="Alterar Pergunta">
</form>
<?php } ?>

<p><a href="listarPerguntas.php">Voltar para a lista de perguntas</a></p>
</main>
</body>
</html>
