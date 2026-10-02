<?php
// funcoes de apoio usadas por todas as telas.
// os dados ficam em arquivos texto dentro da pasta "dados", separados por ";"
// e com uma linha de cabecalho (mesmo formato do disciplinas.txt).

define("ARQ_USUARIOS", __DIR__ . "/dados/usuarios.txt");
define("CAB_USUARIOS", "id;nome;email;senha;perfil");

define("ARQ_PERGUNTAS", __DIR__ . "/dados/perguntas.txt");
define("CAB_PERGUNTAS", "id;tipo;enunciado;resposta");

define("ARQ_RESPOSTAS", __DIR__ . "/dados/respostas.txt");
define("CAB_RESPOSTAS", "idPergunta;letra;texto");

define("LETRAS", array("A", "B", "C", "D", "E"));
define("PERFIS", array("Administrador", "Gestor"));

// cria o arquivo com a linha de cabecalho caso ele ainda nao exista
function criarArquivoSeNaoExistir($arquivo, $cabecalho) {
    if (!file_exists($arquivo)) {
        $arqDados = fopen($arquivo, "w") or die("erro ao criar arquivo " . basename($arquivo));
        fwrite($arqDados, $cabecalho . "\n");
        fclose($arqDados);
    }
}

// le todas as linhas do arquivo (sem o cabecalho) e devolve um array de colunas
function lerRegistros($arquivo, $cabecalho) {
    criarArquivoSeNaoExistir($arquivo, $cabecalho);
    $registros = array();
    $arqDados = fopen($arquivo, "r") or die("erro ao abrir arquivo " . basename($arquivo));
    $linha = fgets($arqDados); // pula o cabecalho
    while (($linha = fgets($arqDados)) !== false) {
        $linha = trim($linha);
        if ($linha == "") {
            continue;
        }
        $registros[] = explode(";", $linha);
    }
    fclose($arqDados);
    return $registros;
}

// reescreve o arquivo inteiro (usado para alterar e excluir)
function gravarRegistros($arquivo, $cabecalho, $registros) {
    $arqDados = fopen($arquivo, "w") or die("erro ao gravar arquivo " . basename($arquivo));
    fwrite($arqDados, $cabecalho . "\n");
    foreach ($registros as $colunaDados) {
        fwrite($arqDados, implode(";", $colunaDados) . "\n");
    }
    fclose($arqDados);
}

// acrescenta uma linha no final do arquivo (usado para incluir)
function acrescentarRegistro($arquivo, $cabecalho, $colunaDados) {
    criarArquivoSeNaoExistir($arquivo, $cabecalho);
    $arqDados = fopen($arquivo, "a") or die("erro ao gravar arquivo " . basename($arquivo));
    fwrite($arqDados, implode(";", $colunaDados) . "\n");
    fclose($arqDados);
}

// procura um registro pelo id (primeira coluna)
function buscarPorId($registros, $id) {
    foreach ($registros as $colunaDados) {
        if ($colunaDados[0] == $id) {
            return $colunaDados;
        }
    }
    return null;
}

// gera o proximo id a partir do maior id existente
function proximoId($registros) {
    $maior = 0;
    foreach ($registros as $colunaDados) {
        if ((int)$colunaDados[0] > $maior) {
            $maior = (int)$colunaDados[0];
        }
    }
    return $maior + 1;
}

// remove ";" e quebras de linha para nao quebrar o formato do arquivo
function limparCampo($valor) {
    $valor = str_replace(array("\r\n", "\r", "\n"), " ", (string)$valor);
    $valor = str_replace(";", ",", $valor);
    return trim($valor);
}

// escapa o texto antes de mostrar na tela
function escapar($valor) {
    return htmlspecialchars((string)$valor, ENT_QUOTES, "UTF-8");
}

// ---------- Perguntas e respostas ----------

// devolve as alternativas de uma pergunta no formato ["A" => "texto", ...]
function buscarAlternativas($idPergunta) {
    $alternativas = array();
    $respostas = lerRegistros(ARQ_RESPOSTAS, CAB_RESPOSTAS);
    foreach ($respostas as $colunaDados) {
        if ($colunaDados[0] == $idPergunta) {
            $alternativas[$colunaDados[1]] = $colunaDados[2];
        }
    }
    return $alternativas;
}

// remove todas as alternativas de uma pergunta do arquivo de respostas
function excluirAlternativas($idPergunta) {
    $respostas = lerRegistros(ARQ_RESPOSTAS, CAB_RESPOSTAS);
    $mantidas = array();
    foreach ($respostas as $colunaDados) {
        if ($colunaDados[0] != $idPergunta) {
            $mantidas[] = $colunaDados;
        }
    }
    gravarRegistros(ARQ_RESPOSTAS, CAB_RESPOSTAS, $mantidas);
}

// grava as alternativas preenchidas de uma pergunta
function gravarAlternativas($idPergunta, $alternativas) {
    foreach ($alternativas as $letra => $texto) {
        if ($texto != "") {
            acrescentarRegistro(ARQ_RESPOSTAS, CAB_RESPOSTAS, array($idPergunta, $letra, $texto));
        }
    }
}

// valida pergunta de multipla escolha. Devolve "" quando esta tudo certo.
function validarPerguntaMultipla($enunciado, $alternativas, $correta) {
    if ($enunciado == "") {
        return "Informe o enunciado da pergunta.";
    }
    $preenchidas = 0;
    foreach ($alternativas as $texto) {
        if ($texto != "") {
            $preenchidas++;
        }
    }
    if ($preenchidas < 2) {
        return "Informe pelo menos duas alternativas.";
    }
    if (!in_array($correta, LETRAS) || $alternativas[$correta] == "") {
        return "Escolha qual alternativa preenchida é a correta.";
    }
    return "";
}

// valida pergunta de texto. Devolve "" quando esta tudo certo.
function validarPerguntaTexto($enunciado, $resposta) {
    if ($enunciado == "") {
        return "Informe o enunciado da pergunta.";
    }
    if ($resposta == "") {
        return "Informe a resposta esperada.";
    }
    return "";
}

// le as alternativas enviadas pelo formulario
function lerAlternativasDoPost() {
    $alternativas = array();
    foreach (LETRAS as $letra) {
        $alternativas[$letra] = limparCampo($_POST["alternativa"][$letra] ?? "");
    }
    return $alternativas;
}

function nomeTipo($tipo) {
    return $tipo == "multipla" ? "Múltipla escolha" : "Texto";
}
