     {{-- Modal finaliza venda Sem Cliente --}}
     <div class="modal fade" id="modalFinalizaVendaSemCliente" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
         <div class="modal-dialog modal-lg"">
             <div class="  modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="exampleModalLabel">Finalizar Venda
                     </h5><br>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                 id="valorDescontoModal">{{ reais($carrinho->valor_desconto + $carrinho->valor_desconto_sb_venda) }}</b>
                         </h6>
                     </div>
                 </div>

                 <form id="formFinalizaVendaSubmit" method="POST" name="formFinalizaVenda"
                     action="{{ route('finaliza_venda', ['carrinho' => $carrinho->id]) }}">
                     @method('PUT')
                     @csrf
                     <input type="hidden" name="hiddenInputValorTotalModal" id="hiddenValorTotalModal"
                         value="<?php echo $carrinho->total; ?>">
                     <input type="hidden" name="hiddenInputValorDescontoModal" id="hiddenValorDescontoModal"
                         value="<?php echo $carrinho->valor_desconto; ?>">
                     <input type="hidden" name="hiddenInputValorDescontoSobreVendaModal"
                         id="hiddenValorDescontoSobreVendaModal" value="">

                     <div class="modal-body">
                         <div class="row g-2">
                             <div class="col-4">
                                 <div class="form-floating">
                                     <input required name="cliente_alltech_id" id="clienteCodigo" type="number"
                                         step="1" class="form-control" {{-- value="{{ $carrinho->cliente_id ? $carrinho->cliente->alltech_id : '999999' }}" --}} name="codigoCliente"
                                         id="floatingInputGrid">
                                     <label for="floatingInputGrid">Cod.</label>
                                 </div>
                             </div>
                             <div class="col-8">
                                 <div class="form-floating">
                                     {{-- id="clienteNomeVenda" --}}

                                     <input onclick="selectClienteFinalizaVenda()" id="clienteNomeVenda"
                                         list="listaClientes" required type="text" class="form-control" required
                                         name="nomeCLienteFinaliza" {{-- value="VENDA A VISTA"> --}}>
                                     <label for="floatingInputGrid">Cliente</label>
                                     <datalist id="listaClientes">


                                         @foreach ($clientes_user as $cliente)
                                             <option value="{{ $cliente->nome }}">{{ $cliente->alltech_id }}
                                             </option>
                                         @endforeach

                                     </datalist>
                                 </div>

                                 <span id="nomeValid"></span>
                             </div>

                             <a onclick="fechaModalfinalizaVenda()" class="btn btn-primary" data-bs-toggle="modal"
                                 data-bs-target="#modalbuscaClienteNomeVendaAjax"><i class="bi-search"
                                     style="color: rgb(255, 255, 255)"></i></a>
                         </div>
                         <br>

                         <span style="font-size: 14px;" class="mx-1"><u>Deseja aplicar desconto
                                 sobre a
                                 venda?</u></span>
                         <br><br>

                         {{-- Desconto --}}
                         <div class="row g-2">
                             <div class="col-8">
                                 <div class="form-floating">
                                     <input disabled required
                                         onkeyup="calculoDescontoSobreVenda(<?php echo $carrinho->total; ?>, <?php echo $carrinho->valor_desconto; ?>)"
                                         name="qtd_desconto_sobre_venda" type="number" class="form-control"
                                         min="0" id="inputDesconto">
                                     <label for="floatingInputGrid">Desconto</label>
                                 </div>
                             </div>
                             <div class="col-4">
                                 <div class="form-floating">
                                     <select name="tp_desconto_sb_venda" class="form-select"
                                         id="tp_desconto_sobre_venda_modal"
                                         onchange="verificaDesconto(this.value, <?php echo $carrinho->total; ?>, <?php echo $carrinho->valor_desconto; ?>)"
                                         aria-label="Floating label select example">
                                         <option selected value="0">selecione...</option>
                                         <option value="porcento">%</option>
                                         <option value="dinheiro">R$</option>
                                     </select>
                                     <label for="floatingSelectGrid">Tipo</label>
                                 </div>
                             </div>
                         </div>
                         <br>
                         {{-- Parcelas --}}
                         <div class="row g-2">
                             <div class="col-6">
                                 <div class="form-floating">
                                     <input disabled required name="parcelas" pattern="[0-9]+([,\.][0-9]+)?"
                                         min="0" step="any" type="text" class="form-control"
                                         placeholder="1" value="1" id="inputParcelas">
                                     <label for="floatingInputGrid">Parcelas</label>
                                 </div>
                             </div>
                             <div class="col-6">
                                 <div class="form-floating">
                                     <select name="tipo_pagamento" class="form-select" id="floatingSelectGrid"
                                         aria-label="Floating label select example"
                                         onchange="verificaAvistaAprazo(this.value)">
                                         <option value="AV">À VISTA</option>
                                         <option value="AP">A PRAZO</option>
                                     </select>
                                     <label for="floatingSelectGrid">Tipo</label>
                                 </div>
                             </div>
                         </div>
                         <br>

                         {{-- Tipo pagamento --}}
                         <div class="row g-2">
                             <div id="campoInserirEntrada" class="col-12">
                                 <div class="form-floating">
                                     <input id="valor_entrada" name="valor_entrada" type="number"
                                         class="form-control" min="0">

                                     <label for="floatingInputGrid">Aplicar algum valor de entrada ?</label>
                                 </div>
                             </div>
                             <br>
                             <div class="col-12">
                                 <div class="form-floating">
                                     <select name="forma_pagamento" class="form-select">

                                         <option selected value="dinheiro">Dinheiro</option>
                                         <option value="cartao">Cartão</option>
                                         <option value="digital">Digital</option>
                                         <option value="promissoria">Promissória</option>
                                         <option value="credito">Crédito</option>
                                         <option value="cheque">Cheque</option>
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
     {{-- fim Modal finaliza venda Sem Cliente --}}

     {{-- Modal modalbuscaClienteNomeVendaAjax --}}
     <div class="modal fade" id="modalbuscaClienteNomeVendaAjax" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
         <div class="modal-dialog modal-lg modal-dialog-scrollable">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>
                     <button onclick="buscaClienteNomeVendaAjax()" type="button" class="btn-close"
                         data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>

                 {{-- usa o mesmo id para salvar clientes porque é a mesma busca que faz no controller via ajax --}}
                 <form id="formSalvaItensCliente" method="GET" class="search-cliente">
                     @csrf
                     <input id="buscaNomeClienteVendaAjax" type="text" class="form-control" placeholder="nome">
                     <button id="botaoBuscaClienteNomeAjax" class="lupa"><i class="bi-search"
                             style="color: black"></i></button>
                 </form>
                 <form id="buscaAlltech_idClienteVendaAjax" method="GET" class="modal-body">
                     @csrf
                     <div class="carregando">
                         <img style="width: 50%; heigth: 50%;" src="{{ asset('carregando.gif') }}" alt="">
                         <h6>Buscando Clientes....</h6>
                     </div>
                     <a id="MontaBuscaClienteFinaliza">

                         <ul class="list-group">
                             @foreach ($clientes_user as $cliente)
                                 <a name="cliente_id" type="button" onclick="buttonAlltech_id(<?php echo $cliente->alltech_id; ?>)">
                                     <li style="text-align:justify; overflow-x: auto; overflow-y: hidden; overflow-y: hidden; width: 93%;"
                                         class="list-group-item d-flex justify-content-between align-items-center mx-2">
                                         {{ $cliente->nome }}
                                         <button type="button" name="cliente_id"
                                             onclick="buttonAlltech_id(<?php echo $cliente->alltech_id; ?>)" class="lupa-list"><i
                                                 class="bi bi-save2"></i></button>
                                     </li>
                                 </a>
                             @endforeach

                         </ul>
                     </a>
                 </form>
                 <div class="modal-footer">
                     <button onclick="buscaClienteNomeVendaAjax()" id="closeModalbuscaClienteNomeVendaAjax"
                         type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sair</button>
                 </div>

             </div>
         </div>
     </div>

     {{-- Fim Modal modalbuscaClienteNomeVendaAjax --}}


     {{-- Modal Salvar Itens Sem Clientes --}}
     <div class="modal fade" id="salvarItens" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
         <div class="modal-dialog modal-lg modal-dialog-scrollable">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title" id="exampleModalLabel">Salvar</h5>
                     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                 </div>

                 <form id="formSalvaItensCliente" method="GET" class="search-cliente">
                     @csrf
                     <input id="nomeClienteSalvarItens" type="text" class="form-control" placeholder="nome">
                     <button id="botaoBuscaClienteAjax" class="lupa"><i class="bi-search"
                             style="color: black"></i></button>
                 </form>
                 <form action="{{ route('salvar_venda') }}" method="POST" class="modal-body">
                     @method('PUT')
                     @csrf
                     <div class="carregando">
                         <img style="width: 50%; heigth: 50%;" src="{{ asset('carregando.gif') }}" alt="">
                         <h6>Buscando Clientes....</h6>
                     </div>
                     <a id="lisClientesModal">
                         <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>
                         <ul class="list-group">
                             @foreach ($clientes_user as $cliente)
                             <a onclick="submitFormularioSalvarVenda(<?php echo $cliente->id ?>)">
                                 <li style="text-align:justify; overflow-x: auto; overflow-y: hidden;overflow-y: hidden;"
                                     class="list-group-item d-flex justify-content-between align-items-center">
                                     {{ $cliente->nome }}
                                     <button id="submitFormularioSalvarVenda<?php echo $cliente->id ?>" type="submit" name="cliente_id" value="{{ $cliente->id }}"
                                         class="lupa-list"><i class="bi bi-save2"></i></button>
                                 </li>
                                </a>
                             @endforeach

                         </ul>
                     </a>
                 </form>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sair</button>
                 </div>

             </div>
         </div>
     </div>
     {{-- Fim Modal Salvar Itens --}}
