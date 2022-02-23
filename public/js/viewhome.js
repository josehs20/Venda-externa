
function cli(id) {
    produto_id = id;
};
$(function () {
    $("#search").keyup(function () {
        var busca = $("#search").val();
     
        $.ajax({
            url: "/busca_produto",
            type: "GET",
            data: {
                busca: busca,
            },
            dataType: 'json',
        }).done(function (response) {

            var itens = response['busca']['data'];       
            var resultado = "";
         //monta a listagem de busca de produto
            itens.forEach(element => {
resultado += '<a class="listHome" style="cursor: pointer">'
resultado +=   '<ul class="list-group">'
resultado +=       '<li class="list-group-item" style="background-color: rgb(58, 36, 252)">'
resultado +=           '<div class="listCar">'
resultado +=               '<h6 style="color: white">'+ element['nome'] +'</h6>'
resultado +=               '<button type="submit"onclick="cli('+element['id']+')"class="buttonAdd"><img class="imgCarr" src="addCar.ico" alt=""></button>'
resultado +=           '</div>'
resultado +=       '</li>'
resultado +=       '<li class="list-group-item">'
resultado +=           '<div class="listCar">'
resultado +=               '<h6> Preço :</h6>'
resultado +=               '<h4>R$'+element['preco'] +'</h4>'
resultado +=           '</div>'
resultado +=       '</li>'
resultado +=   '</ul>'
resultado += '</a>'
            });
            document.getElementById("elemento_ajax_html").innerHTML = resultado;
        });
    });
});

$(function () {
    $('form[name="addItem"]').submit(function (event) {
        event.preventDefault();
        //var global
        var id = produto_id;

        $.ajax({
            url: "/carrinho",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id,
            },
            dataType: 'json',
        }).done(function (response) {
            if (response['ok'] === true) {

                var count_itens = response['count_item'];
                $('.quanti').html(count_itens);
                console.log(response);
                //msg de success
                var mensagem = "Produto " + response['produto_adicionado'] + " Adicionado Com Sucesso!!!";
                var tipo = 'success';
                var tempo = 2000;

                mostraDialogo(mensagem, tipo, tempo);

            } else if (response['ok'] == "add") {
                var mensagem = "Adicionado mais 1 na quantidade";
                var tipo = 'warning';
                mostraDialogo(mensagem, tipo);
            } else {
                var mensagem = "Não Foi Possível";
                var tipo = 'danger';

                mostraDialogo(mensagem, tipo);
            }

        });
    });
});
function name(params) {
    
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


