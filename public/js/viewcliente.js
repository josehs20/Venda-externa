(function (win, doc) {
    'use strict'
//view cliente/vendaSalva
function fechaModal(id) {
    var botao = document.getElementById("fechaModalPinci");  
 console.log(botao);
    botao.click();
}
function fechaModalSalvaVenda(){
    var botao = document.getElementById("fechaModalSalva");  
    botao.click();
}
function reloadpag(){
    var botao = document.getElementById("fechaModalSalva");  
    botao.click();
    location.reload();
}


//View cliente/index
function botaoInfo(id) {

    info_id = id;
}
    function deleta_obs_ajax(event) {
        event.preventDefault();

        $.ajax({
            url: "/deleta_obs/" + info_id,
            type: "DELETE",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
        }).done(function (response) {
            if (response['success'] === true) {
                document.getElementById(info_id).remove();
                var msg = 'Observação Excluida Com Sucesso!!';
                var tipo = 'success';
                mostraDialogo(msg, tipo);
            } else {
                var msg = 'Observação já Excluida, Atualize a Página!!';
                var tipo = 'warning';
                mostraDialogo(msg, tipo);
            }

        });
    }
    if (doc.querySelector('.js-del')) {
        var btn = doc.querySelectorAll('.js-del');
        for (let i = 0; i < btn.length; i++) {
            btn[i].addEventListener('click', deleta_obs_ajax, false)
        }
    }

})(window, document);


$(function () {
    $("#nomeCliente").keyup(function () {
        var busca = $("#nomeCliente").val();
    
        $.ajax({
            url: "/busca_cliente",
            type: "GET",
            data: {
                nome: busca,
            },
            dataType: 'json',
        }).done(function (response) {
            //console.log(response['result']['data']);

            var clientes = response['result']['data'];
            var monta_consulta = '  <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>';
            monta_consulta += '<ul class="list-group">';

            clientes.forEach(element => {

                monta_consulta += '<li style="text-align:justify; overflow-x: auto; overflow-y: hidden;overflow-y: hidden;" class="list-group-item d-flex justify-content-between align-items-center">' + element['nome']
                monta_consulta += '<input type="hidden" name="cliente_id" value="' + element['id'] + '"/>'
                monta_consulta += '<button type="submit" class="lupa-list"><i class="bi bi-save2"></i></button>'
                monta_consulta += '</li>'

            });
            monta_consulta += '</ul>'
            document.getElementById('lisClientesModal').innerHTML = monta_consulta;
            // console.log(document.getElementById('lisClientesModal'));
        });
    });
});


function mostraDialogo(mensagem, tipo) {

    // se houver outro alert desse sendo exibido, cancela essa requisição
    if ($("#message").is(":visible")) {
        return false;
    }

    // se não setar o tempo, o padrão é 3 segundos
    var tempo = 2000;


    // se não setar o tipo, o padrão é alert-info
    if (!tipo) {
        var tipo = "info";
    }

    // monta o css da mensagem para que fique flutuando na frente de todos elementos da página
    var cssMessage = "display: block; position: fixed; top: 0; left: 20%; right: 20%; width: 60%; padding-top: 10px; z-index: 9999";
    var cssInner = "margin: 0 auto; box-shadow: 1px 1px 5px black;";

    // monta o html da mensagem com Bootstrap
    var dialogo = "";
    dialogo += '<div id="message" style="' + cssMessage + '">';
    dialogo += '    <div class="alert alert-' + tipo + ' alert-dismissable col-10" style="' + cssInner + '">';
    dialogo += mensagem;
    dialogo += '    </div>';
    dialogo += '</div>';

    // adiciona ao body a mensagem com o efeito de fade
    $("body").append(dialogo);
    $("#message").hide();
    $("#message").fadeIn(200);

    // contador de tempo para a mensagem sumir
    setTimeout(function () {
        $('#message').fadeOut(300, function () {
            $(this).remove();
        });
    }, tempo); // milliseconds
}