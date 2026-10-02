<?php
require_once "funcoes.php";

$msg = "";
$erro = "";
$enunciado = "";
$alternativas = array_fill_keys(LETRAS, "");
$correta = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $enunciado = limparCampo($_POST["enunciado"] ?? "");
    $alternativas = lerAlternativasDoPost();
    $correta = $_POST["correta"] ?? "";

    $erro = validarPerguntaMultipla($enunciado, $alternativas, $correta);
    if ($erro == "") {
        $perguntas = lerRegistros(ARQ_PERGUNTAS, CAB_PERGUNTAS);
        $id = proximoId($perguntas);
        acrescentarRegistro(ARQ_PERGUNTAS, CAB_PERGUNTAS, array($id, "multipla", $enunciado, $correta));
        gravarAlternativas($id, $alternativas);
        $msg = "Pergunta #$id criada com sucesso!";

        // limpa o formulario para a proxima pergunta
        $enunciado = "";
        $alternativas = array_fill_keys(LETRAS, "");
        $correta = "";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Pergunta - Múltipla Escolha</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<?php include "menu.php"; ?>
<main>
<h1>Criar Nova Pergunta de Múltipla Escolha</h1>
<p class="msg"><?php echo escapar($msg) ?></p>
<p class="erro"><?php echo escapar($erro) ?></p>

<form action="incluirPerguntaMultipla.php" method="POST">
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

    <input type="submit" value="Criar Nova Pergunta">
</form>
</main>
</body>
</html>
