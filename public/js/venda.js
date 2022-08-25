function zera_desconto() {
    $.ajax({
        url: '/zerar-desconto',
        type: 'put',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        success: function (response) {
            var valores = response.valores

            set_atributes(response.carrinho)
            cards_carrinho(valores)
            atualiza_lista_carrinho(response.carrinho.car_itens)
            atualiza_lista_modais(true)
            document.getElementById('qtd_desconto_unifica_desconto').value = ''
            alertPadrao('Desconto alterado com sucesso', 'success')
        },
        error: function (response) {
            alertPadrao('Não foi possível tente novamente', 'error')
        }
    })
}
function set_atributes(carrinho) {
    var elementData = document.getElementById('inputDesconto')
    elementData.setAttribute('data-carrinho', JSON.stringify(carrinho))
}
function deleteItemCarrinho(id) {

    $.ajax({
        url: '/deleta_item/' + id,
        type: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        success: function (response) {
            if (response == 'vazio') {
                window.location.href = "/venda";
            } else {
                var valores = response.valores
                var item = response.item
                document.getElementById('div' + item.id).remove()
                $('#editaItem' + item.id).click();
                cards_carrinho(valores)
                var count_itens_carrinho = document.getElementById('countItensCar');
                count_itens_carrinho.innerText = response.carrinho.car_itens.length
                set_atributes(response.carrinho)
                alertPadrao('Item deletado com sucesso', 'success')
            }
        },
        error: function (response) {
            alertError('Não foi possível, tente novamente')
        },

    })

}

// Unifica valores
$(function () {
    $('form[id="formUnificaDesconto"]').submit(function (event) {
        event.preventDefault()

        var qtd_desconto = document.getElementById('qtd_desconto_unifica_desconto').value
        var tp_desconto = document.getElementById('select_unifica_desconto').value

        $.ajax({
            url: '/unifica-desconto',
            type: 'PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                qtd_desconto: qtd_desconto,
                tp_desconto: tp_desconto
            },
            dataType: 'json',
            success: function (response) {

                var valores = response.valores
                cards_carrinho(valores)

                atualiza_lista_carrinho(response.carrinho.car_itens)
                var elementData = document.getElementById('inputDesconto')
                elementData.setAttribute('data-carrinho', JSON.stringify(response.carrinho))

                alertPadrao('Desconto alterado com sucesso', 'success')
            },
            error: function (response) {
                alertPadrao('Não foi possível tente novamente', 'error')
            }
        })
    })
});

function up_item_carrinho(id_alltech_id, preco, modal_id) {

    var elementos = document.getElementById('editaItem' + modal_id)
    var quantidade = elementos.querySelector('#quantidadeEditItemCarrinho' + modal_id).value
    var qtd_desconto = elementos.querySelector('#inputDescontoEditarItem' + modal_id).value
    var tp_desconto = elementos.querySelector('#selectTp_desconto' + modal_id).value
    var preco = preco
    // var modal = document.getElementById('editaItem' + modal_id)
    
    elementos.querySelector('#quantidadeFloat').innerText = 'Quantidade: ' + quantidade
    if (qtd_desconto == 0 && tp_desconto) {
        alertError('Caso selecione o tipo de desconto o valor é obrigatório')
        return
    }
    if (!quantidade || quantidade == 0) {
        alertError("Quantidade Inválida")
        return
    }

    if (!qtd_desconto && !tp_desconto) {
        qtd_desconto = 0
        tp_desconto = 0
    }

    var valores = addItemCarrinho(id_alltech_id, quantidade, preco, qtd_desconto, tp_desconto)

    if (valores) {
        atualiza_lista_carrinho(valores.itens, modal_id);
        cards_carrinho(valores.valores)
        alertPadrao('Item alterado com sucesso', 'success')
         $('#editaItem' + modal_id).click();
    } else {
        alertPadrao('Não foi possível tente novamente', 'error')
    }
}

// --------Finaliza venda---------//

function monta_valores_finaliza_venda(valores) {
    
    var modalFinaliza = document.querySelector('#modalFinalizaVenda');
    valores = { total: valores.total.toLocaleString('pt-br', { minimumFractionDigits: 2 }), valor_desconto: valores.valor_desconto ? parseFloat(valores.valor_desconto).toLocaleString('pt-br', { minimumFractionDigits: 2 }) : '0,00' }

    total = modalFinaliza.querySelector("#valorTotalModal").innerText = valores.total.toLocaleString('pt-br', { minimumFractionDigits: 2 })
    desconto = modalFinaliza.querySelector("#valorDescontoModal").innerText = valores.valor_desconto ? valores.valor_desconto.toLocaleString('pt-br', { minimumFractionDigits: 2 }) : '0,00'
}

//calcula quando tem troca de tipo de desconto
function calcula_valores_modal() {
    var tp_desconto = document.getElementById('tp_desconto_sb_venda').value
    var input = document.getElementById('inputDesconto')
    var qtd_desconto_input = input.value ? input.value : 0
    var carrinho = JSON.parse(input.getAttribute('data-carrinho'))
    var valoresAtualizados = {}


    if (qtd_desconto_input || qtd_desconto_input === 0) {
   
        qtd_desconto_atual = carrinho.qtd_desconto ? parseFloat(carrinho.qtd_desconto) : 0
       
        valor_desconto_sb_venda = carrinho.valor_desconto_sb_venda ?  parseFloat(carrinho.valor_desconto_sb_venda) : 0
        valor_desconto_atual = carrinho.valor_desconto ? parseFloat(carrinho.valor_desconto) - valor_desconto_sb_venda: 0
      
        total = parseFloat(carrinho.valor_bruto) - valor_desconto_atual
        console.log(carrinho, total);
        qtd_desconto_input = parseFloat(qtd_desconto_input)

        if (tp_desconto == '%') {

            valor_desconto_sb_venda = (qtd_desconto_input / 100 * total)
            totalAtual = total - valor_desconto_sb_venda;
            valor_desconto_atual = valor_desconto_atual + valor_desconto_sb_venda

            valoresAtualizados = {
                total: totalAtual,
                valor_desconto: valor_desconto_atual,
                valor_desconto_sb_venda: valor_desconto_sb_venda,
                qtd_desconto_sb_venda: qtd_desconto_input,
                tp_desconto_sb_venda: tp_desconto,
                valor_bruto: carrinho.valor_bruto
            }
            monta_valores_finaliza_venda(valoresAtualizados)
        } else {

            valor_desconto_sb_venda = qtd_desconto_input
            totalAtual = total - valor_desconto_sb_venda
            valor_desconto_atual = valor_desconto_atual + valor_desconto_sb_venda

            valoresAtualizados = {
                total: totalAtual,
                valor_desconto: valor_desconto_atual,
                valor_desconto_sb_venda: valor_desconto_sb_venda,
                qtd_desconto_sb_venda: qtd_desconto_input,
                tp_desconto_sb_venda: tp_desconto,
                valor_bruto: carrinho.valor_bruto
            }

            monta_valores_finaliza_venda(valoresAtualizados)
        }
console.log(valoresAtualizados);
        return { carrinho, valoresAtualizados }
    }
}

function verifica_cliente() {
    var carrinho = JSON.parse(document.getElementById('inputDesconto').getAttribute('data-carrinho'))
    var token = Cookies.get('token_jwt');

    if (carrinho.cliente_id_alltech) {
        $.ajax({
            url: 'http://localhost:8000/api/v1/clientes/?relations=enderecos&filtro_cliente=id:=:' + carrinho.cliente_id_alltech,
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function (response) {
                var cliente = response[0];
                clienteGlobal = cliente
                document.querySelector('.divBuscaCliente').classList.add('d-none')
                document.getElementById('clienteNomeVenda').value = cliente.nome
                document.getElementById('clienteCodigo').value = cliente.alltech_id

            },
            error: function (response) {
                alertTopEnd('não foi possível', 'warning')
            }

        })

    } else {
        document.getElementById('clienteCodigo').readOnly = false
    }
}
$(function () {
    verifica_cliente()
})

function fechaModalFinalizaVenda() {
    document.getElementById('closeModalFinalizaVenda').click()
}

function abreModalVenda() {
    document.getElementById('abrirModalFinalizaVenda').click()
}

var clienteGlobal = ''
function set_cliente_finaliza_venda(cliente) {
    clienteGlobal = cliente;
    document.getElementById('closeModalBuscaCliente').click()
    abreModalVenda()
    document.getElementById('clienteNomeVenda').value = cliente.nome
    document.getElementById('clienteCodigo').value = cliente.alltech_id
}

function monta_lista_busca_finaliza_venda(clientes) {
    var lista = document.getElementById('ulClientesModalFinalizaVenda');
    lista.innerHTML = "";

    if (!clientes.length) {
        lista.innerHTML = '';
        lista.innerHTML = `<div class="row justify-content-center">
        <div class="alert alert-warning mt-5 col-md-8" role="alert">
              Nenhum registro com esse nome encontrado !
         </div>
     </div>`
        return;
    }

    var list = [];

    clientes.forEach(element => {
        list += `<a onclick='set_cliente_finaliza_venda(${JSON.stringify(element)})'>
        <li style="text-align:justify; overflow-x: auto; overflow-y: hidden;overflow-y: hidden;"
            class="list-group-item d-flex justify-content-between align-items-center">
               ${element.nome}
            <button onclick="submitFormularioSalvarVenda(${element.id})" class="lupa-list"><i
               class="bi bi-save2"></i></button>
        </li>
    </a>`
    });

    lista.innerHTML = list;
}

$(function () {
    $('form[id="formBuscaClienteFinalizaVendaAjax"]').submit(function (event) {
        event.preventDefault()
        var nome = document.getElementById('nomeClienteFinalizarVenda').value
        var carregando = document.querySelectorAll('.carregando');

        carregando.forEach(element => {
            element.classList.remove('d-none')
        });

        var clientes = get_cliente(nome);

        carregando.forEach(element => {
            element.classList.add('d-none')
        });
        monta_lista_busca_finaliza_venda(clientes)
    })
})


function consulta_cliente_codigo(value) {
    var codigo = value
    var token = Cookies.get('token_jwt');
    $.ajax({
        url: `http://localhost:8000/api/v1/clientes/?relations=enderecos&limit=1&filtro_cliente=alltech_id:=:${codigo}`,
        type: "GET",
        headers: {
            'Authorization': 'Bearer ' + token,
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        success: function (response) {
            var cliente = response[0]

            if (cliente) {
                clienteGlobal = cliente;
                document.getElementById('clienteNomeVenda').value = cliente.nome
                document.getElementById('clienteCodigo').value = cliente.alltech_id
            } else {
                document.getElementById('clienteNomeVenda').value = ''
            }
        }
    })
}
$(function () {
    $('form[id="formBuscarClienteSalvarItens"]').submit(function (event) {
        event.preventDefault()
        var nome = document.getElementById('nomeClienteSalvarItens').value
        var carregando = document.querySelectorAll('.carregando');

        carregando.forEach(element => {
            element.classList.remove('d-none')
        });

        var clientes = get_cliente(nome);

        carregando.forEach(element => {
            element.classList.add('d-none')
        });

        monta_lista_cliente_salvar(clientes);
    })
})
function monta_lista_cliente_salvar(clientes) {

    var lista = document.getElementById('ulClientesModalSalvarVenda');
    lista.innerHTML = "";

    if (!clientes.length) {
        lista.innerHTML = '';
        lista.innerHTML = `<div class="row justify-content-center">
                    <div class="alert alert-warning mt-5 col-md-8" role="alert">
                          Nenhum registro com esse nome encontrado !
                     </div>
                 </div>`
        return;
    }

    var list = []

    clientes.forEach(element => {
        list += `<a onclick="submitFormularioSalvarVenda('${element.id}', '${element.nome}')">
        <li style="text-align:justify; overflow-x: auto; overflow-y: hidden;overflow-y: hidden;"
            class="list-group-item d-flex justify-content-between align-items-center">
               ${element.nome}
            <button type="button" onclick="submitFormularioSalvarVenda('${element.id}', '${element.nome}')" class="lupa-list"><i
                    class="bi bi-save2"></i></button>
        </li>
    </a>`
    });

    lista.innerHTML = list;
}


$(function () {

    var clientes = get_cliente('');
    monta_lista_cliente_salvar(clientes);
    monta_lista_busca_finaliza_venda(clientes);

})
//----------Finaliza venda---------//

$(function () {
    $('form[id="formFinalizaVenda"]').submit(function (event) {
        event.preventDefault();

        var valores = calcula_valores_modal()

        if (valores.valoresAtualizados.total <= 0) {
            alertError('Valor Inválido. Valor final de venda é menor ou igual a 0.')
            return
        } else if (clienteGlobal.docto == null || clienteGlobal.docto == '' || clienteGlobal.enderecos.cep == null || clienteGlobal.enderecos.rua == null) {
            var msg = !clienteGlobal.docto ? { title: 'Documento inválido', text: 'Verifique o documento do cliente' }
                : !clienteGlobal.enderecos.cep ? { title: 'Endereço inválido', text: 'Verifique os dados de endereço do cliente' }
                    : { title: 'Endereço inválido', text: 'Verifique os dados de endereço do cliente' }
            Swal.fire({
                title: `<strong>${msg.title}</u></strong>`,
                icon: 'warning',
                text: msg.text,
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText:
                    `<a href="/clientes/${clienteGlobal.id}/edit">Verifica cliente!</a>`,
                cancelButtonText:
                    '<a >Sair</a>',
            })
            return;
        } else {
            valores.carrinho.tipo_pagamento = document.getElementById('tp_pagamento').value
            valores.carrinho.forma_pagamento = document.getElementById('forma_pagamento').value
            valores.carrinho.parcelas = document.getElementById('inputParcelas').value
            var valor_entrada = document.getElementById('valor_entrada').value
            valores.carrinho.valor_entrada = !valor_entrada ? null : valor_entrada

            Swal.fire({
                title: '<strong>Confirmação</strong>',
                icon: 'info',
                html:
                    `<ul class="list-group list-group-flush  d-flex justify-content-between align-items-start">
                 <li class="list-group-item"><b>Cliente : </b> ${clienteGlobal.nome}</li>
                 <li class="list-group-item"><b>Cidade : </b> ${clienteGlobal.enderecos.cidade_ibge.nome}</li>
                 <li class="list-group-item"><b>Rua : </b> ${clienteGlobal.enderecos.rua} </li>
                 <li class="list-group-item"><b>Nº : </b>${clienteGlobal.enderecos.numero}</li>
               </ul>`,
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Fechar',
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: 'finaliza-venda',
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            valores: valores,
                            cliente: clienteGlobal,
                        },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success == 'invalida') {
                                Swal.fire({
                                    text: 'Lucro mínimo atingido, deseja rever os descontos ou enviar uma solicitação para o gerente sobre a venda ?',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'ENVIAR',
                                    cancelButtonText: 'ANALISAR'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        valores.carrinho.cliente_id_alltech = clienteGlobal.id
                                        envia_solicitacao(valores)
                                    }
                                })

                            } else {
                                alertPadrao('Finalizada.', 'success', 'Aguarde a confirmação no caixa EGI')

                                setTimeout(function () {
                                    window.location.href = "/venda";
                                }, 2500);
                            }
                        },
                        error: function (response) {
                            alertError('Não foi possível tente novamente em alguns instantes')
                        }
                    })

                }
            })
        }

    })
})

function envia_solicitacao(valores) {

    $.ajax({
        url: '/desconto-invalido/' + valores.carrinho.id,
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            valores: valores,
        },
        success: function (response) {
            if (response.success == true) {
             
                alertPadrao('Solicitacao enviada', 'success', 'Aguarde a aprovação do gerente')

                setTimeout(function () {
                    window.location.href = "/venda";
                }, 2500);

            } else {
                alertError('Não foi possível')
            }
        },
        error: function (response) {
            alertError('Não foi possível, tente mais tarde')
        }
    })
}

