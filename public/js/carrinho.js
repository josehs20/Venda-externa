async function carrinhoFixedHome() {

    var itens = this.itens
    var table = monta_tabela_itens_modal(itens)

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
function monta_tabela_itens_modal(itens) {
    //soma itens e converte para real
    if (!itens.length) {

        var table = `<div class="row justify-content-center">
        <div class="alert alert-warning mt-5 col-md-8" role="alert">
            Adicione Produtos No Seu Carrinho De Vendas :)
        </div>
    </div>`
    } else {

        const sumTotal = itens.reduce((acumulador, valorAtual) => acumulador + parseFloat(valorAtual.valor), 0)
            .toLocaleString('pt-br', { style: 'currency', currency: 'BRL' });

        var table = `<div class="d-flex justify-content-center">
        <h5 class="card-title mx-3 text-primary">Total :${sumTotal}</h5>
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
            var tam = element.tam ? ' / ' +element.tam : ''
            table += `<tr>
        <td>${element.nome  + tam}</td>
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
    var carrinhoAtual;
    $.ajax({
        url: "/itens-carrinho",
        type: "GET",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        async:false,
        success: function (response) {
            carrinhoAtual = response
            set_itens_carrinho(response.car_itens)
            if (!response.tp_desconto || response.tp_desconto == 'parcial') {
                atualiza_lista_modais(true)
            } else {
                atualiza_lista_modais(false)
            }
            atualiza_lista_carrinho(response.car_itens);
            
            // console.log(response.tp_desconto);
        },
        error: function (response) {
            var msg = 'Não foi possível consultar itens';
            var icon = 'warning';
            alertTopEnd(msg, icon)
        }
    })
    return carrinhoAtual
}

function set_itens_carrinho(itens) {
    var count_itens_carrinho = document.getElementById('countItensCar');
    count_itens_carrinho.innerText = itens ? itens.length : count_itens_carrinho.innerText

    this.itens = typeof itens == 'object' ? itens : JSON.parse(itens)

    return this.itens;
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


function habilitaDescontoEditarItem(value, inputId) {
    var input = document.getElementById("inputDescontoEditarItem" + inputId);

    if (value == '%') {
        input.disabled = false;
    } else if (value == '$') {
        input.disabled = false;
    } else if (value == 0) {
        input.disabled = true;
        input.value = "";
    }
}

// ------------Consulta lista de clientes---------------------//
$(function () {
    $('form[id="formSalvaItensCliente"]').submit(function (event) {
        event.preventDefault()
        var nome = document.getElementById('nomeClienteSalvarItens').value
        var token = Cookies.get('token_jwt');

        $.ajax({
            url: `http://localhost:8000/api/v1/clientes/?relations=enderecos&filtro_cliente=nome:like:%${nome}%`,
            type: "GET",
            headers: {
                'Authorization': 'Bearer ' + token,
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function (response) {
                var clientes = response
                var lista = document.getElementById('lisClientesModal');
                if (!clientes.length) {
                    console.log(document.getElementById('ulClientesModal').remove());

                    lista.innerHTML = ` <div class="row justify-content-center">
                                <div class="alert alert-warning mt-5 col-md-8" role="alert">
                                      Nenhum registro com esse nome encontrado !
                                 </div>
                             </div>`
                    return;
                }
                var table = `<h5 class="modal-title" id="exampleModalLabel">Clientes</h5>
                <ul class="list-group">`
                clientes.forEach(element => {
                    table += monta_lista_cliente(element)
                });

                table += `</ul>`
                lista.innerHTML = table;
            }
        })
    })
})
function monta_lista_cliente(cliente) {
    var list = ` <a onclick="submitFormularioSalvarVenda(${cliente.id})">
         <li style="text-align:justify; overflow-x: auto; overflow-y: hidden;overflow-y: hidden;"
             class="list-group-item d-flex justify-content-between align-items-center">
                ${cliente.nome}
             <button onclick="submitFormularioSalvarVenda(${cliente.id})" class="lupa-list"><i
                     class="bi bi-save2"></i></button>
         </li>
     </a>`

    return list
}
function submitFormularioSalvarVenda(id) {

    $.ajax({
        url: "/salvar_venda",
        type: "PUT",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        data:{
            cliente_id: id,
            salvaSubstitui: subs,
        },
        success: function (response) {

          if (response === true) {
            window.location.href = "/venda";
          }else{
            alertPadrao('não foi possível tente novamente', 'error')
          }
        },
        error: function (response) {
        }
    })

}

