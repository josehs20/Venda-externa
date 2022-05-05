
function cli(id) {
    produto_id = id;
    gradeVefiry = false;

}
function verifyGrade(id) {
    produto_id = id;
    gradeVefiry = true;
}

// Formulário Sem Grade
$(function () {
    $('form[id="formModalSemGrade"]').submit(function (event) {
        event.preventDefault();
        var id = produto_id;
        var qtd = $('#inputProdutoSemGrade' + id).val();

        console.log(qtd);

        if (!qtd || qtd == 0) {

            Swal.fire({
                icon: 'error',
                title: "Quantidade Inválida",
                showConfirmButton: false,
                timer: 1500
            })
            return
        }
        $.ajax({
            url: "/venda",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id,
                qtd: qtd,
                i_grade_qtd: null,
            },
            dataType: 'json',
        }).done(function (response) {
            console.log(response);
            if (response['ok'] === true) {
                var count_itens = response['count_item'];
                $('#countItensCar').html(count_itens);
                Swal.fire({
                    icon: 'success',
                    title: "Produto " + response['produto_adicionado'] + " Adicionado Com Sucesso",
                    showConfirmButton: false,
                    timer: 2500

                })

                document.getElementById('fechaModalSemGrade' + id).click()



            } else if (response['ok'] == "add") {

                Swal.fire({
                    icon: 'success',
                    title: 'Quantidade Atualizada',
                    showConfirmButton: false,
                    timer: 2000
                })
                document.getElementById('fechaModalSemGrade' + id).click()
            } else {

                Swal.fire({
                    icon: 'error',
                    title: 'Não Foi Possível',
                    showConfirmButton: false,
                    timer: 1500
                })

            }
            document.getElementById('fechaModal' + id) ? document.getElementById('fechaModal' + id).click() : false
        });
    });
});
//Fomulário Com  Grade
$(function () {
    $('form[id="formModalComGrade"]').submit(function (event) {
        event.preventDefault();
        var id = produto_id;
        valida_form(id);
        $.ajax({
            url: "/venda",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id,
                qtd: null,
                i_grade_qtd: gradeVefiry ? valida_form(id) : null,
            },
            dataType: 'json',
        }).done(function (response) {
            console.log(response);
            if (response['ok'] === true) {
                var count_itens = response['count_item'];
                $('#countItensCar').html(count_itens);
                Swal.fire({
                    icon: 'success',
                    title: "Produto " + response['produto_adicionado'] + " Adicionado Com Sucesso",
                    showConfirmButton: false,
                    timer: 2500
                })
            } else if (response['ok'] == "add") {

                Swal.fire({
                    icon: 'success',
                    title: 'Quantidade Atualizada',
                    showConfirmButton: false,
                    timer: 2000
                })

            } else {

                Swal.fire({
                    icon: 'error',
                    title: 'Não Foi Possível',
                    showConfirmButton: false,
                    timer: 1500
                })

            }
            document.getElementById('fechaModalComGrade' + id) ? document.getElementById('fechaModalComGrade' + id).click() : false
        });
    });
});
//msg para grade e pega valores
function valida_form(id) {
    var camp = document.getElementById('Grade' + id);
    var checks = camp.querySelectorAll('.valid_check');

    var inputs = camp.querySelectorAll('.valid_input');
    var valid = 0;
    var dados = [];

    for (let i = 0; i < checks.length; i++) {
        if (inputs[i].value != '' || checks[i].checked) {
            valid++;
            if (!checks[i].checked) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Marque todos que contém quantidade',
                    showConfirmButton: false,
                    timer: 2500
                })
                checks = false;
            }
            if (!inputs[i].value || inputs[i].value == 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Insira a quantidade para todos que estão marcados',
                    showConfirmButton: false,
                    timer: 2500
                })
                checks = false;
            } else {

                dados[i] = [checks[i].value, inputs[i].value]
            }
        }
    }
    if (!valid || !checks) {
        return false
    } else {
        return dados;
    }
}

function inputGradeqtdInteiro(value) {
    
    var checkbox = document.getElementById("checkboxInputGrade" + value)
   // console.log(value);
    var input = document.getElementById("inputGradeqtdInteiro" + value);
 
    if (checkbox.checked) {
        input.disabled = false;

    } else {
        input.disabled = true;
    }
}

