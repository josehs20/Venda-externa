validDocumento = false;
validNome = false;
validNumeros = [];


$(function () {
    $('form[id="CadastroCliente"]').submit(function (event) {
        event.preventDefault();
        
        console.log(validDocumento, validNome, validNumeros);



    });
});

//valida nome
function validainputNome() {
    //console.log('a');
    var nome = document.getElementById('inputNome').value;
    var regex = /[0-9]/;
    var b = regex.test(nome);
    if (b) {
        document.getElementById('nomeValid').innerHTML = "Nome contém numero";
        validNome = false;
        return;
    } else {
        document.getElementById('nomeValid').innerHTML = "";
        validNome = true;
        return;
    }
}
function validainputNumeros(inputId) {

    var arrey = Array.from(document.getElementById(inputId).value);
    var msg = document.getElementById(inputId + "Msg");
    var counts = {};
    if (!arrey.length) {
        msg.innerHTML = "";
        validNumeros[inputId] = true;
    }
  
    arrey.forEach(function (x) {
        counts[x] = (counts[x] || 0) + 1;
    });
    Object.keys(counts).forEach(function (item) {
        if (counts[item] >= 6) {
            msg.style.color = "red";
            msg.innerHTML = "Número inválido";
            validNumeros[inputId] = false;
        } else {
            msg.innerHTML = "";
            validNumeros[inputId] = true;
        }

    });
    if (arrey.length != false && arrey.length < 8 || arrey.length > 12) {
        msg.style.color = "red";
        msg.innerHTML = "Número inválido";
        validNumeros[inputId] = false;
    }
}



$('#TipoDocumento').change(function (e) {
    let opcaoSelecionada = this.querySelector('option:checked');
    opcao = opcaoSelecionada.value
    document.getElementById('tituloIputuDocumento').innerHTML = opcaoSelecionada.value
});

$('#docto').keyup(function () {

    var input = document.getElementById("docto").value; //pega o valor digitado.
    var msg = document.getElementById('msgValid');

    if (input.length == 11 || input.length == 14) { //verifica se é o valor de sua preferencia. se for seleciona.
        if (input.length == 11) {
            document.getElementById('selectCNPJ').removeAttribute("selected")
            $("#selectCPF").attr('selected', 'selected');
            var docto = input;
            var tipoDoc = 'CPF'
            validaDocs(docto, tipoDoc)
            return
        }
        if (input.length == 14) {
            var docto = input;
            var tipoDoc = 'CNPJ'
            document.getElementById('selectCPF').removeAttribute("selected")
            $("#selectCNPJ").attr('selected', 'selected');
            //  console.log(document.getElementById('selectCNPJ'));
            validaDocs(docto, tipoDoc)
            return
        }
    } else {
        msg.style.color = "blue"
        msg.innerHTML = "11 digitos para CPF e 14 para CNPJ"
        validDocumento = false;
    }
});
function validaDocs(docto, tipoDoc) {

    var msg = document.getElementById('msgValid');

    if (tipoDoc == 'CPF') {
        if (validaCPF(docto)) {
            console.log('cpfvalido');
            msg.style.color = "black"
            msg.innerHTML = "CPF Válido"
            validDocumento = true
            return
        } else {
            msg.style.color = "red"
            msg.innerHTML = "CPF Inválido"
            validDocumento = false
        }
    }
    if (tipoDoc == 'CNPJ') {
        if (validaCNPJ(docto)) {
            msg.style.color = "black"
            msg.innerHTML = "CNPJ Válido"
            validDocumento = true
        } else {
            msg.style.color = "red"
            msg.innerHTML = "CNPJ Inválido"
            validDocumento = false

        }
    }
}


// Função que valida o CPF
function validaCPF(strDocument) {
    var soma;
    var resto;
    soma = 0;

    // Elimina CPF's invalidos conhecidos
    if (strDocument == "00000000000" ||
        strDocument == "11111111111" ||
        strDocument == "22222222222" ||
        strDocument == "33333333333" ||
        strDocument == "44444444444" ||
        strDocument == "55555555555" ||
        strDocument == "66666666666" ||
        strDocument == "77777777777" ||
        strDocument == "88888888888" ||
        strDocument == "99999999999")
        return false;

    for (i = 1; i <= 9; i++) soma = soma + parseInt(strDocument.substring(i - 1, i)) * (11 - i);
    resto = (soma * 10) % 11;

    if ((resto == 10) || (resto == 11)) resto = 0;
    if (resto != parseInt(strDocument.substring(9, 10))) return false;

    soma = 0;
    for (i = 1; i <= 10; i++) soma = soma + parseInt(strDocument.substring(i - 1, i)) * (12 - i);
    resto = (soma * 10) % 11;

    if ((resto == 10) || (resto == 11)) resto = 0;
    if (resto != parseInt(strDocument.substring(10, 11))) return false;
    return true;
}

// Função que valida o CNPJ
function validaCNPJ(CNPJ) {
    var validaArray = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
    var primeiroDigito = new Number;
    var segundoDigito = new Number;
    var digito = Number(eval(CNPJ.charAt(12) + CNPJ.charAt(13)));

    for (i = 0; i < validaArray.length; i++) {
        primeiroDigito += (i > 0 ? (CNPJ.charAt(i - 1) * validaArray[i]) : 0);
        segundoDigito += CNPJ.charAt(i) * validaArray[i];
    }
    primeiroDigito = (((primeiroDigito % 11) < 2) ? 0 : (11 - (primeiroDigito % 11)));
    segundoDigito = (((segundoDigito % 11) < 2) ? 0 : (11 - (segundoDigito % 11)));

    resultado = (((primeiroDigito * 10) + segundoDigito)) == digito ? true : false;
    return resultado;
}
