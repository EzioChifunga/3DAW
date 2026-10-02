<?php
require_once "funcoes.php";

$msg = "";
$erro = "";
$nome = "";
$email = "";
$perfil = "Gestor";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = limparCampo($_POST["nome"] ?? "");
    $email = strtolower(limparCampo($_POST["email"] ?? ""));
    $senha = $_POST["senha"] ?? "";
    $perfil = $_POST["perfil"] ?? "";

    $usuarios = lerRegistros(ARQ_USUARIOS, CAB_USUARIOS);

    if ($nome == "") {
        $erro = "Informe o nome.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Informe um e-mail válido.";
    } elseif (strlen($senha) < 4) {
        $erro = "A senha deve ter pelo menos 4 caracteres.";
    } elseif (!in_array($perfil, PERFIS)) {
        $erro = "Escolha um perfil.";
    } else {
        foreach ($usuarios as $colunaDados) {
            if ($colunaDados[2] == $email) {
                $erro = "Já existe um usuário com este e-mail.";
            }
        }
    }

    if ($erro == "") {
        $id = proximoId($usuarios);
        // a senha e gravada criptografada (o resultado nao tem ";")
        $senhaGravada = password_hash($senha, PASSWORD_DEFAULT);
        acrescentarRegistro(ARQ_USUARIOS, CAB_USUARIOS, array($id, $nome, $email, $senhaGravada, $perfil));
        $msg = "Usuário #$id criado com sucesso!";

        $nome = "";
        $email = "";
        $perfil = "Gestor";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Usuário</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<?php include "menu.php"; ?>
<main>
<h1>Criar Novo Usuário</h1>
<p class="msg"><?php echo escapar($msg) ?></p>
<p class="erro"><?php echo escapar($erro) ?></p>

<form action="incluirUsuario.php" method="POST">
    <label for="nome">Nome:</label>
    <input type="text" id="nome" name="nome" value="<?php echo escapar($nome) ?>">

    <label for="email">E-mail:</label>
    <input type="email" id="email" name="email" value="<?php echo escapar($email) ?>">

    <label for="senha">Senha:</label>
    <input type="password" id="senha" name="senha">

    <label for="perfil">Perfil:</label>
    <select id="perfil" name="perfil">
        <?php foreach (PERFIS as $opcaoPerfil) { ?>
            <option value="<?php echo $opcaoPerfil ?>" <?php echo $perfil == $opcaoPerfil ? "selected" : "" ?>><?php echo $opcaoPerfil ?></option>
        <?php } ?>
    </select>

    <input type="submit" value="Criar Novo Usuário">
</form>
</main>
</body>
</html>
