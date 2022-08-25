<style>
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }


</style>

<div class="col-md-2">
    <label for="inputState" class="form-label">TIPO</label>
    <select id="TipoDocumento" name="TipoDocumento" class="form-control">
        @if ($cliente && $cliente->tipo == 'F')
            <option id="selectCPF" data-tipo="CPF" selected disabled="" value="CPF">Física</option>
        @elseif($cliente && $cliente->tipo == 'J')
            <option id="selectCNPJ" data-tipo="CNPJ" selected disabled="" value="CNPJ">Jurídica
            </option>
        @else
            <option data-tipo="placeholder" value="Documento">Selecione...</option>
            <option id="selectCPF" data-tipo="CPF" value="CPF">Física</option>
            <option id="selectCNPJ" data-tipo="CNPJ" value="CNPJ">Jurídica</option>
        @endif

    </select>
</div>
<div class="col-md-3">
    <label for="inputEmail4" id="tituloIputuDocumento" class="form-label">Documento</label>
   
    @if ($cliente && $cliente->docto)
   
        <input disabled="" type="number" class="form-control" value="{{ $cliente ? $cliente->docto : '' }}"
            id="docto">
    @else
        <input required type="number" class="form-control" placeholder="Somente Números" id="docto">
        <a style="color: red" id="msgValid"></a>
    @endif
</div>
<div class="col-md-3">
    <label for="inputEmail4" class="form-label">Nome</label>
    @if ($cliente)
        <input type="text" id="inputNome" onkeyup="removeCarcterEspecial(this.value, 'inputNome')" readonly value="{{ $cliente ? $cliente->nome : '' }}"
            class="form-control">
    @else
        <input type="text" onkeyup="removeCarcterEspecial(this.value, 'inputNome')" required onblur="validaInputNome()" class="form-control" id="inputNome">
        <a style="color: red" id="nomeValid"></a>
    @endif


</div>
<div class="col-md-4">
    <label for="inputEmail4" class="form-label">Email</label>
    <input type="email" class="form-control" id="inputEmail" value="{{ $cliente ? $cliente->email : '' }}">
</div>


<div class="col-md-4">
    <label for="inputEmail4" class="form-label">Telefone 1</label>
    <input type="number" onblur="validaInputNumeros('inputTel1')" minlength="8" maxlength="12" class="form-control"
        id="inputTel1" value="{{ $cliente ? $cliente->fone1 : '' }}">
    <a style="color: red" id="inputTel1Msg"></a>
</div>
<div class="col-md-4">
    <label for="inputEmail4" class="form-label">Telefone 2</label>
    <input type="number" class="form-control" minlength="8" maxlength="12" onblur="validaInputNumeros('inputTel2')"
        id="inputTel2" value="{{ $cliente ? $cliente->fone2 : '' }}">
    <a style="color: red" id="inputTel2Msg"></a>
</div>
<div class="col-md-4">
    <label for="inputEmail4" class="form-label">Celular</label>
    <input type="number" onblur="validaInputNumeros('celular')" minlength="8" maxlength="12" class="form-control"
        id="celular" value="{{ $cliente ? $cliente->celular : '' }}">
    <a style="color: red" id="celularMsg"></a>
</div>

<div class="col-md-2">
    <label for="inputAddress" class="form-label">CEP</label>
    <input onblur="PesquisarCEP()" onkeyup="PesquisarCEP()" id="cep" type="text" required
        value="{{ $cliente && $cliente->enderecos ? $cliente->enderecos->cep : '' }}" class="form-control">
    <a style=" color: red" id="error"></a>
    <input type="hidden" id="cidIbge"
        value="{{ $cliente && $cliente->enderecos && $cliente->enderecos->cidadeIbge ? $cliente->enderecos->cidadeIbge->codigo : '' }}">
</div>
<div class="col-md-1">
    <label for="inputCity" class="form-label">UF</label>
    <input type="text" required onkeyup="removeCarcterEspecial(this.value, 'uf')"
        value="{{ $cliente && $cliente->enderecos && $cliente->enderecos->cidadeIbge ? $cliente->enderecos->cidadeIbge->uf : '' }}"
        class="form-control" id="uf">
</div>
<div class="col-md-5">
    <label for="inputAddress2" class="form-label">Cidade</label>
    <input type="text" onkeyup="removeCarcterEspecial(this.value, 'cidade')" onblur="PesquisarCepCidade()" required id="cidade"
        class="form-control"
        value="{{ $cliente && $cliente->enderecos && $cliente->enderecos->cidadeIbge ? $cliente->enderecos->cidadeIbge->nome : '' }}">
</div>
<div class="col-md-4">
    <label for="inputCity" class="form-label">Bairro</label>
    <input type="text" required onkeyup="removeCarcterEspecial(this.value, 'bairro')"
        value="{{ $cliente && $cliente->enderecos && $cliente->enderecos->cidadeIbge ? $cliente->enderecos->bairro : null }}"
        class="form-control" id="bairro">
</div>
<div class="col-md-5">
    <label for="inputCity" class="form-label">Rua</label>
    <input type="text" onkeyup="removeCarcterEspecial(this.value, 'rua')" required value="{{ $cliente && $cliente->enderecos ? $cliente->enderecos->rua : '' }}"
        class="form-control" id="rua">
</div>
<div class="col-md-1">
    <label for="inputCity" class="form-label">Numero</label>
    <input type="number" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" id="numero" class="form-control"
        value="{{ $cliente && $cliente->enderecos ? $cliente->enderecos->numero : '' }}" placeholder="S/N">
</div>
<div class="col-md-4">
    <label for="inputCity" class="form-label">Complemento</label>
    <input type="text" class="form-control" id="compto" onkeyup="removeCarcterEspecial(this.value, 'compto')"
        value="{{ $cliente && $cliente->enderecos ? $cliente->enderecos->compto : '' }}">
</div>
<div class="col-md-2">
    <button id="idCliente" class="btn btn-primary mx-4" style="margin-top: 30px"
        value="{{ $cliente ? $cliente->id : '' }}">{{ $cliente ? 'Atualiza' : 'Cadastrar' }}</button>
</div>
<div id="divResultado"></div>
<div class="col-md-4">
</div>
