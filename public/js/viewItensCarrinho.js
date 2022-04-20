//para modal salvar itens
var botaoBuscaClienteAjax = document.getElementById('botaoBuscaClienteAjax');

var botaoBuscaClienteNomefinaliza = document.getElementById('botaoBuscaClienteNomeAjax');
var clienteCodigoConsulta = document.getElementById('clienteCodigo');


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