{{-- Modal Confirma Salvar itens para cliente já salvo cliente itens --}}
<div class="modal fade" id="salvarItensRetornados" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('salvar_venda') }}" method="POST" class="modal-content">
            @method('PUT')
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Salvar Itens</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Carrinho já esta salvo para <b>{{ $carrinho->cliente->nome }}</b>, deseja
                continuar?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Não</button>
                <input type="hidden" name="cliente_id" value="{{ $carrinho->cliente_id }}" />
                <button type="submit" class="btn btn-primary">Sim</button>
            </div>
        </form>
    </div>
</div>


{{-- Modal finaliza venda Com Cliente --}}
<div class="modal fade" id="modalFinalizaVendaComCLientSalvo" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
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
                        <b id="valorDescontoModal">{{ reais($carrinho->valor_desconto) }}</b>
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

                <span class="mx-3"><b>Cliente Já Mencionado</b></span>
                <div class="modal-body">

                    <div class="row g-2">
                        <div class="col-8">
                            <div class="form-floating">
                                <input id="clienteNomeVenda" Readonly type="text" class="form-control" required
                                    value="{{ $carrinho->cliente->nome }}" id="floatingInputGrid">

                                <label for="floatingInputGrid">Cliente</label>

                            </div>
                            <span id="nomeValid"></span>
                        </div>
                        <div class="col-4">
                            <div class="form-floating">
                                <input required name="cliente_alltech_id" Readonly id="clienteCodigo" type="number"
                                    step="1" class="form-control"
                                    value="{{ $carrinho->cliente_id ? $carrinho->cliente->alltech_id : null }}"
                                    name="codigoCliente" id="floatingInputGrid">

                                <label for="floatingInputGrid">Cod.</label>
                            </div>
                        </div>
                    </div>

                    <br>
                    <span style="font-size: 14px;" class="mx-1"><u>Deseja aplicar desconto
                            sobre a
                            venda?</u></span>
                    <br><br>
                    <div class="row g-2">
                        <div class="col-8">
                            <div class="form-floating">
                                <input disabled
                                    onkeyup="calculoDescontoSobreVenda(<?php echo $carrinho->total; ?>, <?php echo $carrinho->valor_desconto; ?>)"
                                    name="qtd_desconto_sobre_venda" type="number" required class="form-control"
                                    min="0.01" step="0.01" value="{{ $carrinho->qtd_unificado }}" id="inputDesconto">
                                <label for="floatingInputGrid">Desconto</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <span></span>
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
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="form-floating">
                                <input name="parcelas" disabled min="1" type="text" class="form-control"
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
                                <input name="valor_entrada" type="number" class="form-control" min="0">

                                <label for="floatingInputGrid">Aplicar algum valor de entrada ?</label>
                            </div>
                        </div>
                        <br>
                        <div class="col-12">
                            <div class="form-floating">
                                <select name="forma_pagamento" class="form-select">

                                    <option value="dinheiro">Dinheiro</option>
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
                    <button type="button" id="closeModalFinalizaVenda" class="btn btn-secondary" data-bs-dismiss="modal"
                        aria-label="Close">Sair</button>

                    <button type="submit" class="btn btn-primary">Finalizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- fim modal finalizar venda com cliente --}}
