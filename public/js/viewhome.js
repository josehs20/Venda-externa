
// function cli(id) {
//     produto_id = id;
//     gradeVefiry = false;

// }
// function verifyGrade(id) {
//     produto_id = id;
//     gradeVefiry = true;
// }

// // Formulário Sem Grade
// $(function () {
//     $('form[id="formModalSemGrade"]').submit(function (event) {
//         event.preventDefault();
//         var id = produto_id;
//         var qtd = $('#inputProdutoSemGrade' + id).val();

//         console.log(qtd);

//         if (!qtd || qtd == 0) {

//             Swal.fire({
//                 icon: 'error',
//                 title: "Quantidade Inválida",
//                 showConfirmButton: false,
//                 timer: 1500
//             })
//             return
//         }
//         $.ajax({
//             url: "/venda",
//             type: "POST",
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             },
//             data: {
//                 id: id,
//                 qtd: qtd,
//                 i_grade_qtd: null,
//             },
//             dataType: 'json',
//         }).done(function (response) {
//             console.log(response);
//             if (response['ok'] === true) {
//                 var count_itens = response['count_item'];
//                 $('#countItensCar').html(count_itens);

//                 const Toast = Swal.mixin({
//                     toast: true,
//                     position: 'bottom-end',
//                     showConfirmButton: false,
//                     timer: 3000,
//                     timerProgressBar: true,
//                     didOpen: (toast) => {
//                         toast.addEventListener('mouseenter', Swal.stopTimer)
//                         toast.addEventListener('mouseleave', Swal.resumeTimer)
//                     }
//                 })

//                 Toast.fire({
//                     icon: 'success',
//                     title: 'Produto ' + response['produto_adicionado'] + ' Adicionado Com Sucesso'
//                 })

//                 document.getElementById('fechaModalSemGrade' + id).click()



//             } else if (response['ok'] == "add") {

//                 const Toast = Swal.mixin({
//                     toast: true,
//                     position: 'bottom-end',
//                     showConfirmButton: false,
//                     timer: 3000,
//                     timerProgressBar: true,
//                     didOpen: (toast) => {
//                         toast.addEventListener('mouseenter', Swal.stopTimer)
//                         toast.addEventListener('mouseleave', Swal.resumeTimer)
//                     }
//                 })

//                 Toast.fire({
//                     icon: 'success',
//                     title: 'Quantidade Atualizada'
//                 })

//                 document.getElementById('fechaModalSemGrade' + id).click()
//             } else {

//                 Swal.fire({
//                     icon: 'error',
//                     title: 'Não Foi Possível Verifique com a empresa sobre o produto',
//                     showConfirmButton: false,
//                     timer: 1500
//                 })

//             }
//             document.getElementById('fechaModal' + id) ? document.getElementById('fechaModal' + id).click() : false
//         });
//     });
// });
// //Fomulário Com  Grade
// $(function () {
//     $('form[id="formModalComGrade"]').submit(function (event) {
//         event.preventDefault();
//         var id = produto_id;
//         valida_form(id);
//         $.ajax({
//             url: "/venda",
//             type: "POST",
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             },
//             data: {
//                 id: id,
//                 qtd: null,
//                 i_grade_qtd: gradeVefiry ? valida_form(id) : null,
//             },
//             dataType: 'json',
//         }).done(function (response) {
//             console.log(response);
//             if (response['ok'] === true) {
//                 var count_itens = response['count_item'];
//                 $('#countItensCar').html(count_itens);
//                 Swal.fire({
//                     icon: 'success',
//                     title: "Produto " + response['produto_adicionado'] + " Adicionado Com Sucesso",
//                     showConfirmButton: false,
//                     timer: 2500
//                 })
//                 const Toast = Swal.mixin({
//                     toast: true,
//                     position: 'bottom-end',
//                     showConfirmButton: false,
//                     timer: 3000,
//                     timerProgressBar: true,
//                     didOpen: (toast) => {
//                         toast.addEventListener('mouseenter', Swal.stopTimer)
//                         toast.addEventListener('mouseleave', Swal.resumeTimer)
//                     }
//                 })

//                 Toast.fire({
//                     icon: 'success',
//                     title: 'Produto ' + response['produto_adicionado'] + ' Adicionado Com Sucesso'
//                 })
//             } else if (response['ok'] == "add") {

//                 const Toast = Swal.mixin({
//                     toast: true,
//                     position: 'bottom-end',
//                     showConfirmButton: false,
//                     timer: 3000,
//                     timerProgressBar: true,
//                     didOpen: (toast) => {
//                         toast.addEventListener('mouseenter', Swal.stopTimer)
//                         toast.addEventListener('mouseleave', Swal.resumeTimer)
//                     }
//                 })

//                 Toast.fire({
//                     icon: 'success',
//                     title: 'Quantidade Atualizada'
//                 })

//             } else {

//                 Swal.fire({
//                     icon: 'error',
//                     title: 'Não Foi Possível Emtre em contato com a sua empresa',
//                     showConfirmButton: false,
//                     timer: 1500
//                 })

//             }
//             document.getElementById('fechaModalComGrade' + id) ? document.getElementById('fechaModalComGrade' + id).click() : false
//         });
//     });
// });
// //msg para grade e pega valores
// function valida_form(id) {
//     var camp = document.getElementById('Grade' + id);
//     var checks = camp.querySelectorAll('.valid_check');

//     var inputs = camp.querySelectorAll('.valid_input');
//     var valid = 0;
//     var dados = [];

//     for (let i = 0; i < checks.length; i++) {
//         if (inputs[i].value != '' || checks[i].checked) {
//             valid++;
//             if (!checks[i].checked) {

//                 Swal.fire({
//                     icon: 'warning',
//                     title: 'Marque todos que contém quantidade',
//                     showConfirmButton: false,
//                     timer: 2500
//                 })
//                 checks = false;
//             }
//             if (!inputs[i].value || inputs[i].value == 0) {
//                 Swal.fire({
//                     icon: 'warning',
//                     title: 'Insira a quantidade para todos que estão marcados',
//                     showConfirmButton: false,
//                     timer: 2500
//                 })
//                 checks = false;
//             } else {

//                 dados[i] = [checks[i].value, inputs[i].value]
//             }
//         }
//     }
//     if (!valid || !checks) {
//         return false
//     } else {
//         return dados;
//     }
// }

// function inputGradeqtdInteiro(value) {

//     var checkbox = document.getElementById("checkboxInputGrade" + value)
//     // console.log(value);
//     var input = document.getElementById("inputGradeqtdInteiro" + value);

//     if (checkbox.checked) {
//         input.disabled = false;

//     } else {
//         input.disabled = true;
//     }
// }

// function solicitacaoDesconto() {
//     Swal.fire({
//         icon: 'success',
//         title: 'Solicitação enviada',
//         text: 'Aguarde a aprovação do seu gerente',
//     })
// }

async function modalAddProduto(nome, preco, grade, item_estoque_id, item_carrinho, unidade_de_medida) {

    var grade = grade ? 'Tam: ' + grade : '';
    var item_carrinho = item_carrinho ? item_carrinho : 'Quantidade';
    //      <input style="width:200px;" id="quantidadeProduto" placeholder="${itemCarrinhoQuantidade}" class="swal2-input">
    const { value: formValue } = await Swal.fire({
        title: nome,
        html:
            `<div class="d-flex justify-content-center">
            <h5 class="card-title mx-3 text-primary">R$: ${preco}</h5>
            <h5 class="card-title mx-3 text-info">${grade}</h5>
            </div>
            <div class="input-group mb-3">
          <span class="input-group-text bg-white">${unidade_de_medida}</span>
          <input onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="${item_carrinho}" type="number" id="quantidadeProduto" class="form-control">`,
        focusConfirm: false,
        confirmButtonText: 'Adicionar',
        preConfirm: () => {
            return [
                document.getElementById('quantidadeProduto').value,
            ]
        }
    })

    if (formValue) {
        addItemCarrinho(item_estoque_id, formValue[0], preco)
    }

}
//item é estoque id
function addItemCarrinho(item, quantidade, preco, qtd_desconto, tp_desconto) {

    var quantidade = quantidade;
    var preco = preco.replace(',', '.');
    var carrinho = {};
    var valores;

    if (!quantidade || quantidade == 0) {
        Swal.fire({
            icon: 'error',
            text: "Quantidade Inválida",
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
        async: false,
        data: {
            id: item,
            preco: preco,
            quantidade: quantidade,
            qtd_desconto: qtd_desconto ?? qtd_desconto ,
            tp_desconto: tp_desconto ?? tp_desconto ,
        },
        dataType: 'json',
        success: function (response) {
            carrinho = response.dados;

            var elementData = document.querySelector('[data-carrinho]')
       
            elementData.setAttribute('data-carrinho', JSON.stringify(carrinho))

            var count_itens_carrinho = document.getElementById('countItensCar');
            count_itens_carrinho.innerText = carrinho.car_itens.length

            var msg = response.msg;
            var icon = 'success';
            alertTopEnd(msg, icon);
            valores = { valores: response.valores, itens: carrinho.car_itens }

        },
        error: function (response) {

            var msg = 'Não foi possível tente novamente';
            var icon = 'warning';
            alertTopEnd(msg, icon)
            confirm = false
        }
    })
    return valores
}

