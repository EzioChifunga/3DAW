<?php
require_once "funcoes.php";

$usuarios = lerRegistros(ARQ_USUARIOS, CAB_USUARIOS);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Usuários</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<?php include "menu.php"; ?>
<main>
<h1>Listar Usuários</h1>

<?php if (count($usuarios) == 0) { ?>
    <p>Nenhum usuário cadastrado. <a href="incluirUsuario.php">Criar usuário</a></p>
<?php } else { ?>
<table>
    <tr><th>#</th><th>Nome</th><th>E-mail</th><th>Perfil</th><th>Ações</th></tr>
    <?php foreach ($usuarios as $colunaDados) { ?>
    <tr>
        <td><?php echo escapar($colunaDados[0]) ?></td>
        <td><?php echo escapar($colunaDados[1]) ?></td>
        <td><?php echo escapar($colunaDados[2]) ?></td>
        <td><?php echo escapar($colunaDados[4]) ?></td>
        <td>
            <a href="alterarUsuario.php?id=<?php echo escapar($colunaDados[0]) ?>">Alterar</a> |
            <a href="excluirUsuario.php?id=<?php echo escapar($colunaDados[0]) ?>">Excluir</a>
        </td>
    </tr>
    <?php } ?>
</table>
<?php } ?>
</main>
</body>
</html>
