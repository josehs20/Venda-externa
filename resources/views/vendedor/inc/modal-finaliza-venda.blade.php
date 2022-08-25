<div class="modal fade" id="modalFinalizaVenda" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Finalizar Venda
                </h5><br>
                <button type="button" id="closeModalFinalizaVenda" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <br>
            <div class="row mx-1">
                <div class="col-5">
                    <h6 class="">Total: R$
                        <b id="valorTotalModal">{{ reais($carrinho->total) }}</b>
                    </h6>
                </div>
                <div class="col-7">
                    <h6 class="">Desconto: R$
                        <b
                            id="valorDescontoModal">{{ reais($carrinho->valor_desconto) }}</b>
                    </h6>
                </div>
            </div>

            <form onsubmit="return false;" id="formFinalizaVenda" method="POST"
                action="{{ route('finaliza_venda', ['carrinho' => $carrinho->id]) }}">
                @method('PUT')
                @csrf
                <span class="mx-3"><b>Cliente Já Mencionado</b></span>
                <div class="modal-body">

                    <div class="row g-2">
                        <div class="col-8">
                            <div class="form-floating">
                                <input id="clienteNomeVenda" Readonly type="text" class="form-control" required>
                                {{-- value="{{ $carrinho->cliente->nome }}" id="floatingInputGrid"> --}}

                                <label for="floatingInputGrid">Cliente</label>

                            </div>
                            <span class="nomeValid"></span>
                        </div>
                        <div class="col-4">
                            <div class="form-floating">
                                <input onkeyup="consulta_cliente_codigo(this.value)" required name="cliente_alltech_id" Readonly id="clienteCodigo" type="number"
                                    step="1" class="form-control" {{-- value="{{ $carrinho->cliente_id ? $carrinho->cliente->alltech_id : null }}" --}} name="codigoCliente"
                                    id="floatingInputGrid">

                                <label for="floatingInputGrid">Cod.</label>
                            </div>
                        </div>
                    </div>
                    <div class="row divBuscaCliente">
                    <button type="button" onclick="fechaModalFinalizaVenda()" class="btn btn-primary float-end mt-3" data-bs-toggle="modal"
                    data-bs-target="#modalbuscaClienteNomeVendaAjax">Buscar Cliente</button>
                    {{-- <a onclick="fechaModalfinalizaVenda()" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#modalbuscaClienteNomeVendaAjax"><i class="bi-search"
                        style="color: rgb(255, 255, 255)"></i></a> --}}
                    </div>
                        <br>
                        <div class="row">
                    <span style="font-size: 14px;" class="mx-1"><u>Deseja aplicar desconto
                            sobre a
                            venda?</u></span></div>
                    <br><br>
                    <div class="row g-2">
                        <div class="col-8">
                            <div class="form-floating">
                                <input  {{$carrinho->tp_desconto_sb_venda  == '%' || $carrinho->tp_desconto_sb_venda  == '$' ? '' : 'disabled' }}  onkeyup="calcula_valores_modal()" data-carrinho='<?php echo json_encode($carrinho); ?>'
                                    name="qtd_desconto_sobre_venda" type="number" required class="form-control"
                                    min="0.01" step="0.01" value="{{ $carrinho->desconto_qtd_sb_venda }}"
                                    id="inputDesconto">
                                <label for="floatingInputGrid">Desconto</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <span></span>
                            <div class="form-floating">
                                <select name="tp_desconto_sb_venda" class="form-select" id="tp_desconto_sb_venda"
                                    onchange="habilitaDesconto(this.value, 'inputDesconto', )"
                                    aria-label="Floating label select example">
                                    <option selected value="">selecione...</option>
                                    <option {{ $carrinho->tp_desconto_sb_venda  == '%' ? 'selected' : ''}} value="%">%</option>
                                    <option {{ $carrinho->tp_desconto_sb_venda == '$' ? 'selected' : ''}} value="$">R$</option>
                                </select>
                                <label for="floatingSelectGrid">Tipo</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="form-floating">
                                <input onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" name="parcelas" {{$carrinho->tipo_pagamento == 'AP' ? '' : 'disabled'}} min="1" type="text" class="form-control"
                                    placeholder="1" value="{{$carrinho->parcelas ? $carrinho->parcelas : '1'}}" id="inputParcelas">
                                <label for="floatingInputGrid">Parcelas</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-floating">
                                <select name="tipo_pagamento" class="form-select" id="tp_pagamento"
                                    aria-label="Floating label select example" onchange="habilitaVendaAP(this.value)">
                                    <option {{$carrinho->tipo_pagamento == 'AV' ? 'selected' : ''}} value="AV">À VISTA</option>
                                    <option {{$carrinho->tipo_pagamento == 'AP' ? 'selected' : ''}} value="AP">A PRAZO</option>
                                </select>
                                <label for="floatingSelectGrid">Tipo</label>
                            </div>
                        </div>
                    </div>
                    <br>

                    {{-- Tipo pagamento --}}
                    <div class="row g-2">
                        <div id="campoInserirEntrada" class="col-12 {{$carrinho->valor_entrada ? '' : 'd-none'}}">
                            <div class="form-floating">
                                <input id="valor_entrada" value="{{$carrinho->valor_entrada ? $carrinho->valor_entrada : ''}}" name="valor_entrada" type="number" class="form-control"
                                    min="0">

                                <label for="floatingInputGrid">Aplicar algum valor de entrada ?</label>
                            </div>
                        </div>
                        <br>
                        <div class="col-12">
                            <div class="form-floating">
                                <select id="forma_pagamento" name="forma_pagamento" class="form-select">
                                    <option {{$carrinho->forma_pagamento == 'dinheiro' ? 'selected' : ''}} value="dinheiro">Dinheiro</option>
                                    <option {{$carrinho->forma_pagamento == 'cartao' ? 'selected' : ''}} value="cartao">Cartão</option>
                                    <option {{$carrinho->forma_pagamento == 'digital' ? 'selected' : ''}} value="digital">Digital</option>
                                    <option {{$carrinho->forma_pagamento == 'promissoria' ? 'selected' : ''}} value="promissoria">Promissória</option>
                                    <option {{$carrinho->forma_pagamento == 'credito' ? 'selected' : ''}} value="credito">Crédito</option>
                                    <option {{$carrinho->forma_pagamento == 'cheque' ? 'selected' : ''}} value="cheque">Cheque</option>
                                </select>
                                <label for="floatingSelectGrid">Tipo</label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" id="closeModalFinalizaVenda" class="btn btn-secondary"
                        data-bs-dismiss="modal" aria-label="Close">Sair</button>

                    <button type="submit" class="btn btn-primary">Finalizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{--buscar cliente finaliza venda --}}
   <div class="modal fade" id="modalbuscaClienteNomeVendaAjax" tabindex="-1" aria-labelledby="exampleModalLabel"
   aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-scrollable">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">Buscar cliente</h5>
               <button onclick="abreModalVenda()" id="closeModalBuscaCliente" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>

           <form onsubmit="return false;" id="formBuscaClienteFinalizaVendaAjax" method="GET" class="search-cliente">
               @csrf
               <input id="nomeClienteFinalizarVenda" type="text" class="form-control" placeholder="nome">
               <button type="submit" class="lupa"><i class="bi-search"
                       style="color: black"></i></button>
           </form>
           {{-- <form onsubmit="return false;" id="selectClienteVenda" method="POST" class="modal-body">
               @csrf --}}
               <div class="modal-body">
               <div style="margin-left: 30%;" class="carregando justify-content-center d-none">
                   <img style="width: 50%; heigth: 50%;" src="{{ asset('carregando.gif') }}" alt="">
                   <h6>Buscando Clientes....</h6>
               </div>
               <a id="lisClientesModal">
                   <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>
                   <ul id="ulClientesModalFinalizaVenda" class="list-group">
                       {{-- @foreach ($clientes as $cliente)
                           <a onclick='set_cliente_finaliza_venda(<?php echo json_encode($cliente); ?>)'>
                               <li style="text-align:justify; overflow-x: auto; overflow-y: hidden;overflow-y: hidden;"
                                   class="list-group-item d-flex justify-content-between align-items-center">
                                   {{ $cliente->nome }}
                                   <button onclick="submitFormularioSalvarVenda(<?php echo $cliente->id; ?>)" class="lupa-list"><i
                                           class="bi bi-save2"></i></button>
                               </li>
                           </a>
                       @endforeach --}}

                   </ul>
               </a>
            </div>
           {{-- </form> --}}
       </div>
   </div>
</div>

{{-- Modal Salvar Itens Sem Clientes --}}
<div class="modal fade" id="salvarItens" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Salvar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form onsubmit="return false;" id="formBuscarClienteSalvarItens" method="GET" class="search-cliente">
                @csrf
                <input id="nomeClienteSalvarItens" type="text" class="form-control" placeholder="nome">
                <button type="submit" class="lupa"><i class="bi-search" style="color: black"></i></button>
            </form>
            <form action="{{route('salvar_venda')}}" id="fomrsalvarVenda" method="POST" class="modal-body">
                @method('PUT')
                @csrf
                <input type="hidden" id="clienteSalvaCarrinho" name="cliente">
                <a id="lisClientesModalSalvarVenda">
                    <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>
                    <div style="margin-left: 30%;" class="carregando justify-content-center d-none">
                        <img style="width: 50%; heigth: 50%;" src="{{ asset('carregando.gif') }}" alt="">
                        <h6>Buscando Clientes....</h6>
                    </div>
                    <ul id="ulClientesModalSalvarVenda" class="list-group">
                       
                        {{-- @foreach ($clientes as $cliente)
                            <a onclick="submitFormularioSalvarVenda(<?php echo $cliente->id; ?>)">
                                <li style="text-align:jnomeClienteSalvarItensustify; overflow-x: auto; overflow-y: hidden;overflow-y: hidden;"
                                    class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $cliente->nome }}
                                    <button onclick="submitFormularioSalvarVenda(<?php echo $cliente->id; ?>)"
                                        class="lupa-list"><i class="bi bi-save2"></i></button>
                                </li>
                            </a>
                        @endforeach --}}

                    </ul>
                </a>
            </form>
        </div>
    </div>
</div>
{{-- Fim Modal Salvar Itens --}}
