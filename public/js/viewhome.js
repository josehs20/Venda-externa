function cli(id) {
    produto_id = id;
};

$(function () {
    $('form[name="addItem"]').submit(function (event) {
        event.preventDefault();
        //var global
        var id = produto_id;

        var quantidade = "1";
        var tipo_uni = "UN";
        var qtd_desconto = "10";
        var desc_tipo = "porcentagem";
        var arr = [quantidade, tipo_uni, qtd_desconto, desc_tipo, produto_id];
       // console.log(arr);
        $.ajax({
            url: "/carrinho",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id,
                quantidade: quantidade,
                tipo_uni: tipo_uni,
                qtd_desconto: qtd_desconto,
                desc_tipo: desc_tipo,
            },
            dataType: 'json',
        }).done(function (response) {
            var count_itens = response['count_item']['car_item'].length;   
            $('.quanti').html(count_itens);
console.log(response);
              //msg de success
              var mensagem = 'deu certo a mensagem de successo';
              var tipo = 'info'
              var tempo = 2000

      mostraDialogo(mensagem, tipo, tempo);
           
        
        });
    });
});

//Mensagem Personalizada
function mostraDialogo(mensagem, tipo, tempo){
    
    // se houver outro alert desse sendo exibido, cancela essa requisição
    if($("#message").is(":visible")){
        return false;
    }

    // se não setar o tempo, o padrão é 3 segundos
    if(!tempo){
        var tempo = 3000;
    }

    // se não setar o tipo, o padrão é alert-info
    if(!tipo){
        var tipo = "sucess";
    }

    // monta o css da mensagem para que fique flutuando na frente de todos elementos da página
    var cssMessage = "display: block; position: fixed; top: 0; left: 20%; right: 20%; width: 60%; padding-top: 10px; z-index: 9999";
    var cssInner = "margin: 0 auto; box-shadow: 1px 1px 5px black;";

    // monta o html da mensagem com Bootstrap
    var dialogo = "";
    dialogo += '<div id="message" style="'+cssMessage+'">';
    dialogo += '    <div class="alert alert-'+tipo+' alert-dismissable" style="'+cssInner+'">';
    dialogo += '    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>';
    dialogo +=          mensagem;
    dialogo += '    </div>';
    dialogo += '</div>';

    // adiciona ao body a mensagem com o efeito de fade
    $("body").append(dialogo);
    $("#message").hide();
    $("#message").fadeIn(200);

    // contador de tempo para a mensagem sumir
    setTimeout(function() {
        $('#message').fadeOut(300, function(){
            $(this).remove();
        });
    }, tempo); // milliseconds

}