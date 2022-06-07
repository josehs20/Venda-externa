        {{-- Modal carrinho item editar --}}
        <div class="modal fade" id="editaItem{{ $item->id }}" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" style="margin-left: 35%;" id="exampleModalLabel">Editar
                            Item
                        </h5><br>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <br>
                    <label class="mx-2" for=""> Produto: <b>
                            {{ $item->produto->nome }}</b></label>
                    <hr>

                    <form method="POST" action="{{ route('venda.update', ['venda' => $item->id]) }}">
                        @method('PUT')
                        @csrf
                        <div class="modal-body">
                            <div class="row g-2">
                                <div class="col-8">
                                    <div class="form-floating">
                                        {{-- Verifica tipo de qauntidade --}}
                                        @if ('u' == 'un')
                                            <input name="quantidade" type="number" class="form-control"
                                                min="0" value="{{ $item->quantidade }}"
                                                id="floatingInputGrid" placeholder="quantidade">
                                            <label for="floatingInputGrid">Quantidadeasd</label>
                                        @else
                                            <input name="quantidade" type="number" class="form-control"
                                                min="0" value="{{ $item->quantidade }}"
                                                id="floatingInputGrid" placeholder="quantidade">
                                            <label for="floatingInputGrid">Quantidade</label>
                                        @endif

                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-floating">
                                        <select name="tipo_qtd" disabled class="form-select"
                                            id="floatingSelectGrid"
                                            aria-label="Floating label select example">
                                            <option selected value="UN">UN</option>
                                            {{-- <option value="CX">CX</option> --}}
                                        </select>
                                        <label for="floatingSelectGrid">Tipo</label>
                                    </div>
                                </div>
                            </div>

                            <br>

                            @if ($carrinho->tp_desconto == 'porcento_unico' or $carrinho->tp_desconto == 'dinheiro_unico')
                                <span>Desconto único já inserido, para inserir desconto individual é
                                    necessário
                                    zerar o desconto.</span>
                            @else
                                {{-- --porcentagem --}}
                                <div class="row g-2">
                                    <div class="col-8">
                                        <div class="form-floating">
                                            <input {{ $item->qtd_desconto ? '' : 'disabled' }}
                                                name="qtd_desconto" type="number" class="form-control"
                                                min="0.01" step="0.01" value="{{ $item->qtd_desconto }}"
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
                                                name="tipo_desconto" class="form-select"
                                                id="floatingSelectGrid"
                                                aria-label="Floating label select example">
                                                @if ($item->qtd_desconto)
                                                    <option value="">selecione...</option>
                                                    <option value="porcento"
                                                        {{ $item->tipo_desconto == 'porcento' ? 'selected' : '' }}>
                                                        %</option>
                                                    <option value="dinheiro"
                                                        {{ $item->tipo_desconto == 'dinheiro' ? 'selected' : '' }}>
                                                        R$</option>
                                                @else
                                                    <option value="">selecione...</option>
                                                    <option value="porcento">%</option>
                                                    <option value="dinheiro">R$</option>
                                                @endif

                                            </select>
                                            <label for="floatingSelectGrid">Tipo</label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                aria-label="Close">Sair</button>

                            <button type="submit" class="btn btn-primary">Confirmar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- fim modal editar --}}

        {{-- Modal Excluir item do Carrinnho --}}
        <div class="modal fade" id="deleteItemCarrinho{{ $item->id }}" data-bs-backdrop="static"
            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Confirmação</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span>Deseja realmente excluir esse Item,
                            <b>{{ $item->produto->nome }}</b>?</span>
                    </div>
                    <form action="{{ route('destroy_item', ['item' => $item->id]) }}" method="POST"
                        class="modal-footer">
                        @method('DELETE')
                        @csrf
                        <button type="button" id="#" data-bs-dismiss="modal" aria-label="Close"
                            class="btn btn-secondary">
                            Sair
                        </button>
                        <button type="submit" name="deleteCarrinho" value="1"
                            class="btn btn-primary">Sim</button>
                    </form>
                </div>
            </div>
        </div>
        {{-- Fim Modal Excluir item do Carrinnho --}}