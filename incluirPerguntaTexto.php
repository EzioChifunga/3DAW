<?php
require_once "funcoes.php";

$msg = "";
$erro = "";
$enunciado = "";
$resposta = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $enunciado = limparCampo($_POST["enunciado"] ?? "");
    $resposta = limparCampo($_POST["resposta"] ?? "");

    $erro = validarPerguntaTexto($enunciado, $resposta);
    if ($erro == "") {
        $perguntas = lerRegistros(ARQ_PERGUNTAS, CAB_PERGUNTAS);
        $id = proximoId($perguntas);
        acrescentarRegistro(ARQ_PERGUNTAS, CAB_PERGUNTAS, array($id, "texto", $enunciado, $resposta));
        $msg = "Pergunta #$id criada com sucesso!";

        $enunciado = "";
        $resposta = "";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Pergunta - Texto</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<?php include "menu.php"; ?>
<main>
<h1>Criar Nova Pergunta de Texto</h1>
<p class="msg"><?php echo escapar($msg) ?></p>
<p class="erro"><?php echo escapar($erro) ?></p>

<form action="incluirPerguntaTexto.php" method="POST">
    <label for="enunciado">Enunciado (situação / pergunta):</label>
    <textarea id="enunciado" name="enunciado"><?php echo escapar($enunciado) ?></textarea>

    <label for="resposta">Resposta esperada:</label>
    <textarea id="resposta" name="resposta"><?php echo escapar($resposta) ?></textarea>

    <input type="submit" value="Criar Nova Pergunta">
</form>
</main>
</body>
</html>
