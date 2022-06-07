  <!-- Modal Unifica -->
  <div class="modal fade" id="modalUnifica" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Desconto</h5><br>
                <button id="closemodalDesconto" type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <form method="post" action="{{ route('unifica_valor_Itens', ['carrinho' => $carrinho->id]) }}">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="row g-2">
                        <div class="col-8">
                            <div class="form-floating">
                                <input required name="qtd_unificado" type="number" class="form-control" min="0.01"
                                    step="0.01" id="floatingInputGrid" placeholder="Desconto Geral" value="{{$carrinho->desconto_qtd ? $carrinho->desconto_qtd : ''}}">
                                <label for="floatingInputGrid">Desconto</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-floating">
                                <select name="tipo_unificado" class="form-select" id="floatingSelectGrid"
                                    aria-label="Floating label select example">
                                    <option value="porcento">%</option>
                                    <option value="dinheiro">R$</option>
                                </select>
                                <label for="floatingSelectGrid">Tipo</label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <a onclick="abremodalConfDesconto()" data-bs-toggle="modal" data-bs-target="#confzerarDesconto"
                        style="width:140px;" type="button" class="btn btn-danger col-5 mx-2">
                        Zerar Desconto
                    </a>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Fim Modal Unificado --}}

<!-- Modal confirma Zerar Desconto -->
<div class="modal fade" id="confzerarDesconto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Confirmação</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Deseja Realmente Zerar o Desconto Deste Carrinho ?
            </div>

            <form action="{{ route('zera_desconto', ['carrinho' => $carrinho->id]) }}" method="POST"
                class="modal-footer">
                @method('PUT')
                @csrf
                <input type="hidden" name="verify" value="1">
                <button onclick="abremodalDesconto()" type="button" class="btn btn-danger"
                    data-bs-dismiss="modal">Não</button>
                <button type="submit" class="btn btn-primary">Sim</button>
            </form>
        </div>
    </div>
</div>
<!-- Fim Modal confirma Zerar descontoa -->


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
            <form action="{{ route('venda.destroy', ['venda' => $carrinho->id]) }}" method="POST"
                class="modal-footer">
                @method('DELETE')
                @csrf
                <input type="hidden" name="verify" value="1">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Não</button>
                <button type="submit" class="btn btn-primary">Sim</button>
            </form>
        </div>
    </div>
</div>
<!-- Fim Modal confirma concelamento de venda -->