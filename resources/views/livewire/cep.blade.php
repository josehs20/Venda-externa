<div>

    <style>
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        #codUf {
            text-transform: uppercase;
        }

    </style>

    <div class="container" style="width: 99%; margin-top:65px;">
        <div class="card border-primary mb-3 mx-3">
            <div class="card-header d-flex d-flex justify-content-center">
                <h5>Cadastro de Cliente</h5>
            </div>

            <form id="CadastroCliente"
                class="row g-3 d-flex justify-content-center px-2">
                @csrf
                <div class="col-md-2">
                    <label for="inputState" class="form-label">TIPO</label>
                    <select id="TipoDocumento" name="TipoDocumento" class="form-control">
                        <option data-tipo="placeholder" value="Documento">Selecione...</option>
                        <option id="selectCPF"  data-tipo="CPF" value="CPF">Física</option>
                        <option  id="selectCNPJ" data-tipo="CNPJ" value="CNPJ">Jurídica</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="inputEmail4" id="tituloIputuDocumento" class="form-label">Documento</label>
                    <input required type="number" class="form-control" placeholder="Somente Números" id="docto">
                    <a style="color: red" id="msgValid"></a>
                </div>
                <div class="col-md-3">
                    <label for="inputEmail4" class="form-label">Nome</label>
                    <input type="text" required onblur="validaInputNome()" class="form-control" id="inputNome">
                    <a style="color: red" id="nomeValid"></a>
                </div>
                <div class="col-md-4">
                    <label for="inputEmail4" class="form-label">Email</label>
                    <input type="email" class="form-control" id="inputEmail">
                </div>


                <div class="col-md-4">
                    <label for="inputEmail4" class="form-label">Telefone 1</label>
                    <input type="number" onblur="validaInputNumeros('inputTel1')" minlength="8" maxlength="12"
                        class="form-control" id="inputTel1">
                    <a style="color: red" id="inputTel1Msg"></a>
                </div>
                <div class="col-md-4">
                    <label for="inputEmail4" class="form-label">Telefone 2</label>
                    <input type="number" class="form-control" minlength="8" maxlength="12"
                        onblur="validaInputNumeros('inputTel2')" id="inputTel2">
                    <a style="color: red" id="inputTel2Msg"></a>
                </div>
                <div class="col-md-4">
                    <label for="inputEmail4" class="form-label">Celular</label>
                    <input type="number" onblur="validaInputNumeros('celular')" minlength="8" maxlength="12"
                        class="form-control" id="celular">
                    <a style="color: red" id="celularMsg"></a>
                </div>


                <div class="col-md-2">
                    <label for="inputAddress" class="form-label">CEP</label>
                    <input id="inputCep" type="text" required wire:model.defer="cep" wire:model.lazy="cep" value="{{ $cep }}"
                        class="form-control">
                        <a style="color: red" id="validCep">{{$validCep}}</a>
                </div>
                <div class="col-md-1">
                    <label for="inputCity" wire:model.defer="uf" class="form-label">UF</label>
                    <input type="text" required wire:model.defer="uf" wire:model="uf" class="form-control" id="inputUf">
                </div>
                <div class="col-md-5">
                    <label for="inputAddress2" class="form-label">Cidade</label>
                    <input type="text" required wire:model.lazy="cidade" wire:model.defer="cidade" id="inputCidade"
                        class="form-control">
                        <a style="color: red" id="validCidade">{{$validCidade}}</a>
                </div>
                <div class="col-md-4">
                    <label for="inputCity" class="form-label">Bairro</label>
                    <input type="text" required wire:model.defer="bairro" class="form-control" id="inputBairro">
                </div>
                <div class="col-md-5">
                    <label for="inputCity" class="form-label">Rua</label>
                    <input type="text" required wire:model.defer="rua" class="form-control" id="inputRua">
                </div>
                <div class="col-md-1">
                    <label for="inputCity" class="form-label">Numero</label>
                    <input type="number" class="form-control" id="inputNumero" placeholder="S/N">
                </div>
                <div class="  col-md-4">
                    <label for="inputCity" class="form-label">Complemento</label>
                    <input type="text" class="form-control" id="inputCompto">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary mx-4" style="margin-top: 30px">Cadastrar</button>
                </div>
                <div id="divResultado"></div>
                <div class="col-md-4">
                </div>
            </form>
        </div>
    </div>
</div>
