
function calcular() {

    let num1 = document.getElementById("num1").value;
    let num2 = document.getElementById("num2").value;
    let operacao = document.getElementById("operacao").value;

    fetch("calcula.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "num1=" + encodeURIComponent(num1) +
              "&num2=" + encodeURIComponent(num2) +
              "&operacao=" + encodeURIComponent(operacao)
    })

    .then(resposta => resposta.text())
    .then(resultado => {
        document.getElementById("resultado").innerHTML = resultado;
    });

}

///////////////////////////////////////////////


function calcular() {

    let num1 = document.getElementById("num1").value;
    let num2 = document.getElementById("num2").value;
    let operacao = document.getElementById("operacao").value;

    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
			let resposta = JSON.parse(this.responseText);
            document.getElementById("resultado").innerHTML = resposta.resultado;
        } else if (this.readyState < 4) {
            console.log("Carregando... Estado: " + this.readyState);
        } else {
            console.log("Requisição falhou: " + this.status);
        }
    };
    xmlhttp.open("POST", "calcula.php", true);
    xmlhttp.setRequestHeader(
        "Content-Type",
        "application/x-www-form-urlencoded"
    );
    xmlhttp.send(
        "num1=" + encodeURIComponent(num1) +
        "&num2=" + encodeURIComponent(num2) +
        "&operacao=" + encodeURIComponent(operacao)
    );
}
