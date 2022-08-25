function modalItensVendaFinalizada(carrinho, produtos) {
    var itens = adiciona_nome_tam_item(carrinho.car_itens, produtos)
    var table = monta_tabela_itens_modal(itens, carrinho)
    var carrinhoAtual = get_itens_carrinho()

    Swal.fire({
        title: 'ITENS',
        width: 800,
        showCancelButton: true,
        cancelButtonText: 'Fechar',
        html: table,
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
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