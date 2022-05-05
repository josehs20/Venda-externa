
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
}

//habilita avista ou a prazo
function verificaAvistaAprazo(value) {
    var input = document.getElementById("inputParcelas");
    console.log(input);
    if (value == 'AP') {
        input.disabled = false;
    } else if (value == 'AV') {
        input.disabled = true;
        input.value = 1;
    }
}
function verificaDesconto(value, valorTotal, qtd_desconto_antigo) {
    var input = document.getElementById("inputDesconto");
    if (value == 'porcento') {
        input.disabled = false;

    } else if (value == 'dinheiro') {
        input.disabled = false;

    } else if (value == 0) {
        input.disabled = true;
        input.value = "";
    }

    calculoDescontoSobreVenda(valorTotal, qtd_desconto_antigo, value)
}
function calculoDescontoSobreVenda(valorTotal, qtd_desconto_antigo, tp_desconto) {
    var qtd_desconto = document.getElementById("inputDesconto").value;
    //  console.log(qtd_desconto);
    if (qtd_desconto) {
        var tp_desconto = $('#tp_desconto_sobre_venda_modal').val();
        var valorDesconto = tp_desconto == 'porcento' ? (valorTotal / 100) * qtd_desconto : qtd_desconto;

        //caso valor seja undefined
        !qtd_desconto_antigo ? qtd_desconto_antigo = 0 : false;

        var novoValorTotalModal = valorTotal - valorDesconto;
        var novoValorDescontoModal = qtd_desconto_antigo + parseFloat(valorDesconto);

        atualizaViewModalFinalizaVendaItensCarrinho(novoValorTotalModal, novoValorDescontoModal, valorDesconto)

    } else {

        var novoValorTotalModal = valorTotal;
        var novoValorDescontoModal = !qtd_desconto_antigo ? qtd_desconto_antigo = 0 : qtd_desconto_antigo;

        atualizaViewModalFinalizaVendaItensCarrinho(novoValorTotalModal, novoValorDescontoModal, valorDesconto = null)
    }

}

function atualizaViewModalFinalizaVendaItensCarrinho(novoValorTotalModal, novoValorDescontoModal, valorDesconto) {
    console.log(novoValorDescontoModal);
    // console.log(parseFloat(novoValorDescontoModal));
    document.getElementById('valorTotalModal').textContent = novoValorTotalModal.toLocaleString('pt-br', { minimumFractionDigits: 2 });
    document.getElementById('valorDescontoModal').textContent = novoValorDescontoModal.toLocaleString('pt-br', { minimumFractionDigits: 2 });

    $('#hiddenValorTotalModal').val(novoValorTotalModal);
    $('#hiddenValorDescontoModal').val(novoValorDescontoModal);
    $('#hiddenValorDescontoSobreVendaModal').val(valorDesconto);

}
$(function () {
    $('form[name="formFinalizaVenda"]').submit(function (event) {
        event.preventDefault();
        var parcelas = document.getElementById("inputParcelas").value;
        if (parcelas < 1 || parcelas % 1 != 0) {
            console.log(parcelas);
            Swal.fire({
                icon: 'error',
                title: 'Quantidade de parcelas Inválida',
                showConfirmButton: false,
                timer: 1500
            });
        } else {
            console.log(document.getElementById('formFinalizaVendaSubmit').submit());
        }

    });
});

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
            console.log(response);
            if (!response['codigo']) {
                $("#clienteNomeVenda").val("VENDA A VISTA")
                $("#clienteCodigo").val("999999")
                document.getElementById('nomeValid').innerHTML = "";
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

$(function () {
    $("#buscaNomeClienteVendaAjax").keyup(function () {
        var busca = $("#buscaNomeClienteVendaAjax").val();
console.log(busca);
        if (busca.length > 4) {


            console.log(busca);
            $.ajax({
                url: "/busca_cliente",
                type: "GET",
                data: {
                    nome: busca,
                    codigo: false,
                    id: false,
                },
                dataType: 'json',
            }).done(function (response) {
                console.log(response);

                var clientes = response['nome'];
                var monta_consulta = '  <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>';
                monta_consulta += '<ul class="list-group">';

                clientes.forEach(element => {

                    monta_consulta += '<li style="text-align:justify; overflow-x: auto; overflow-y: hidden;overflow-y: hidden;" class="list-group-item d-flex justify-content-between align-items-center">' + element['nome']
                    monta_consulta += '<button type="submit" name="cliente_id" onclick="buttonAlltech_id(' + element['alltech_id'] + ')" class="lupa-list"><i class="bi bi-save2"></i></button>'
                    monta_consulta += '</li>'

                });
                monta_consulta += '</ul>'
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
    console.log(busca);
    $.ajax({
        url: "/busca_cliente",
        type: "GET",
        data: {
            nome: busca,
            codigo: false,
            id: false,
        },
        dataType: 'json',
    }).done(function (response) {
        console.log(response);

        var clientes = response['nome'];
        var monta_consulta = '  <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>';
        monta_consulta += '<ul class="list-group">';

        clientes.forEach(element => {

            monta_consulta += '<li style="text-align:justify; overflow-x: auto; overflow-y: hidden;overflow-y: hidden;" class="list-group-item d-flex justify-content-between align-items-center">' + element['nome']
            monta_consulta += '<button type="submit" name="cliente_id" onclick="buttonAlltech_id(' + element['alltech_id'] + ')" class="lupa-list"><i class="bi bi-save2"></i></button>'
            monta_consulta += '</li>'

        });
        monta_consulta += '</ul>'
        document.getElementById('MontaBuscaClienteFinaliza').innerHTML = monta_consulta;
        // console.log(document.getElementById('lisClientesModal'));
    });
}
botaoBuscaClienteNomefinaliza.addEventListener('click', botaoBuscaClienteNomefinalizaAjax, false);

//busca alltech_id
function buttonAlltech_id(id) {
    cliente_consulta_finaliza = id;
}
$(function () {
    $('form[id="buscaAlltech_idClienteVendaAjax"]').submit(function (event) {
        event.preventDefault();
        var cliente = cliente_consulta_finaliza;

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
    });
});



//consulta cliente para salvar os intens do carrinho caso clique em search
$(function () {
    $('form[id="formSalvaItensCliente"]').submit(function (event) {
        event.preventDefault();
        console.log(botaoBuscaClienteNomeAjax);
        var busca = $("#nomeClienteSalvarItens").val();
        $.ajax({
            url: "/busca_cliente",
            type: "GET",
            data: {
                nome: busca,
                codigo: false,
                id: false

            },
            dataType: 'json',
        }).done(function (response) {
            var clientes = response['nome'];
            var monta_consulta = '  <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>';
            monta_consulta += '<ul class="list-group">';

            clientes.forEach(element => {

                monta_consulta += '<li style="text-align:justify; overflow-x: auto; overflow-y: hidden;overflow-y: hidden;" class="list-group-item d-flex justify-content-between align-items-center">' + element['nome']
                monta_consulta += '<button type="submit" name="cliente_id" value="' + element['id'] + '" class="lupa-list"><i class="bi bi-save2"></i></button>'
                monta_consulta += '</li>'

            });
            monta_consulta += '</ul>'
            document.getElementById('lisClientesModal').innerHTML = monta_consulta;

        });
    });
});

$(function () {
    $("#nomeClienteSalvarItens").keyup(function () {
        var busca = $("#nomeClienteSalvarItens").val();
        console.log(busca);
        if (busca.length >= 3) {
            $.ajax({
                url: "/busca_cliente",
                type: "GET",
                data: {
                    nome: busca,
                    codigo: false,
                    id: false,
                },
                dataType: 'json',
            }).done(function (response) {
                console.log(response);

                var clientes = response['nome'];
                var monta_consulta = '  <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>';
                monta_consulta += '<ul class="list-group">';

                clientes.forEach(element => {

                    monta_consulta += '<li style="text-align:justify; overflow-x: auto; overflow-y: hidden;overflow-y: hidden;" class="list-group-item d-flex justify-content-between align-items-center">' + element['nome']
                    monta_consulta += '<button type="submit" name="cliente_id" value="' + element['id'] + '" class="lupa-list"><i class="bi bi-save2"></i></button>'
                    monta_consulta += '</li>'

                });
                monta_consulta += '</ul>'
                document.getElementById('lisClientesModal').innerHTML = monta_consulta;
            });
        }
    });
});

function fechaModalUnificaDesconto() {
    var botao = document.getElementById("fechaModalUnificaDesconto");

    botao.click();
}