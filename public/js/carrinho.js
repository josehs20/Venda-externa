async function carrinhoFixedHome() {

    var carrinho = JSON.parse(document.getElementById('carrinhoFixedHome').getAttribute('data-carrinho'))
    var itens = carrinho.car_itens;
    var table = monta_tabela_itens_modal(itens, carrinho)

    const { value: formValues } = await Swal.fire({
        title: 'ITENS ADICIONADOS',
        width: 800,
        showCancelButton: true,
        showConfirmButton: true,
        cancelButtonText: '<a href="/home-carrinho" >Finalizar</a>',
        confirmButtonText: 'Fechar',
        html: table,
        focusConfirm: false,
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    })
}
function monta_tabela_itens_modal(itens, carrinho) {
    //soma itens e converte para real
    if (!itens.length) {

        var table = `<div class="row justify-content-center">
        <div class="alert alert-warning mt-5 col-md-8" role="alert">
            Adicione Produtos No Seu Carrinho De Vendas :)
        </div>
    </div>`
    } else {

        // const sumTotal = itens.reduce((acumulador, valorAtual) => acumulador + parseFloat(valorAtual.valor), 0)
        //     .toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });
        const total = parseFloat(carrinho.total).toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });
        const descontos = carrinho.valor_desconto == null ? 'R$ 00,00' : parseFloat(carrinho.valor_desconto).toLocaleString('pt-br', { style: 'currency', currency: 'BRL' })

        var table = `<div class="d-flex justify-content-center">
        <h5 class="card-title mx-3 text-black">Total :${total}</h5>
        <h5 class="card-title mx-3 text-black">Descontos :${descontos}</h5>
        </div>
        <div style="overflow-x:auto; width: 130%;">
        <table class="table overflow-scroll">
        <thead>
        <tr>
        <th scope="col">Nome</th>
        <th scope="col">Qtd.</th>
        <th scope="col">Preco</th>
        <th scope="col">Valor</th>
        </tr>
        </thead>
        <tbody>`;
        itens.forEach(element => {
            var tam = element.tam ? ' / ' + element.tam : ''
            table += `<tr>
        <td>${element.nome + tam}</td>
        <td>${element.quantidade}</td>
        <td>R$ ${element.preco.replace('.', ',')}</td>
        <td>R$ ${element.valor.replace('.', ',')}</td>
        </tr>`;
        });
        table += `</tbody>
    </table>
    </div>`;
    }

    return table;
}

function get_itens_carrinho() {
    var carrinho;
    $.ajax({
        url: "/itens-carrinho",
        type: "GET",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        async: false,
        success: function (response) {

            carrinho = response
            var count_itens_carrinho = document.getElementById('countItensCar');
            count_itens_carrinho.innerText = carrinho.car_itens.length

            set_atributes(carrinho)
            set_modal_finaliza_venda_atributos(carrinho);
            //set_itens_carrinho(response.car_itens)
            if (!response.tp_desconto || response.tp_desconto == 'parcial') {
                atualiza_lista_modais(true)
            } else {
                atualiza_lista_modais(false)
            }

           
            atualiza_lista_carrinho(response.car_itens)

        },
        error: function (response) {
            var msg = 'Não foi possível consultar itens';
            var icon = 'warning';
            alertTopEnd(msg, icon)
        }
    })
    return carrinho
}

function set_modal_finaliza_venda_atributos(carrinho) {
    console.log(carrinho);
    var inputDesconto = document.getElementById('inputDesconto')
    var tp_desconto_sb_venda = document.getElementById('tp_desconto_sb_venda')
    var tp_pagamento = document.getElementById('tp_pagamento')
    var inputParcelas = document.getElementById('inputParcelas')
    var inputParcelas = document.getElementById('inputParcelas')
    var forma_pagamento = document.getElementById('forma_pagamento')
    var valor_entrada = document.getElementById('valor_entrada')
}

$(function () {
    get_itens_carrinho()
})

function cards_carrinho(valores) {

    var elementos = document.querySelector('.cardsIniciais')

    var valor_bruto = parseFloat(valores.valor_bruto).toLocaleString('pt-br', { style: 'currency', currency: 'BRL' })
    var total = parseFloat(valores.total).toLocaleString('pt-br', { style: 'currency', currency: 'BRL' })
    var valor_desconto = valores.valor_desconto == null ? '0,00' : parseFloat(valores.valor_desconto).toLocaleString('pt-br', { style: 'currency', currency: 'BRL' })
    var tp_desconto = valores.tp_desconto

    if (!tp_desconto || tp_desconto == null) {
        elementos.querySelector('.tp_desconto').classList.add('d-none')
        atualiza_lista_modais(true)
    } else {
        //padroniza elemnto com tipo de desconto (parcial, unico, dinheiro ou porcentagem)
        var element_tp_desconto = elementos.querySelector('.tp_desconto')
        element_tp_desconto.classList.remove('d-none')

        if (tp_desconto == 'porcento_unico') {
            element_tp_desconto.innerText = "Único em: % " + parseFloat(valores.qtd_desconto).toFixed(2)

            atualiza_lista_modais()

        } else if (tp_desconto == 'dinheiro_unico') {
            element_tp_desconto.innerText = "Único em: R$ " + parseFloat(valores.qtd_desconto).toLocaleString('pt-br', { minimumFractionDigits: 2 })
            atualiza_lista_modais()
        } else {
            element_tp_desconto.innerText = 'Desconto Dado Parcialmente'
            atualiza_lista_modais(true)
        }

    }

    elementos.querySelector('.valorBruto').innerText = 'Subtotal: ' + valor_bruto
    elementos.querySelector('.valorTotal').innerText = 'Total: ' + total
    elementos.querySelector('.valor_desconto').innerText = 'V. Desconto: ' + valor_desconto

    document.getElementById('closemodalDesconto').click()

    // valores = {valores : { valor_bruto : valor_bruto,  total: total, valor_desconto : valor_desconto, tp_desconto : tp_desconto}}

    monta_valores_finaliza_venda(valores);
    // calculoDescontoSobreVenda(valores)

    return valores;
}
function atualiza_lista_carrinho(itens, id_div) {

    itens.forEach(element => {
        var divItem = document.getElementById('div' + element.id);
        divItem.querySelector('#itemCarrinhoPreco').innerText = parseFloat(element.preco).toLocaleString('pt-br', { style: 'currency', currency: 'BRL' })
        divItem.querySelector('#itemCarrinhoQtd').innerText = element.quantidade
        desconto_lista_item_carrinho(divItem, element)
        // divItem.querySelector('#itemCarrinhoDesconto').innerText = element
        divItem.querySelector('#itemCarrinhoValor').innerText = parseFloat(element.valor).toLocaleString('pt-br', { style: 'currency', currency: 'BRL' })
    });

}
function desconto_lista_item_carrinho(divItem, element) {
    if (element.qtd_desconto) {
        divItem.querySelector('#itemCarrinhoDesconto').classList.remove('d-none')
        divItem.querySelector('#itemCarrinhoDesconto').children[0].innerText = element.tp_desconto + ' ' + element.qtd_desconto

    } else {
        divItem.querySelector('#itemCarrinhoDesconto').classList.add('d-none')
    }
}
function atualiza_lista_modais(autorizaDesconto) {
    if (autorizaDesconto) {
        var descontoListCarrinho = document.querySelectorAll('.descontoUnicoInserido')
        descontoListCarrinho.forEach(element => {
            element.classList.add('d-none')
        });
        var listaModaisAutoriza = document.querySelectorAll('.descontoListCarrinho');
        listaModaisAutoriza.forEach(element => {
            element.classList.remove('d-none')
        });
    } else {
        //bloqueia

        var listaModaisBloqueiaDesconto = document.querySelectorAll('.descontoListCarrinho');
        listaModaisBloqueiaDesconto.forEach(element => {
            element.classList.add('d-none')
        });
        //  console.log(listaModaisBloqueiaDesconto);
        var descontoListCarrinho = document.querySelectorAll('.descontoUnicoInserido')
        descontoListCarrinho.forEach(element => {
            element.classList.remove('d-none')
        });


    }
}

function habilitaDesconto(value, inputId) {
    var input = document.getElementById(inputId);

    if (value == '%') {
        input.disabled = false;
    } else if (value == '$') {
        input.disabled = false;
    } else if (value == '') {
        input.disabled = true;
        input.value = '';
    }

    //input desconto no modal de finalizar venda
    if (inputId == 'inputDesconto') {
        calcula_valores_modal(value)
    }

}
function habilitaVendaAP(value) {
    var input = document.getElementById("inputParcelas");
    var campoInserirEntrada = document.getElementById('campoInserirEntrada');

    if (value == 'AP') {
        input.disabled = false;
        campoInserirEntrada.classList.remove('d-none');

    } else {
        input.disabled = true;
        input.value = 1;
        document.getElementById('valor_entrada').value = null;
        campoInserirEntrada.classList.add('d-none');
    }
}
// ------------Consulta lista de clientes---------------------//
function get_cliente(nome) {

    var token = Cookies.get('token_jwt');
    var clientes = null;

    $.ajax({
        url: 'http://localhost:8000/api/v1/clientes/?relations=enderecos&limit=100&filtro_cliente=nome:like:%' + nome + '%',
        type: "GET",
        headers: {
            'Authorization': 'Bearer ' + token,
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        async: false,
        success: function (response) {
            clientes = response
        }
    })

    return clientes;
}



function submitFormularioSalvarVenda(id, nome) {
    var input = document.getElementById('clienteSalvaCarrinho');
    input.value = id
    Swal.fire({
        title: 'Salvar itens',
        text: 'Deseja realmente salvar esses itens para, ' + nome + '?',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, salvar!',
        concelButtonText: 'Não',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('fomrsalvarVenda').submit()
        }
    })

}

