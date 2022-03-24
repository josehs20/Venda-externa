
function cli(id) {
    produto_id = id;
    gradeVefiry = false;
  
}
function verifyGrade(id) {
    produto_id = id;
    gradeVefiry = true;
}

$(function () {
    $("#search").keyup(function () {
        var busca = $("#search").val();
        console.log(busca);
        $.ajax({
            url: "/busca_produto",
            type: "GET",
            data: {
                busca: busca,
            },
            dataType: 'json',
        }).done(function (response) {
            //console.log(response);
            //atualiza lista de busca

            var resultado = "";

            //monta a listagem de busca de produto
            response['produtos'].forEach(element => {
                resultado += '<a class="listHome" style="cursor: pointer">'
                resultado += '<ul class="list-group">'
                resultado += '<li class="list-group-item" style="background-color: rgb(58, 36, 252)">'
                resultado += '<div class="listCar">'
                resultado += '<h6 style="color: white">' + element['nome'] + '</h6>'

                if (element['grades']) {
                    resultado += '<button type="button" class="buttonAdd" data-bs-toggle="modal" data-bs-target="#Grade' + element['id'] + '"><img id="imgg" class="imgCarr" src="addCar.ico" alt=""></button>'


                    resultado += '<div class="modal fade" id="Grade' + element['id'] + '" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">'
                    resultado += '<div class="modal-dialog modal-dialog-scrollable">'
                    resultado += '<div class="modal-content">'
                    resultado += '<div class="modal-header">'
                    resultado += '<h5 class="modal-title" id="staticBackdropLabel">' + element['nome'] + '/ ' + element['grades']['nome'] + '</h5>'
                    resultado += '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'
                    resultado += '</div>'
                    resultado += '<div class="modal-body">'

                    element['grades']['i_grades'].forEach(ig => {

                        resultado += '<div class="input-group mb-3">'
                        resultado += '<div class="input-group-text">'
                        resultado += '<input class="form-check-input mt-0" type="checkbox" value="' + ig['id'] + '">'
                        resultado += '</div>'
                        resultado += '<div class="input-group-text">'
                        resultado += '<span class="">' + ig['tam'] + '</span>'
                        resultado += '</div>'
                        resultado += '<input class="form-control" type="number" min="0.01" step="0.01" placeholder="Quantidade">'
                        resultado += '</div>'

                    });
                    resultado += '</div>'
                    resultado += '<div class="modal-footer">'
                    resultado += '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sair</button>'
                    resultado += '<button type="submit" onclick="cli(' + element['id'] + ')" class="btn btn-primary">Adicionar</button>'
                    resultado += '</div>'
                    resultado += '</div>'
                    resultado += '</div>'
                    resultado += '</div>'

                } else {
                    resultado += '<button type="submit" onclick="cli(' + element['id'] + ')"class="buttonAdd"><img class="imgCarr" src="addCar.ico" alt=""></button>'
                }
                resultado += '</div>'
                resultado += '</li>'
                resultado += '<li class="list-group-item">'
                resultado += '<div class="listCar">'
                resultado += '<h6> Preço :</h6>'
                resultado += '<h4>' + Intl.NumberFormat('pt-br', { style: 'currency', currency: 'BRL' }).format(element['preco']) + '</h4>'
                resultado += '</div>'
                resultado += '</li>'
                resultado += '</ul>'
                resultado += '</a>'


            });
            return document.getElementById("elemento_ajax_html").innerHTML = resultado;

        });
    });
});

    $(function () {
        $('form[name="addItem"]').submit(function (event) {
            event.preventDefault();
    
            var id = produto_id;

            $.ajax({
                url: "/venda",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: id,
                    i_grade_qtd: gradeVefiry ? valida_form(id) : null,
                },
                dataType: 'json',
            }).done(function (response) {
                  console.log(response);
                if (response['ok'] === true) {
                    var count_itens = response['count_item'];
                    $('.quantiCar').html(count_itens);
    
                    var mensagem = "Produto " + response['produto_adicionado'] + " Adicionado Com Sucesso!!!";
                    var tipo = 'success';
                    var tempo = 2000;
    
                    mostraDialogo(mensagem, tipo, tempo);
    
                } else if (response['ok'] == "add") {
                    // console.log(response);
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

    function valida_form(id) {

        var camp = document.getElementById('Grade' + id);
        var checks = camp.querySelectorAll('.valid_check');
        if (!checks) {
            console.log(checks);    
        }
        
        var inputs = camp.querySelectorAll('.valid_input');
        var valid = 0;
        var dados = []
    
    
        for (let i = 0; i < checks.length; i++) {
            if (checks[i].checked && inputs[i].value != '') {
                valid++;
                dados[i] = [checks[i].value, inputs[i].value];
                
            }
        }
        if (!valid) {
    
            console.log('nenhum item marcado');
            var msg = 'Selecione o Campo selecionado';
            var tipo = 'warning';
            mostraDialogo(msg, tipo)
            return false
        }else{
            return dados;
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
    dialogo += '    <div class="alert alert-' + tipo + ' alert-dismissable col-5" style="' + cssInner + '">';
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


