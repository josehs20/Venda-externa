        {{-- Modal carrinho item editar --}}
        <div class="modal fade" id="editaItem{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="margin-left: 35%;" id="exampleModalLabel">Editar
                            Item
                        </h5><br>
                        <button type="button" class="btn-close closeModalEditItem" id="closemodalDesconto"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <br>
                    <label class="mx-2" for=""> Produto: <b>
                            {{ $item->nome }}</b></label>
                    <hr>
                    <form method="POST" onsubmit="return false;" id="editItemCarrinho">
                        @method('PUT')
                        @csrf
                        <input type="hidden" id="estoque_alltech_id" value="{{ $item->estoque_id_alltech }}">
                        <input type="hidden" id="precoItemProduto" value="{{ $item->preco }}">
                        <div class="modal-body">
                            <div class="row g-2">
                                <div class="col-8">
                                    <div class="form-floating">
                                        {{-- Verifica tipo de qauntidade --}}

                                        <input name="quantidade" id="quantidadeEditItemCarrinho" value="{{ $item->quantidade }}" type="number" class="form-control" min="0"
                                             id="floatingInputGrid"
                                            >
                                        <label id="quantidadeFloat" for="floatingInputGrid">Quantidade: {{ $item->quantidade }}</label>


                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-floating">
                                        <select name="tipo_qtd" disabled class="form-select" id="floatingSelectGrid"
                                            aria-label="Floating label select example">
                                            <option selected value="UN">UN</option>
                                            {{-- <option value="CX">CX</option> --}}
                                        </select>
                                        <label for="floatingSelectGrid">Tipo</label>
                                    </div>
                                </div>
                            </div>

                            <br>

                            {{-- @if ($carrinho->tp_desconto == 'porcento_unico' or $carrinho->tp_desconto == 'dinheiro_unico') --}}
                                <span  class="descontoUnicoInserido d-none">Desconto único já inserido, para inserir desconto individual é
                                    necessário
                                    zerar o desconto.</span>
                            {{-- @else --}}
                                {{-- --porcentagem --}}
                                <div class="row g-2 descontoListCarrinho d-none">
                                    <div class="col-8">
                                        <div class="form-floating">
                                            <input required {{ $item->qtd_desconto ? '' : 'disabled' }} name="qtd_desconto"
                                                type="number" class="form-control" min="0.01" step="0.01"
                                                value="{{ $item->qtd_desconto }}"
                                                id="inputDescontoEditarItem<?php echo $item->id; ?>"
                                                placeholder="Desconto Geral">
                                            <label for="floatingInputGrid">Desconto</label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <span></span>
                                        <div class="form-floating">
                                            <select
                                                onchange="habilitaDescontoEditarItem(this.value, <?php echo $item->id; ?>)"
                                                name="tp_desconto" class="form-select" id="selectTp_desconto"
                                                aria-label="Floating label select example">
                                                {{-- @if ($item->qtd_desconto) --}}
                                                    <option value="">selecione...</option>
                                                    <option value="%"
                                                        {{ $item->tp_desconto == '%' ? 'selected' : '' }}>
                                                        %</option>
                                                    <option value="$"
                                                        {{ $item->tp_desconto == '$' ? 'selected' : '' }}>
                                                        R$</option>
                                                {{-- @else --}}
                                                    {{-- <option value="">selecione...</option>
                                                    <option value="porcento">%</option>
                                                    <option value="dinheiro">R$</option> --}}
                                                {{-- @endif --}}

                                            </select>
                                            <label for="floatingSelectGrid">Tipo</label>
                                        </div>
                                    </div>
                                </div>
                            {{-- @endif --}}
                        </div>
                        <div class="modal-footer d-flex justify-content-end">
                            <button type="button" onclick="confirmDeleteItem('<?php echo $item->id; ?>')"
                                class="btn btn-outline-danger">Excluir item</button>

                            <button type="buttom" onclick="up_item_carrinho('<?php echo $item->estoque_id_alltech ?>', '<?php echo $item->preco ?>', '<?php echo $item->id ?>')" class="btn btn-outline-primary">Confirmar</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- fim modal editar --}}











        {{-- Modal Excluir item do Carrinnho --}}
        {{-- <div class="modal fade" id="deleteItemCarrinho{{ $item->id }}" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Confirmação</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span>Deseja realmente excluir esse Item,
                            <b>{{ $item->nome }}</b>?</span>
                    </div>
                    <form action="{{ route('destroy_item', ['item' => $item->id]) }}" method="POST"
                        class="modal-footer">
                        @method('DELETE')
                        @csrf
                        <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn btn-secondary">
                            Sair
                        </button>
                        <button type="submit" name="deleteCarrinho" value="1"
                            class="btn btn-primary">Sim</button>
                    </form>
                </div>
            </div>
        </div> --}}
        {{-- Fim Modal Excluir item do Carrinnho --}}
