<?php
require_once "funcoes.php";

$totalPerguntas = count(lerRegistros(ARQ_PERGUNTAS, CAB_PERGUNTAS));
$totalUsuarios = count(lerRegistros(ARQ_USUARIOS, CAB_USUARIOS));
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Falls - Jogo Corporativo</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<?php include "menu.php"; ?>
<main>
    <h1>Jogo Corporativo - Cadastros</h1>
    <p>Treinamento de gestores com perguntas e respostas (decisões) encadeadas.</p>

    <h2>Perguntas e Respostas (<?php echo $totalPerguntas ?>)</h2>
    <ul>
        <li><a href="incluirPerguntaMultipla.php">1. Criar pergunta de múltipla escolha</a></li>
        <li><a href="incluirPerguntaTexto.php">2. Criar pergunta de texto</a></li>
        <li><a href="listarPerguntas.php">3/4. Alterar perguntas (pela listagem)</a></li>
        <li><a href="listarPerguntas.php">5. Listar perguntas e respostas</a></li>
        <li><a href="listarPergunta.php">6. Listar uma pergunta</a></li>
        <li><a href="listarPerguntas.php">7. Excluir pergunta e respostas (pela listagem)</a></li>
    </ul>

    <h2>Usuários (<?php echo $totalUsuarios ?>)</h2>
    <ul>
        <li><a href="incluirUsuario.php">Criar usuário</a></li>
        <li><a href="listarUsuarios.php">Listar / alterar / excluir usuários</a></li>
    </ul>
</main>
</body>
</html>
