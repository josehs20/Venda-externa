

function deleta_obs_ajax(id) {
    info_id = id;
    console.log(info_id);
   
};

function confirmDel(event) {
    event.preventDefault();
    console.log(event);
}


$(function () {
    $('form[name="infoObs"]').submit(function (event) {
        event.preventDefault();
        //var global
        alert(info_id);

        // $.ajax({
        //     url: "/carrinho",
        //     type: "POST",
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     },
        //     data: {
        //         id: id,
        //     },
        //     dataType: 'json',
        // }).done(function (response) {
        //     if (response['ok'] === true) {

        //         var count_itens = response['count_item'];
        //         $('.quanti').html(count_itens);
        //         console.log(response);
        //         //msg de success
        //         var mensagem = "Produto " + response['produto_adicionado'] + " Adicionado Com Sucesso!!!";
        //         var tipo = 'success';
        //         var tempo = 2000;

        //         mostraDialogo(mensagem, tipo, tempo);

        //     } else if (response['ok'] == "add") {
        //         var mensagem = "Adicionado mais 1 na quantidade";
        //         var tipo = 'warning';
        //         mostraDialogo(mensagem, tipo);
        //     } else {
        //         var mensagem = "Não Foi Possível";
        //         var tipo = 'danger';

        //         mostraDialogo(mensagem, tipo);
        //     }

        // });
    });
});