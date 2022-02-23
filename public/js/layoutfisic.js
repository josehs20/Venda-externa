var btnMenu = document.querySelectorAll(".btnMenu div");
var btn = document.getElementById("btnMenu");
btn.addEventListener("click", function(){
    btnMenu.forEach((linha) => {
        linha.classList.toggle(linha.id);
    });
});

function msgContato(msg) {
   
    if (msg) {
        var msg = 'Cliente Adicionado Com Sucesso';
        var tipo = 'success';
        setTimeout(function(){
            console.log("I am the third log after 5 seconds");
            mostraDialogo(msg, tipo);
        },100);
      
    }
}


//Mensagem Personalizada
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

