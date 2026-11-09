const visor = document.getElementById("visor");

function adicionar(valor) {
    visor.value += valor;
}

function limpar() {
    visor.value = "";
}

function calcular() {
    try {
        const expressao = visor.value;
        if (!expressao.trim()) return;
        if (!/^[0-9+\-*/.()\s]+$/.test(expressao)) {
            throw new Error("Expressão inválida");
        }
        const resultado = Function(
            '"use strict"; return (' + expressao + ')'
        )();
      
        if (!Number.isFinite(resultado)) {
            throw new Error("Operação inválida");
        }
        visor.value = resultado;
    } catch (erro) {
        visor.value = "Erro";
    }
}

function raiz() {
    const numero = Number(visor.value);
    if (visor.value === "" || numero < 0) {
        visor.value = "Erro";
        return;
    }
    visor.value = Math.sqrt(numero);
}

function quadrado() {
    const numero = Number(visor.value);
    if (visor.value === "") return;
    visor.value = numero ** 2;
}

function porcentagem() {
    const numero = Number(visor.value);
    if (visor.value === "") return;
    visor.value = numero / 100;
}
