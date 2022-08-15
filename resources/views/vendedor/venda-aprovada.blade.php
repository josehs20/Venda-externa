@extends('layouts.app')

@section('content')

    <style>
        .carregando {
            display: none;
        }
    </style>
    @include('componentes.navbar', ['titulo' => 'Itens'])
    <br><br><br><br>

    @if (Session::has('success'))

        <body onload="msgSuccess('<?php echo Session::get('success'); ?>')">
    @endif


    @if (!isset($carrinho))

        <div class="alert alert-warning mt-5" role="alert">
            Adicione Produtos No Seu Carrinho De Vendas :)
        </div>
    @else
        {{-- Modais dos botoes de desconto, cancelamento --}}
        @include('vendedor.inc.modais-botoes')

        @include('vendedor.inc.cards-iniciais')
        <div class="row d-flex justify-content-center">

        </div>
        <div class="row d-flex justify-content-center" style="margin-top: 10px;">


            <button type="button" class="btn btn-success col-5 mx-2" data-bs-toggle="modal"
                data-bs-target="#modalFinalizaVendaComCLientSalvo">
                Finalizar
            </button>

            <button href="" type="button" class="btn btn-danger col-5 mx-2" data-bs-toggle="modal"
                data-bs-target="#cancelaVenda">
                Cancelar Venda
            </button>

            {{-- Modal finaliza venda Com Cliente --}}
            <div class="modal fade" id="modalFinalizaVendaComCLientSalvo" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Finalizar Venda
                            </h5><br>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
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

                        <form 
                            action="{{ route('finaliza_venda_aprovada', ['carrinho' => $carrinho->id]) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="hiddenInputValorTotalModal" id="hiddenValorTotalModal"
                                value="<?php echo $carrinho->total; ?>">
                            <input type="hidden" name="hiddenInputValorDescontoModal" id="hiddenValorDescontoModal"
                                value="<?php echo $carrinho->valor_desconto; ?>">
                            <input type="hidden" name="hiddenInputValorDescontoSobreVendaModal"
                                id="hiddenValorDescontoSobreVendaModal" value="<?php echo $carrinho->valor_desconto_sb_venda; ?>">

                            <span class="mx-3"><b>Cliente Mencionado</b></span>
                            <div class="modal-body">

                                <div class="row g-2">
                                    <div class="col-8">
                                        <div class="form-floating">
                                            <input id="clienteNomeVenda" Readonly type="text" class="form-control"
                                                required value="{{ $carrinho->cliente->nome }}" id="floatingInputGrid">

                                            <label for="floatingInputGrid">Cliente</label>

                                        </div>
                                        <span id="nomeValid"></span>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-floating">
                                            <input required name="cliente_alltech_id" Readonly id="clienteCodigo"
                                                type="number" step="1" class="form-control"
                                                value="{{ $carrinho->cliente_id ? $carrinho->cliente->alltech_id : null }}"
                                                name="codigoCliente" id="floatingInputGrid">

                                            <label for="floatingInputGrid">Cod.</label>
                                        </div>
                                    </div>
                                </div>


                                {{-- <span style="font-size: 14px;" class="mx-1"><u>Desconto aplicado
                                        sobre a
                                        venda?</u></span> --}}
                                <br>
                                <div class="row g-2">
                                    <div class="col-8">
                                        <div class="form-floating">
                                            <input Readonly
                                                onkeyup="calculoDescontoSobreVenda(<?php echo $carrinho->valor_bruto; ?>, <?php echo $carrinho->valor_desconto; ?>)"
                                                name="qtd_desconto_sobre_venda" type="number" required
                                                class="form-control" min="0.01" step="0.01"
                                                value="{{ $carrinho->desconto_qtd_sb_venda ? $carrinho->desconto_qtd_sb_venda : null }}"
                                                id="inputDesconto">
                                            <label for="floatingInputGrid">Desconto</label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <span></span>
                                        <div class="form-floating">
                                            <input class="form-control"
                                                value="{{ $carrinho->tp_desconto_sb_venda == 'porcento' ? '%' : ($carrinho->tp_desconto_sb_venda == 'dinheiro' ? '$' : '') }}"
                                                Readonly id="inputDesconto">
                                            {{-- <select name="tp_desconto_sb_venda" class="form-select"
                                                id="tp_desconto_sobre_venda_modal" data-value-selected="<?php echo $carrinho->tp_desconto_sb_venda ? $carrinho->tp_desconto_sb_venda : '0'; ?>"
                                                data-carrinho-total="<?php echo $carrinho->total; ?>"
                                                data-carrinho-valor-desconto="<?php echo $carrinho->valor_desconto; ?>"
                                                onchange="verificaDesconto(this.value, <?php echo $carrinho->valor_bruto; ?>, <?php echo $carrinho->valor_desconto; ?>)"
                                                aria-label="Floating label select example">
                                                <option selected value="0">selecione...</option>
                                                <option value="porcento">%</option>
                                                <option value="dinheiro">R$</option>
                                            </select> --}}
                                            <label for="floatingSelectGrid">Tipo</label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input name="parcelas" readonly min="1" type="text" class="form-control"
                                                placeholder="1" value="{{$carrinho->parcelas}}" id="inputParcelas">
                                            <label for="floatingInputGrid">Parcelas</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input name="tipo_pagamento" readonly min="1" type="text" class="form-control"
                                                placeholder="1"
                                                value="{{ $carrinho->tipo_pagamento ? $carrinho->tipo_pagamento : '' }}"
                                                id="inputParcelas">

                                            {{-- <select name="tipo_pagamento" class="form-select" id="floatingSelectGrid"
                                                aria-label="Floating label select example"
                                                onchange="verificaAvistaAprazo(this.value)">
                                                <option value="AV">À VISTA</option>
                                                <option value="AP">A PRAZO</option>
                                            </select> --}}
                                            <label for="floatingSelectGrid">Tipo</label>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                {{-- Tipo pagamento --}}
                                <div class="row g-2">
                                    @if ($carrinho->valor_entrada)
                                        <div id="campoInserirEntrada" class="col-12">
                                            <div class="form-floating">
                                                <input readonly id="valor_entrada" name="valor_entrada" type="text"
                                                    class="form-control" min="0" value="{{reais($carrinho->valor_entrada)}}">

                                                <label for="floatingInputGrid">Aplicar algum valor de entrada ?</label>
                                            </div>
                                        </div>
                                    @endif
                                    <br>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input readonly id="forma_pagamento" name="forma_pagamento" type="text"
                                                    class="form-control" value="{{ $carrinho->forma_pagamento == 'cartao' ? 'Cartão' : ($carrinho->forma_pagamento == 'digital' ? 'Digital' : ($carrinho->forma_pagamento == 'promissoria' ? 'Promissória' : ($carrinho->forma_pagamento == 'cheque' ? 'Cheque': ($carrinho->forma_pagamento == 'credito' ? 'Crédito': 'Dinheiro'))))}}">
                                            {{-- <select name="forma_pagamento" class="form-select">
                                                <option value="dinheiro">Dinheiro</option>
                                                <option value="cartao">Cartão</option>
                                                <option value="digital">Digital</option>
                                                <option value="promissoria">Promissória</option>
                                                <option value="credito">Crédito</option>
                                                <option value="cheque">Cheque</option>
                                            </select> --}}
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
            {{-- fim modal finalizar venda com cliente --}}
        </div>
        <hr><br>
        {{-- tabela de itens principal --}}
        @if ($carrinho)
            <form id="formDescInvalido" action="{{ route('descInvalido', $carrinho->id) }}" method="POST">
                @method('PUT')
                @csrf
            </form>
            <div class="container">
                @foreach ($carrinho->carItem as $item)
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center"
                            style="border: none; background-color: rgb(193, 194, 201)">
                            <a
                                style="color: black; font-weight: 500; text-decoration:none; font-size:16px">{{ $item->produto->nome }}&ensp;{{ $item->iGrade ? '/' . $item->iGrade->tam : '' }}</a>
                            {{-- <a class="badge  rounded-pill" data-bs-toggle="modal"
                                data-bs-target="#deleteItemCarrinho{{ $item->id }}"><i class="bi bi-x-square"
                                    style="font-size: 22px; cursor: pointer; color:black;"></i></a> --}}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center"
                            style="background-color: rgb(230, 227, 255)">
                            <a href="" style="color: black; font-weight: 400; text-decoration:none;">Total: R$
                                {{ reais($item->valor) }}</a>
                            {{-- <a class="badge  rounded-pill" data-bs-toggle="modal"
                                data-bs-target="#editaItem{{ $item->id }}"><i class="bi bi-pencil-square"
                                    style="font-size: 22px; cursor: pointer; color:black;"></i></a> --}}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Preço:
                            <span class="badge bg-primary rounded-pill">R$ {{ reais($item->preco) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Quantidade:
                            <span class="badge bg-primary rounded-pill">{{ $item->quantidade }}</span>
                            {{-- <div class="input-group list-qtd" style="width: 50%;">
                            <button class="btn btn-link" type="button"><i class="bi bi-dash-circle"></i></button>
                            <input type="text" class="form-control" placeholder="" value="{{ $item->quantidade }}">
                            <button class="btn btn-link" type="button"><i class="bi bi-plus-circle"></i></button>
                        </div> --}}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Desconto:
                            @if ($carrinho->tp_desconto == 'porcento_unico' or !$carrinho->tp_desconto)
                                <span>{{ !$carrinho->tp_desconto ? 'Não Aplicado' : 'Porcentagem Única' }}</span>
                            @else
                                <span
                                    class="badge bg-primary rounded-pill">{{ $carrinho->tp_desconto == 'porcento_unico' ? 'Porcentagem única' : ($carrinho->tp_desconto == 'dinheiro_unico' ? 'Unificado em dinheiro' : (!$item->qtd_desconto ? 'Não inserido' : ($item->tp_desconto == '%' ? '%' . $item->qtd_desconto : "R$" . $item->qtd_desconto))) }}</span>
                            @endif
                        </li>
                    </ul>
                    <br>
                @endforeach
            </div>
        @endif
    @endif
@endsection

<script type="text/javascript" src="{{ asset('js/viewItensCarrinho.js') }}" defer></script>
