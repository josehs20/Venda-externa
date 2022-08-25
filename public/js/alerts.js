function alertTopEnd(msg, icon) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    Toast.fire({
        icon: icon,
        title: msg
    })
}
function alertError(text) {
    Swal.fire({
        position: 'top-end',
        icon: 'error',
        text: text,
        showConfirmButton: false,
        timer: 2500
    })
}
function alertPadrao(msg, icon, text) {
    Swal.fire({
        position: 'top-end',
        icon: icon,
        title: msg,
        text: text,
        showConfirmButton: false,
        timer: 3000
    })
}

function confirmacaoZerarDesconto() {
    Swal.fire({
        text: 'Deseja realmente zerar desconto deste carrinho',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'SIM',
        cancelButtonText: 'NÃO'
    }).then((result) => {
        if (result.isConfirmed) {
            zera_desconto()
        }
    })
}

function confirmacaoCancelarVenda(id) {
    Swal.fire({
        text: 'Deseja realmente cancelar esta venda ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'SIM',
        cancelButtonText: 'NÃO'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/venda/' + id,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function (response) {
                    window.location.href = "/venda";
                }
            })


        }
    })
}

function confirmDeleteItem(id) {
    Swal.fire({
        text: 'Deseja realmente excluir este item da venda ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'SIM',
        cancelButtonText: 'NÃO'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteItemCarrinho(id)
        }
    })
}

function confirm_substitui_carrinho(carrinho, texto) {

    Swal.fire({
        text: texto,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'SIM',
        cancelButtonText: 'NÃO'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/substitui-carrinho/' + carrinho.id,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                async: false,
                success: function (response) {
                    if (response === true) {
                        window.location.href = "/home-carrinho";
                    } else {
                        alertError('Não foi possível tente novamente');
                    }
                },
                error: function (response) {
                    alertError('Não foi possível tente novamente');
                }
            })
        }
    })
}
function salvarItensRetornados(cliente, id) {
    console.log(cliente);
    Swal.fire({
        text: 'Esse carrinho já esta salvo para ' + cliente + ', deseja retorná-lo para salvo?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'SIM',
        cancelButtonText: 'NÃO'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/salvar_venda",
                type: "PUT",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: {
                    cliente_id: id,
                },
                success: function (response) {

                    if (response === true) {
                        window.location.href = "/venda";
                    } else {
                        alertPadrao('não foi possível tente novamente', 'error')
                    }
                },
                error: function (response) {
                }
            })
        }
    })
}