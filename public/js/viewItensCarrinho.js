var botao = document.getElementById('preventCliente');

function preventCliente(event) {
    event.preventDefault();
}
botao.addEventListener('click', preventCliente, false);

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