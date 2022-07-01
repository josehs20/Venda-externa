
//para modal salvar itens
var botaoBuscaClienteAjax = document.getElementById('botaoBuscaClienteAjax');

var botaoBuscaClienteNomefinaliza = document.getElementById('botaoBuscaClienteNomeAjax');
var clienteCodigoConsulta = document.getElementById('clienteCodigo');

//habilita desconto
function habilitaDescontoEditarItem(value, inputId) {
    var input = document.getElementById("inputDescontoEditarItem" + inputId);

    if (value == 'porcento') {
        input.disabled = false;
    } else if (value == 'dinheiro') {
        input.disabled = false;
    } else if (value == 0) {
        input.disabled = true;
        input.value = "";
    }
    console.log(input);
}

//habilita avista ou a prazo
function verificaAvistaAprazo(value) {
    var input = document.getElementById("inputParcelas");
    var campoInserirEntrada = document.getElementById('campoInserirEntrada');

    if (value == 'AP') {
        input.disabled = false;
        campoInserirEntrada.style.display = 'block';

    } else if (value == 'AV') {
        input.disabled = true;
        input.value = 1;
        valor_entrada.value = null;
        campoInserirEntrada.style.display = 'none';
    }
}

$(function () {
    var selectedFinaliza = document.getElementById('tp_desconto_sobre_venda_modal');
    console.log(selectedFinaliza);
    var textSelect = selectedFinaliza.dataset.valueSelected;
    text = (textSelect == 'porcento') ? '%' : (textSelect == 'dinheiro') ? '$' : '0';

    for (var i = 0; i < selectedFinaliza.options.length; i++) {
        if (selectedFinaliza.options[i].text === text) {
            selectedFinaliza.selectedIndex = i;
            if (text != '0') {
                document.getElementById('inputDesconto').disabled = false

            }
            break;
        }
    }
})

$(function () {
    var valor_desconto_sobre_venda = document.getElementById('valor_desconto_sobre_venda')
    var select_desconto_sb_venda = document.getElementById('select_desconto_sb_venda')

    if (valor_desconto_sobre_venda != null && valor_desconto_sobre_venda.value != '') {
        var select = document.getElementById('tp_desconto_sobre_venda_modal')
        select.value = select_desconto_sb_venda.value

        verificaSelectDescSobreVenda()

    }

})
function verificaSelectDescSobreVenda() {
    var input = document.getElementById('inputDesconto')
    var select = document.getElementById('tp_desconto_sobre_venda_modal')
    var dadosDesconto = { input: '', select: '' }

    if (select.value == 'porcento') {
        input.disabled = false;
        return dadosDesconto = { input: input.value, select: select.value }

    } else if (select.value == 'dinheiro') {
        input.disabled = false;
        return dadosDesconto = { input: input.value, select: select.value }

    } else if (select.value == 0) {
        input.disabled = true;
        input.value = "";

        return dadosDesconto;

    }

}
function verificaDesconto(valorTotal, qtd_desconto_antigo) {

    var dadosDesconto = verificaSelectDescSobreVenda()

    if (dadosDesconto.input != '' && dadosDesconto.select != '') {

        calculoDescontoSobreVenda(valorTotal, qtd_desconto_antigo, dadosDesconto.select)
        return
    } else {
        dadosDesconto.input = 0

        calculoDescontoSobreVenda(valorTotal, qtd_desconto_antigo, dadosDesconto.select)
    }
}

function calculoDescontoSobreVenda(valorTotal, qtd_desconto_antigo, tp_desconto) {
    var valor_desconto_sobre_venda = document.getElementById('valor_desconto_sobre_venda')
    var qtd_desconto = document.getElementById("inputDesconto");
    var valorTotal = parseFloat(valorTotal)
    var qtd_desconto_antigo = qtd_desconto_antigo == undefined ? 0 : parseFloat(qtd_desconto_antigo)

    //caso exista valor no banco de dados sobre a venda obs: só existirá caso a venda seja rejeitada pelo cotroller finalizar_venda
    if (valor_desconto_sobre_venda != null && valor_desconto_sobre_venda.value != '') {
        valor_desconto_sobre_venda = parseFloat(valor_desconto_sobre_venda.value)
        valorTotal += valor_desconto_sobre_venda
    }

    //se valor de desconto no input for vazio tem qeu atribuir 0 para evitar NaN
    qtd_desconto = qtd_desconto.value === '' ? 0 : parseFloat(qtd_desconto.value)

    //caso tipo desconto seja undefined pelo retorno da função verificaSelectDescSobreVenda
    if (tp_desconto == undefined) {
        tp_desconto = document.getElementById('tp_desconto_sobre_venda_modal').value
    }

    var valorDesconto = tp_desconto == 'porcento' && qtd_desconto != 0 ? (valorTotal / 100) * qtd_desconto : qtd_desconto;

    var novoValorTotalModal = valorTotal - valorDesconto;

    var novoValorDescontoModal = qtd_desconto_antigo + valorDesconto;

    novoValorTotalModal = novoValorTotalModal.toFixed(2);

    novoValorDescontoModal = novoValorDescontoModal.toFixed(2);

    atualizaViewModalFinalizaVendaItensCarrinho(novoValorTotalModal, novoValorDescontoModal, valorDesconto)
}

function atualizaViewModalFinalizaVendaItensCarrinho(novoValorTotalModal, novoValorDescontoModal, valorDesconto) {

    if (novoValorTotalModal < 0) {
        document.getElementById('valorTotalModal').textContent = 'Valor negativo';
        document.getElementById('valorDescontoModal').textContent = 'Valor negativo';
        document.getElementById('valorDescontoModal').style.color = 'red'
        document.getElementById('valorTotalModal').style.color = 'red'
    } else {
        document.getElementById('valorTotalModal').textContent = novoValorTotalModal.toLocaleString('pt-br', { minimumFractionDigits: 2 });
        document.getElementById('valorDescontoModal').textContent = novoValorDescontoModal.toLocaleString('pt-br', { minimumFractionDigits: 2 });
        document.getElementById('valorDescontoModal').style.color = 'black'
        document.getElementById('valorTotalModal').style.color = 'black'

    }

    $('#hiddenValorTotalModal').val(novoValorTotalModal);
    $('#hiddenValorDescontoModal').val(novoValorDescontoModal);
    $('#hiddenValorDescontoSobreVendaModal').val(valorDesconto);

}

$(function () {
    $('form[name="formFinalizaVenda"]').submit(function (event) {
        event.preventDefault();
        var parcelas = document.getElementById("inputParcelas").value;
        var cod = $("#clienteCodigo").val();
        var nome = $("#clienteNomeVenda").val();
        var valorTotal = document.getElementById('hiddenValorTotalModal')

        if (valorTotal.value < 0) {
            Swal.fire({
                icon: 'error',
                title: 'Valor de venda negativo valor: ' + valorTotal.value,
                showConfirmButton: false,
                timer: 2500
            });
            return
        }

        $.ajax({
            url: "/busca_cliente",
            type: "GET",
            data: {
                codigo: cod,
                nome: false,
                id: false
            },
            dataType: 'json',
        }).done(function (response) {

            if (response.codigo.docto == null || response.codigo.enderecos.cep == null || response.codigo.enderecos.rua == null) {
                Swal.fire({
                    icon: 'error',
                    title: 'Cadastro de cliente inválido',
                    text: !response.codigo.docto ? 'Cliente não possui docunento' : 'Endereço do cliente inválido',
                    footer: '<a class="btn btn-secondary style="color:black;" href="clientes/' + response.codigo.id + '/edit">Atualizar cadastro do cliente</a>'
                })
                return;
            }

            if (response.codigo) {

                if (parcelas < 1 || parcelas % 1 != 0) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Quantidade de parcelas Inválida',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    //entra para submit do formulário apra finalizar venda
                    var numero = response.codigo.enderecos.numero ? response.codigo.enderecos.numero : 'S/N'
                    Swal.fire({
                        title: 'Confirmação de cliente',
                        text: "You won't be able to revert this!",
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        cancelButtonText: 'Voltar',
                        confirmButtonText: 'Finalizar',

                        html:
                            '<ul class="list-group">' +
                            '  <li class="list-group-item active">' + response.codigo.nome + '</li>' +
                            '</ul>' +
                            '<br>' +
                            '<div class="list-group">' +
                            '  <a lass="list-group-item list-group-item-action" aria-current="true">' +
                            ' <div class="d-flex w-100 justify-content-between">' +
                            '<h6 class="mb-1">Cidade: ' + response.cidade.nome + '</h6>' +
                            '  </div>' +
                            '<br>' +
                            ' <div class="d-flex w-100 justify-content-between">' +
                            '<h6 class="mb-1">Bairro: ' + response.codigo.enderecos.bairro + '</h6>' +
                            '  </div>' +
                            '<br>' +
                            ' <div class="d-flex w-100 justify-content-between">' +
                            '<h6 class="mb-1">Rua: ' + response.codigo.enderecos.rua + '</h6>' +
                            '  </div>' +
                            '<br>' +

                            ' <div class="d-flex w-100 justify-content-between">' +
                            '<h6 class="mb-1">Nº: ' + numero + '</h6>' +
                            '  </div>' +
                            ' </a>' +
                            '</div>',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            console.log(document.getElementById('formFinalizaVendaSubmit').submit());
                        }
                    })


                }
            } else {
                document.getElementById('nomeValid').innerHTML = "Codigo ou nome não exitem";
            }
        });
    });
});

//pega valor no datalist 
$(function () {
    $("#clienteNomeVenda").on('change', function () {
        var _this = $(this);

        $('#listaClientes').find('option').each((index, el) => {

            if (_this.val() === el.value) {

                // prompt('', el.value + ' ' + el.label);
                // console.log('', el.value + ' ' + el.label);
                $('#clienteCodigo').val(el.label)

            }
        });
    });
})

function selectClienteFinalizaVenda() {
    if ($("#clienteNomeVenda").val() == "VENDA A VISTA") {
        $("#clienteNomeVenda").val("");
    }

};
// function apagaCodigoConflitoDeBusca(params) {
//     $("#clienteCodigo").val("");
// }

//consulta cliente pelo codigo para finalizar a venda
$(function () {
    $("#clienteCodigo").keyup(function () {
        var busca = $("#clienteCodigo").val();
        $.ajax({
            url: "/busca_cliente",
            type: "GET",
            data: {
                codigo: busca,
                nome: false,
                id: false
            },
            dataType: 'json',
        }).done(function (response) {

            if (response['codigo']) {
                $("#clienteNomeVenda").val(response['codigo']['nome'])
                document.getElementById('nomeValid').innerHTML = "";
            } else {
                document.getElementById('nomeValid').innerHTML = "Código ou nome não cadastrado";
                $("#clienteNomeVenda").val("");
            }
        });
    });
});
//verifica caso saia do foco
$(function () {
    $('#clienteCodigo').change(function (e) {

        var busca = $("#clienteCodigo").val();

        $.ajax({
            url: "/busca_cliente",
            type: "GET",
            data: {
                codigo: busca,
                nome: false,
                id: false
            },
            dataType: 'json',
        }).done(function (response) {
            //console.log(response);
            if (!response['codigo'] || !response['nome']) {

                document.getElementById('nomeValid').innerHTML = "Cliente ou codigo não existem";
            } else {
                $("#clienteNomeVenda").val(response['codigo']['nome'])
                document.getElementById('nomeValid').innerHTML = "";
            }
        });
    });
});
//consulta cliente pelo nome para finalizar a venda
function fechaModalfinalizaVenda(event) {

    $("#buscaClienteNomeVendaAjax").modal({
        show: true
    });
    document.getElementById('closeModalFinalizaVenda').click();
}

function buscaClienteNomeVendaAjax(event) {
    document.getElementById('closeModalbuscaClienteNomeVendaAjax').click();
    document.getElementById('abrirModalFinalizaVendaSemCliente').click();
}

// busca cliente por tecla digitada para finalizar venda
$(function () {
    $("#buscaNomeClienteVendaAjax").keyup(function () {
        var busca = $("#buscaNomeClienteVendaAjax").val();

        if (busca.length > 10) {

            document.getElementById('MontaBuscaClienteFinaliza').innerHTML = '';
            $(".carregando").show();
            $.ajax({
                url: "/busca_cliente",
                type: "GET",
                data: {
                    nome: busca,
                    codigo: false,
                    id: false,
                },
                dataType: 'json',
                success: function (result) {
                    $(".carregando").hide();
                },
            }).done(function (response) {

                if (response.nome.length) {
                    var clientes = response['nome'];
                    var monta_consulta = '  <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>';
                    monta_consulta += '<ul class="list-group">';

                    clientes.forEach(element => {

                        monta_consulta += '<li style="text-align:justify; overflow-x: auto; overflow-y: hidden;overflow-y: hidden; width: 93%;" class="list-group-item d-flex justify-content-between align-items-center mx-2">' + element['nome']
                        monta_consulta += '<button type="submit" name="cliente_id" onclick="buttonAlltech_id(' + element['alltech_id'] + ')" class="lupa-list"><i class="bi bi-save2"></i></button>'
                        monta_consulta += '</li>'

                    });
                    monta_consulta += '</ul>'
                } else {
                    var monta_consulta = '  <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>';

                    monta_consulta += '<div class="alert alert-warning" role="alert">'
                    monta_consulta += 'Nome não encontrado na nossa base de dados'
                    monta_consulta += '</div>'

                }

                document.getElementById('MontaBuscaClienteFinaliza').innerHTML = monta_consulta;
                // console.log(document.getElementById('lisClientesModal'));
            });
        }
    })
})

//caso clique no search para buscar clientes em modal de finalizar sem cliente
function botaoBuscaClienteNomefinalizaAjax(event) {
    event.preventDefault();


    var busca = $("#buscaNomeClienteVendaAjax").val();
    document.getElementById('MontaBuscaClienteFinaliza').innerHTML = '';
    $(".carregando").show();
    $.ajax({
        url: "/busca_cliente",
        type: "GET",
        data: {
            nome: busca,
            codigo: false,
            id: false,
        },
        dataType: 'json',
        success: function (result) {
            $(".carregando").hide();
        },
    }).done(function (response) {
        var clientes = response.nome
        var array = Object.keys(clientes)
            .map(function (key) {
                return clientes[key];
            });

        if (array.length) {

            var clientes = response.nome

            var monta_consulta = '  <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>';
            monta_consulta += '<ul class="list-group">';

            Object.keys(response.nome)
                .map(function (key) {
                    monta_consulta += '<a name="cliente_id" type="button" onclick="buttonAlltech_id(<?php echo $cliente->alltech_id; ?>)">'
                    monta_consulta += '<li style="text-align:justify; overflow-x: auto; overflow-y: hidden;overflow-y: hidden; width: 93%;" class="list-group-item d-flex justify-content-between align-items-center mx-2">' + clientes[key]['nome']
                    monta_consulta += '<button type="submit" name="cliente_id" onclick="buttonAlltech_id(' + clientes[key]['alltech_id'] + ')" class="lupa-list"><i class="bi bi-save2"></i></button>'
                    monta_consulta += '</li></a>'
                });

            monta_consulta += '</ul>'
            // console.log(response.nome);
        } else {
            var monta_consulta = '  <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>';

            monta_consulta += '<div class="alert alert-warning" role="alert">'
            monta_consulta += 'Nome não encontrado na nossa base de dados'
            monta_consulta += '</div>'

        }

        document.getElementById('MontaBuscaClienteFinaliza').innerHTML = monta_consulta;
        // console.log(document.getElementById('lisClientesModal'));
    });
}
botaoBuscaClienteNomefinaliza ? botaoBuscaClienteNomefinaliza.addEventListener('click', botaoBuscaClienteNomefinalizaAjax, false) : false;

function submitFormularioSalvarVenda(id) {
    document.getElementById('submitFormularioSalvarVenda' + id).click();
}
//busca alltech_id
function buttonAlltech_id(id) {
    var cliente = id;
    $.ajax({
        url: "/busca_cliente",
        type: "GET",
        data: {
            nome: false,
            codigo: cliente,
        },
        dataType: 'json',
    }).done(function (response) {
        console.log(response);
        document.getElementById('closeModalbuscaClienteNomeVendaAjax').click();
        document.getElementById('abrirModalFinalizaVendaSemCliente').click();

        if (response['codigo']) {
            $("#clienteNomeVenda").val(response['codigo']['nome'])
            $("#clienteCodigo").val(response['codigo']['alltech_id'])
            document.getElementById('nomeValid').innerHTML = "";
        } else {
            document.getElementById('nomeValid').innerHTML = "Código ou nome não cadastrado";
            $("#clienteNomeVenda").val("");
        }
        console.log(response);
    });
}

// $(function () {
//     $('form[id="buscaAlltech_idClienteVendaAjax"]').submit(function (event) {
//         event.preventDefault();
//         var cliente = cliente_consulta_finaliza;

//         $.ajax({
//             url: "/busca_cliente",
//             type: "GET",
//             data: {
//                 nome: false,
//                 codigo: cliente,
//             },
//             dataType: 'json',
//         }).done(function (response) {
//             console.log(response);
//             document.getElementById('closeModalbuscaClienteNomeVendaAjax').click();
//             document.getElementById('abrirModalFinalizaVendaSemCliente').click();

//             if (response['codigo']) {
//                 $("#clienteNomeVenda").val(response['codigo']['nome'])
//                 $("#clienteCodigo").val(response['codigo']['alltech_id'])
//                 document.getElementById('nomeValid').innerHTML = "";
//             } else {
//                 document.getElementById('nomeValid').innerHTML = "Código ou nome não cadastrado";
//                 $("#clienteNomeVenda").val("");
//             }
//             console.log(response);
//         });
//     });
// });



//consulta cliente para salvar os intens do carrinho caso clique em search
$(function () {
    $('form[id="formSalvaItensCliente"]').submit(function (event) {
        event.preventDefault();

        var busca = $("#nomeClienteSalvarItens").val();
        document.getElementById('lisClientesModal').innerHTML = '';
        $(".carregando").show();
        $.ajax({
            url: "/busca_cliente",
            type: "GET",
            data: {
                nome: busca,
                codigo: false,
                id: false

            },
            dataType: 'json',
            success: function (result) {
                $(".carregando").hide();
            },
        }).done(function (response) {
            var clientes = response.nome
            var array = Object.keys(clientes)
                .map(function (key) {
                    return clientes[key];
                });

            montaListaModalSalvaVendaCliente(array, response.nome)

        });
    });
});
$(function () {
    $("#nomeClienteSalvarItens").keyup(function () {
        var busca = $("#nomeClienteSalvarItens").val();

        if (busca.length >= 10) {
            document.getElementById('lisClientesModal').innerHTML = '';
            $(".carregando").show();
            $.ajax({
                url: "/busca_cliente",
                type: "GET",
                data: {
                    nome: busca,
                    codigo: false,
                    id: false,
                },
                dataType: 'json',
                success: function (result) {
                    $(".carregando").hide();
                },
            }).done(function (response) {

                var clientes = response.nome
                var array = Object.keys(clientes)
                    .map(function (key) {
                        return clientes[key];
                    });

                montaListaModalSalvaVendaCliente(array, response.nome)

            });
        }
    });
});
function montaListaModalSalvaVendaCliente(array, objetosClientes) {
    if (array.length) {
        var clientes = objetosClientes
        var monta_consulta = '  <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>';
        monta_consulta += '<ul class="list-group">';

        var array = Object.keys(clientes)
            .map(function (key) {
                monta_consulta += '<a onclick="submitFormularioSalvarVenda(' + clientes[key]['id'] + ')">'
                monta_consulta += '<li style="text-align:justify; overflow-x: auto; overflow-y: hidden;overflow-y: hidden;" class="list-group-item d-flex justify-content-between align-items-center">' + clientes[key]['nome']
                monta_consulta += '<button type="submit" name="cliente_id" value="' + clientes[key]['id'] + '" class="lupa-list"><i class="bi bi-save2"></i></button>'
                monta_consulta += '</li></a>'

            });
        monta_consulta += '</ul>'
    } else {
        var monta_consulta = '  <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>';

        monta_consulta += '<div class="alert alert-warning" role="alert">'
        monta_consulta += 'Nome não encontrado na nossa base de dados'
        monta_consulta += '</div>'
    }
    document.getElementById('lisClientesModal').innerHTML = monta_consulta;
}
function abremodalConfDesconto() {
    var botao = document.getElementById("closemodalDesconto");
    botao.click();
}
function abremodalDesconto(params) {
    document.getElementById('modalUnificaclick').click()
}

function descInvalido() {

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger',
            cancelButtonColor: '#d33',
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: 'Venda Inválida',
        text: "O desconto aplicado excede o limite permitido pelo sistema EGI",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Solicitar liberacao?',
        cancelButtonText: 'Alterar venda',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {

            document.getElementById('formDescInvalido').submit();
        }
    })

}

