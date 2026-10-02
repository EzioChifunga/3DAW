<?php
require_once "funcoes.php";

$msg = "";
$erro = "";
$id = "";
$nome = "";
$email = "";
$perfil = "";
$encontrou = false;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // carrega o usuario escolhido para o formulario
    $id = $_GET["id"] ?? "";
    $usuario = buscarPorId(lerRegistros(ARQ_USUARIOS, CAB_USUARIOS), $id);
    if ($usuario == null) {
        $erro = "Usuário não encontrado.";
    } else {
        $encontrou = true;
        $nome = $usuario[1];
        $email = $usuario[2];
        $perfil = $usuario[4];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST["id"] ?? "";
    $nome = limparCampo($_POST["nome"] ?? "");
    $email = strtolower(limparCampo($_POST["email"] ?? ""));
    $senha = $_POST["senha"] ?? "";
    $perfil = $_POST["perfil"] ?? "";
    $encontrou = true;

    $usuarios = lerRegistros(ARQ_USUARIOS, CAB_USUARIOS);
    $usuario = buscarPorId($usuarios, $id);

    if ($usuario == null) {
        $erro = "Usuário não encontrado.";
        $encontrou = false;
    } elseif ($nome == "") {
        $erro = "Informe o nome.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Informe um e-mail válido.";
    } elseif ($senha != "" && strlen($senha) < 4) {
        $erro = "A senha deve ter pelo menos 4 caracteres.";
    } elseif (!in_array($perfil, PERFIS)) {
        $erro = "Escolha um perfil.";
    } else {
        foreach ($usuarios as $colunaDados) {
            if ($colunaDados[2] == $email && $colunaDados[0] != $id) {
                $erro = "Já existe outro usuário com este e-mail.";
            }
        }
    }

    if ($erro == "") {
        // senha em branco mantem a senha atual
        $senhaGravada = $senha == "" ? $usuario[3] : password_hash($senha, PASSWORD_DEFAULT);
        foreach ($usuarios as $i => $colunaDados) {
            if ($colunaDados[0] == $id) {
                $usuarios[$i] = array($id, $nome, $email, $senhaGravada, $perfil);
            }
        }
        gravarRegistros(ARQ_USUARIOS, CAB_USUARIOS, $usuarios);
        $msg = "Usuário #$id alterado com sucesso!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Usuário</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<?php include "menu.php"; ?>
<main>
<h1>Alterar Usuário</h1>
<p class="msg"><?php echo escapar($msg) ?></p>
<p class="erro"><?php echo escapar($erro) ?></p>

<?php if ($encontrou) { ?>
<form action="alterarUsuario.php" method="POST">
    <input type="hidden" name="id" value="<?php echo escapar($id) ?>">
    <p>Usuário #<?php echo escapar($id) ?></p>

    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" value="<?php echo escapar($nome) ?>">

    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" value="<?php echo escapar($email) ?>">

    <label for="senha">Nova senha (deixe em branco para manter a atual):</label>
    <input type="password" id="senha" name="senha">

    <label for="perfil">Perfil:</label>
    <select id="perfil" name="perfil">
        <?php foreach (PERFIS as $opcaoPerfil) { ?>
            <option value="<?php echo $opcaoPerfil ?>" <?php echo $perfil == $opcaoPerfil ? "selected" : "" ?>><?php echo $opcaoPerfil ?></option>
        <?php } ?>
    </select>

    <input type="submit" value="Alterar Usuário">
</form>
<?php } ?>

<p><a href="listarUsuarios.php">Voltar para a lista de usuários</a></p>
</main>
</body>
</html>
