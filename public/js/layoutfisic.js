var buscaCliente = document.getElementById('buscaCliente');
var inputBuscaCliente = document.getElementById('inputBuscaCliente');
var fechaInputBuscaCliente = document.getElementById('fechaCliente');
//var menuIcon = document.querySelectorAll('.toggle-menu div')
//menu seta <- navBar
var btnMenu = document.querySelectorAll(".btnMenu div");
var btn = document.getElementById("btnMenu");

//realiza a seta
btn.addEventListener("click", function () {
    btnMenu.forEach((linha) => {
        linha.classList.toggle(linha.id);
    });
    inputBuscaCliente.classList.remove('go-back')
    inputBuscaCliente.classList.toggle('go-esconde')
});


//aparece input busca cliente
buscaCliente.addEventListener("click", function () {
    inputBuscaCliente.classList.remove('go-esconde')
    inputBuscaCliente.classList.toggle('go-back')
 
    /* forEach to make the menu an X */
    // menuIcon.forEach((line) => {
    //     line.classList.toggle(line.id)
    // })
});


function msgContato(msg) {

    if (msg === 1) {
        var msg = 'Cliente Adicionado Com Sucesso!!';
        var tipo = 'success';
        setTimeout(function () {
            mostraDialogo(msg, tipo);
        }, 100);

    } else if (msg === 2) {
        var msg = 'Observação Adicionada Com sucesso!!';
        var tipo = 'success';
        setTimeout(function () {
            mostraDialogo(msg, tipo);
        }, 100);
    } else if (msg === 3) {
        var msg = 'Não Autorizado Custo Maior Que Venda!!';
        var tipo = 'warning';
        setTimeout(function () {
            mostraDialogo(msg, tipo);
        }, 100);
    } else if (msg === 4) {
        var msg = 'Venda Salva Com sucesso!!';
        var tipo = 'success';
        setTimeout(function () {
            mostraDialogo(msg, tipo);
        }, 100);
    } else if (msg === 5) {
        var msg = 'Cliente excluido com sucesso!!';
        var tipo = 'success';
        setTimeout(function () {
            mostraDialogo(msg, tipo);
        }, 100);
    } else if (msg === 6) {
        var msg = 'Cliente Editado com sucesso!!';
        var tipo = 'success';
        setTimeout(function () {
            mostraDialogo(msg, tipo);
        }, 100);
    } else if (msg === 7) {
        var msg = 'Venda Cancelada Com sucesso!!';
        var tipo = 'success';
        setTimeout(function () {
            mostraDialogo(msg, tipo);
        }, 100);
    } else if (msg === 8) {
        var msg = 'Itens retornados com sucesso!!';
        var tipo = 'success';
        setTimeout(function () {
            mostraDialogo(msg, tipo);
        }, 100);
    } else if (msg === 9) {
        var msg = 'Item Retirado Com Sucesso!!';
        var tipo = 'success';
        setTimeout(function () {
            mostraDialogo(msg, tipo);
        }, 100);
    } else if (msg === 10) {
        var msg = 'Item Alterado Com Sucesso!!';
        var tipo = 'success';
        setTimeout(function () {
            mostraDialogo(msg, tipo);
        }, 100);
    } else if (msg === 11) {
        var msg = 'Desconto Alterado Com Sucesso!!';
        var tipo = 'success';
        setTimeout(function () {
            mostraDialogo(msg, tipo);
        }, 100);
    } else if (msg === 12) {
        var msg = 'Descontos Zerados Com Sucesso!!';
        var tipo = 'success';
        setTimeout(function () {
            mostraDialogo(msg, tipo);
        }, 100);
    } else if (msg === 13) {
        var msg = 'Quantidade Alterada Com Sucesso!!';
        var tipo = 'danger';
        setTimeout(function () {
            mostraDialogo(msg, tipo);
        }, 100);
    }
}


//Mensagem Personalizada
function mostraDialogo(mensagem, tipo, tempo) {

    // se houver outro alert desse sendo exibido, cancela essa requisição
    if ($("#message").is(":visible")) {
        return false;
    }

    // se não setar o tempo, o padrão é 3 segundos
    if (!tempo) {
        var tempo = 2000;
    }

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

