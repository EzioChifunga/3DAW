<?php
require_once "funcoes.php";

$msg = "";
$erro = "";
$id = "";
$enunciado = "";
$resposta = "";
$encontrou = false;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // carrega a pergunta escolhida para o formulario
    $id = $_GET["id"] ?? "";
    $pergunta = buscarPorId(lerRegistros(ARQ_PERGUNTAS, CAB_PERGUNTAS), $id);
    if ($pergunta == null) {
        $erro = "Pergunta não encontrada.";
    } elseif ($pergunta[1] != "texto") {
        $erro = "Esta pergunta é de múltipla escolha. Use a tela de alterar pergunta de múltipla escolha.";
    } else {
        $encontrou = true;
        $enunciado = $pergunta[2];
        $resposta = $pergunta[3];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST["id"] ?? "";
    $enunciado = limparCampo($_POST["enunciado"] ?? "");
    $resposta = limparCampo($_POST["resposta"] ?? "");
    $encontrou = true;

    $perguntas = lerRegistros(ARQ_PERGUNTAS, CAB_PERGUNTAS);
    $pergunta = buscarPorId($perguntas, $id);
    if ($pergunta == null || $pergunta[1] != "texto") {
        $erro = "Pergunta não encontrada.";
        $encontrou = false;
    } else {
        $erro = validarPerguntaTexto($enunciado, $resposta);
    }

    if ($erro == "") {
        foreach ($perguntas as $i => $colunaDados) {
            if ($colunaDados[0] == $id) {
                $perguntas[$i] = array($id, "texto", $enunciado, $resposta);
            }
        }
        gravarRegistros(ARQ_PERGUNTAS, CAB_PERGUNTAS, $perguntas);
        $msg = "Pergunta #$id alterada com sucesso!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Pergunta - Texto</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<?php include "menu.php"; ?>
<main>
<h1>Alterar Pergunta de Texto</h1>
<p class="msg"><?php echo escapar($msg) ?></p>
<p class="erro"><?php echo escapar($erro) ?></p>

<?php if ($encontrou) { ?>
<form action="alterarPerguntaTexto.php" method="POST">
    <input type="hidden" name="id" value="<?php echo escapar($id) ?>">
    <p>Pergunta #<?php echo escapar($id) ?></p>

    <label for="enunciado">Enunciado (situação / pergunta):</label>
    <textarea id="enunciado" name="enunciado"><?php echo escapar($enunciado) ?></textarea>

    <label for="resposta">Resposta esperada:</label>
    <textarea id="resposta" name="resposta"><?php echo escapar($resposta) ?></textarea>

    <input type="submit" value="Alterar Pergunta">
</form>
<?php } ?>

<p><a href="listarPerguntas.php">Voltar para a lista de perguntas</a></p>
</main>
</body>
</html>
