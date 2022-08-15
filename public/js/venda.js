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
            cards_carrinho(valores)
            atualiza_lista_carrinho(response.itens)
            atualiza_lista_modais(true)
            document.getElementById('qtd_desconto_unifica_desconto').value = ''
            alertPadrao('Desconto alterado com sucesso', 'success')
        },
        error: function (response) {
            alertPadrao('Não foi possível tente novamente', 'error')
        }
    })
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
                count_itens_carrinho.innerText = parseInt(count_itens_carrinho.textContent) - 1
         
                alertPadrao('Item deletado com sucesso', 'success')
            }
        },
        error: function (response) {

        },

    })

}

//funções async com formulários
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
                var itens = response.itens
                cards_carrinho(valores)
         
                atualiza_lista_carrinho(itens)
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
    var quantidade = elementos.querySelector('#quantidadeEditItemCarrinho').value
    var qtd_desconto = elementos.querySelector('#inputDescontoEditarItem' + modal_id).value
    var tp_desconto = elementos.querySelector('#selectTp_desconto').value
    var preco = preco

    var modal = document.getElementById('editaItem' + modal_id)
    modal.querySelector('#quantidadeFloat').innerText = 'Quantidade: ' + quantidade
    if (!qtd_desconto && tp_desconto) {
        alertError('Caso selecione o tipo de desconto o valor é obrigatório')
        return
    }
    if (!quantidade || quantidade == 0) {
        alertError("Quantidade Inválida")
        return
    }

    var valores = addItemCarrinho(id_alltech_id, quantidade, preco, qtd_desconto, tp_desconto)

    if (valores) {
        atualiza_lista_carrinho(itens, modal_id);
        cards_carrinho(valores.valores)
        alertPadrao('Item alterado com sucesso', 'success')
        $('#editaItem' + modal_id).click();
    } else {
        alertPadrao('Não foi possível tente novamente', 'error')
    }
}
