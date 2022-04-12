@extends('layouts.app')

@section('content')
    @include('componentes.navbar')
    @include('componentes.titulo', [
        'titlePage' => 'Cadastrar Cliente',
    ])

    @if (Session::has('clienteadd'))

        <body onload="msgContato(msg = 1)">
    @endif
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

            #uf {
                text-transform: uppercase;
            }

        </style>

        <div id="contentIndex">
            <div class="container" style="width: 99%; margin-top:65px;">
                <div class="card border-primary mb-3 mx-3">
                    <div class="card-header d-flex d-flex justify-content-center">
                        <h5>Cadastro de Cliente</h5>
                    </div>

                    <form id="CadastroCliente" class="row g-3 d-flex justify-content-center px-2">
                        @csrf
                        <div class="col-md-2">
                            <label for="inputState" class="form-label">TIPO</label>
                            <select id="TipoDocumento" name="TipoDocumento" class="form-control">
                                <option data-tipo="placeholder" value="Documento">Selecione...</option>
                                <option id="selectCPF" data-tipo="CPF" value="CPF">Física</option>
                                <option id="selectCNPJ" data-tipo="CNPJ" value="CNPJ">Jurídica</option>
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
                            <input onblur="PesquisarCEP()" id="cep" type="text" required class="form-control">
                            <a style=" color: red" id="error"></a>
                            <input type="hidden" id="cidIbge">
                        </div>
                        <div class="col-md-1">
                            <label for="inputCity" class="form-label">UF</label>
                            <input type="text" required class="form-control" id="uf">
                        </div>
                        <div class="col-md-5">
                            <label for="inputAddress2" class="form-label">Cidade</label>
                            <input type="text" onblur="PesquisarCepCidade()" required id="cidade" class="form-control">

                        </div>
                        <div class="col-md-4">
                            <label for="inputCity" class="form-label">Bairro</label>
                            <input type="text" required class="form-control" id="bairro">
                        </div>
                        <div class="col-md-5">
                            <label for="inputCity" class="form-label">Rua</label>
                            <input type="text" required class="form-control" id="rua">
                        </div>
                        <div class="col-md-1">
                            <label for="inputCity" class="form-label">Numero</label>
                            <input type="number" id="numero" class="form-control" placeholder="S/N">
                        </div>
                        <div class="col-md-4">
                            <label for="inputCity" class="form-label">Complemento</label>
                            <input type="text" class="form-control" id="compto">
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
    </div>
@endsection
<script type="text/javascript" src="{{ asset('js/valida-documento.js') }}" defer></script>
