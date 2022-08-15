  <!-- Modal desconto -->
  <div class="modal fade" id="modalDescontoCarrinho" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Desconto</h5><br>
                <button id="closemodalDesconto" type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <form method="post" id="formUnificaDesconto">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-8">
                            <div class="form-floating">
                                <input required name="qtd_desconto" type="number" class="form-control" min="0.01"
                                    step="0.01" id="qtd_desconto_unifica_desconto" placeholder="Desconto Geral" value="{{$carrinho->qtd_desconto ? $carrinho->qtd_desconto : ''}}">
                                <label for="floatingInputGrid">Desconto</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-floating">
                                <select name="tp_desconto" class="form-select" id="select_unifica_desconto"
                                    aria-label="Floating label select example">
                                    <option value="porcento_unico">%</option>
                                    <option value="dinheiro_unico">R$</option>
                                </select>
                                <label for="floatingSelectGrid">Tipo</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="confirmacaoZerarDesconto()" type="button" class="btn btn-outline-danger mx-2">
                        Zerar Desconto
                    </button>
                    <button type="submit" class="btn btn-outline-primary">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Fim Modal Unificado --}}

<!-- Modal confirma concelamento de venda -->
<div class="modal fade" id="cancelaVenda" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Cancelar venda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Deseja realmente cancelar todos os itens do carrinho?
            </div>
            <form action="{{ route('venda.destroy', ['venda' => $carrinho->id]) }}" method="POST">
                @method('DELETE')
                @csrf
                <input type="hidden" name="verify" value="1">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Não</button>
                <button type="submit" class="btn btn-primary">Sim</button>
            </form>
        </div>
    </div>
</div>
<!-- Fim Modal confirma concelamento de venda -->