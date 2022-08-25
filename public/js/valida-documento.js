

//Cria Usuário
$(function () {
    $('form[id="CadastroCliente"]').submit(function (event) {
        event.preventDefault();

        if (documento() && validaInputNome() &&
            validaInputNumeros('inputTel1') &&
            validaInputNumeros('inputTel2') &&
            validaInputNumeros('celular')) {

            var email = $('#inputEmail').val();
            var telefones = [validaInputNumeros('inputTel1'), validaInputNumeros('inputTel2'), validaInputNumeros('celular')]
            var cep = $('#cep').val();
            var uf = $("#uf").val();
            var rua = $("#rua").val();
            var bairro = $("#bairro").val();
            var cidade = $("#cidade").val();
            var numero = $("#numero").val();
            var complemento = $("#compto").val();
            var codIbge = $("#cidIbge").val();

            $.ajax({
                url: "/clientes",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    documento: documento(),
                    nome: validaInputNome(),
                    email: email,
                    telefones: telefones,
                    cep: cep,
                    uf: uf,
                    cidade: cidade,
                    bairro: bairro,
                    rua: rua,
                    numero: numero,
                    complemento: complemento,
                    codIbge: codIbge
                },
                dataType: 'json',
            }).done(function (response) {

                if (response['success'] == true) {
                    $('#docto').val("");
                    $('#inputNome').val("");
                    $('#inputEmail').val("");
                    $('#inputTel1').val("");
                    $('#inputTel2').val("");
                    $('#celular').val("");
                    $('#cep').val("");
                    $("#uf").val("");
                    $("#rua").val("");
                    $("#bairro").val("");
                    $("#cidade").val("");
                    $("#numero").val("");
                    $("#compto").val("");
                    $("#cidIbge").val("");

                    alertPadrao('Cliente Cadastrado Com Sucesso', 'success')

                } else if (response.success == false) {
                    console.log(response.success);
                    alertPadrao(response.msg, 'warning')

                }
            }).catch(function (response) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Não foi possível, entre em contato com o suporte',
                    showConfirmButton: false,
                    timer: 2000
                })
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Não foi possível, entre em contato com o suporte',
                showConfirmButton: false,
                timer: 2000
            })
        }
    });
});

//Atualiza Usuário
$(function () {
    $('form[id="updateCliente"]').submit(function (event) {
        event.preventDefault();

        if (validaInputNumeros('inputTel1') &&
            validaInputNumeros('inputTel2') &&
            validaInputNumeros('celular')) {

            var email = $('#inputEmail').val();
            var telefones = [validaInputNumeros('inputTel1'), validaInputNumeros('inputTel2'), validaInputNumeros('celular')]
            var cep = $('#cep').val();
            var uf = $("#uf").val();
            var rua = $("#rua").val();
            var bairro = $("#bairro").val();
            var cidade = $("#cidade").val();
            var numero = $("#numero").val();
            var complemento = $("#compto").val();
            var codIbge = $("#cidIbge").val();
            var id = $('#idCliente').val();
            var nome = $('#inputNome').val();
            var docto = $('#docto').val();

            $.ajax({
                url: "/clientes/" + id,
                type: "PUT",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id,
                    email: email,
                    telefones: telefones,
                    cep: cep,
                    uf: uf,
                    cidade: cidade,
                    bairro: bairro,
                    rua: rua,
                    numero: numero,
                    complemento: complemento,
                    codIbge: codIbge,
                    nome: nome,
                    docto: docto,
                },
                dataType: 'json',
                success: function (response) {

                    if (response['success'] == true) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Cliente Atualizado Com Sucesso',
                            showConfirmButton: false,
                            timer: 1500
                        })

                    } else if (response['success'] == false) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Documento já existe para  ' + response.cliente.nome,
                            showConfirmButton: false,
                            timer: 2000
                        })
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Não foi possível entre em contato com o suporte',
                        showConfirmButton: false,
                        timer: 1500
                    })
                },
            });
        }
    })
})

//arruma inputes quando entra da página para update
window.onload = function () {
    var dados = {
        uf: $('#uf').val(),
        cidade: $('#cidade').val(),
        inputNome: $('#inputNome').val(),
        bairro: $('#bairro').val(),
        rua: $('#rua').val(),
        compto: $('#compto').val(),

    }

    for (const key in dados) {
        removeCarcterEspecial(dados[key], key)

    }

    return;
}

function removeCarcterEspecial(letra, id) {

    $('#' + id).val(letra.normalize("NFD").replace(/[^\w\s]/gi, "").toUpperCase());

}

//valid cep
function PesquisarCepCidade() {
    var uf = $("#uf").val();
    var cidade = $('#cidade').val()
    var erro = document.getElementById("error");
    $.ajax({
        type: "GET",
        url: "https://viacep.com.br/ws/" + uf + "/" + cidade + "/true/json/",
        dataType: "json",
    }).done(function (dados) {
        if (dados.length) {

            $("#cep").val(dados[0].cep.normalize("NFD").replace(/[^\w\s]/gi, ""));
            $("#cidIbge").val(dados[0].ibge);
            erro.innerHTML = ""
        } else {
            erro.innerHTML = "Inválido"
        }
    });
}
function PesquisarCEP() {
    var cep = document.getElementById('cep').value
    var erro = document.getElementById("error");
    if (cep.length > 7) {
        $.ajax({
            type: "GET",
            url: "https://viacep.com.br/ws/" + cep + "/json/",
            dataType: "json",
            error: function () {
                erro.innerHTML = "Inválido"
            }
        }).done(function (dados) {
            $("#uf").val(dados.uf);
            $("#cidade").val(dados.localidade.normalize("NFD").replace(/[^\w\s]/gi, "").toUpperCase());
            $("#cidIbge").val(dados.ibge.replace('-', ''));
            erro.innerHTML = ""
        });
    }
}

//valida nome
function validaInputNome() {

    var nome = document.getElementById('inputNome').value;
    var regex = /[0-9]/;
    var contemNum = regex.test(nome);

    if (contemNum) {
        document.getElementById('nomeValid').innerHTML = "Nome contém numero";

        return false;
    } else {
        document.getElementById('nomeValid').innerHTML = "";
        return nome;
    }
}
function validaInputNumeros(inputId) {

    var arrey = Array.from(document.getElementById(inputId).value);
    var msg = document.getElementById(inputId + "Msg");
    var counts = {};

    if (!arrey.length) {
        msg.innerHTML = "";
        return true;
    }

    arrey.forEach(function (x) {
        counts[x] = (counts[x] || 0) + 1;

        Object.keys(counts).forEach(function (item) {
            if (Object.values(counts) >= 6) {
                msg.style.color = "red";
                msg.innerHTML = "Número inválido";
                valid = false
            } else {
                msg.innerHTML = "";
                valid = document.getElementById(inputId).value
            }
        });
    });
    if (arrey.length != false && arrey.length < 8 || arrey.length > 12) {
        msg.style.color = "red";
        msg.innerHTML = "Número inválido";
        return false;
    }
    return valid

}
function documento() {
    var input = document.getElementById("docto").value; //pega o valor digitado.
    var msg = document.getElementById('msgValid');
    if (input.length == 11 || input.length == 14) {

        // console.log(verifica_docto_repetido(input)); //verifica se é o valor de sua preferencia. se for seleciona.

        if (input.length == 11) {
            $("#TipoDocumento").val('CPF');
            var docto = input;
            var tipoDoc = 'CPF'
            return validaDocs(docto, tipoDoc);
        }
        if (input.length == 14) {
            var docto = input;
            var tipoDoc = 'CNPJ'
            $("#TipoDocumento").val('CNPJ');
            return validaDocs(docto, tipoDoc);
        }
    } else {
        msg.style.color = "blue"
        msg.innerHTML = "11 digitos para CPF e 14 para CNPJ"
        validDocumento = false;
    }
}


$('#TipoDocumento').change(function (e) {

    var val = $("#TipoDocumento option:selected").val();
    documento();
    document.getElementById('tituloIputuDocumento').innerHTML = val
});

$('#docto').keyup(function () {

    documento();

});

function validaDocs(docto, tipoDoc) {

    var msg = document.getElementById('msgValid');

    if (tipoDoc == 'CPF') {
        if (validaCPF(docto)) {

            msg.style.color = "black"
            msg.innerHTML = "CPF Válido"
            return docto;

        } else {
            msg.style.color = "red"
            msg.innerHTML = "CPF Inválido"
            return false
        }
    }
    if (tipoDoc == 'CNPJ') {
        if (validaCNPJ(docto)) {

            msg.style.color = "black"
            msg.innerHTML = "CNPJ Válido"
            return docto;


        } else {
            msg.style.color = "red"
            msg.innerHTML = "CNPJ Inválido"
            return false

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
