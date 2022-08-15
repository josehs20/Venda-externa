function modalItensVendaSalva(carrinho, produtos) {
    var itens = adiciona_nome_tam_item(carrinho.car_itens, produtos)
    var table = monta_tabela_itens_modal(itens)
    var carrinhoAtual = get_itens_carrinho()

    Swal.fire({
        title: 'ITENS',
        width: 800,
        showCancelButton: true,
        showConfirmButton: true,
        cancelButtonText: 'Fechar',
        confirmButtonText: 'Finalizar',
        html: table,
        focusConfirm: false,
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            
            if (carrinhoAtual.car_itens.length && !carrinhoAtual.cliente_id_alltech) {
                confirm_substitui_carrinho(carrinho, 'Seu carrinho atual contém itens, caso continue ele sera substituído sem salvar')
            } else{
                confirm_substitui_carrinho(carrinho, 'Deseja realmente retornar essa venda?')
            }
        }
    })
}

function adiciona_nome_tam_item(itens, produtos) {

    itens.forEach(element => {
        var tam = false
        if (produtos[element.estoque_id_alltech].i_grade) {
            var tam = produtos[element.estoque_id_alltech].i_grade.tam
        }

        element.nome = produtos[element.estoque_id_alltech].produto.nome
        element.tam = tam ? tam : null;

    });
    return itens;
}