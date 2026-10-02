<?php
require_once "funcoes.php";

$msg = "";
$erro = "";
$usuario = null;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // mostra o usuario para confirmar a exclusao
    $id = $_GET["id"] ?? "";
    $usuario = buscarPorId(lerRegistros(ARQ_USUARIOS, CAB_USUARIOS), $id);
    if ($usuario == null) {
        $erro = "Usuário não encontrado.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST["id"] ?? "";
    $usuarios = lerRegistros(ARQ_USUARIOS, CAB_USUARIOS);
    $mantidos = array();
    $encontrou = false;
    foreach ($usuarios as $colunaDados) {
        if ($colunaDados[0] == $id) {
            $encontrou = true;
        } else {
            $mantidos[] = $colunaDados;
        }
    }

    if ($encontrou) {
        gravarRegistros(ARQ_USUARIOS, CAB_USUARIOS, $mantidos);
        $msg = "Usuário #$id excluído.";
    } else {
        $erro = "Usuário não encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Usuário</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<?php include "menu.php"; ?>
<main>
<h1>Excluir Usuário</h1>
<p class="msg"><?php echo escapar($msg) ?></p>
<p class="erro"><?php echo escapar($erro) ?></p>

<?php if ($usuario != null) { ?>
    <p>Deseja realmente excluir o usuário abaixo?</p>
    <p><strong>#<?php echo escapar($usuario[0]) ?> - <?php echo escapar($usuario[1]) ?></strong>
        (<?php echo escapar($usuario[2]) ?>)</p>

    <form action="excluirUsuario.php" method="POST">
        <input type="hidden" name="id" value="<?php echo escapar($usuario[0]) ?>">
        <input type="submit" class="perigo" value="Confirmar Exclusão">
    </form>
<?php } ?>

<p><a href="listarUsuarios.php">Voltar para a lista de usuários</a></p>
</main>
</body>
</html>
