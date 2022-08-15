@extends('layouts.app')

@section('content')
    <style>
        .carregando {
            display: none;
        }

        #campoInserirEntrada {
            display: none;
        }

        /* .l-bg-cyanButton {
                                background: linear-gradient(to right, #dacf3b, #a9b404) !important;
                                color: #fff;
                            }

                            .l-bg-redButton {
                                background: linear-gradient(to right, #ff2323, #b64949) !important;
                                color: #fff;
                            }

                            .l-bg-orangeButton {
                                background: linear-gradient(to right, #6c6ee9, #4189f5) !important;
                                color: #fff;
                            }

                            .l-bg-greenButton {
                                background: linear-gradient(to right, #99e087 0%, #08fc82 100%) !important;
                                color: #fff;
                            } */
    </style>
    @include('componentes.navbar', ['titulo' => 'Itens'])
    <br><br><br><br>

    @if (Session::has('success'))

        <body onload="alertPadrao('<?php echo Session::get('success'); ?>', 'success')">
    @endif
    @if (Session::has('error'))

        <body onload="msgError('<?php echo Session::get('error'); ?>')">
    @endif
    {{-- @if (Session::has('descInvalido'))

        <body onload="descInvalido('<?php echo Session::get('descIvalido'); ?>')">
    @endif --}}

    @if (!count($carrinho->car_itens))
        <div class="row justify-content-center">
            <div class="alert alert-warning mt-5 col-md-8" role="alert">
                Adicione Produtos No Seu Carrinho De Vendas :)
            </div>
        </div>
    @else
        {{-- Modais dos botoes de desconto, cancelamento --}}
        @include('vendedor.inc.modais-botoes')

        @include('vendedor.inc.cards-iniciais')
        <div class="row d-flex justify-content-center m-4">
            <div class="row col-md-10 justify-content-center">

                <button type="button"
                    class="btn btn-outline-danger col-md-2 mx-4 mt-3 animate__animated animate__fadeIn animate__delay-0.5s"
                    onclick="confirmacaoCancelarVenda('<?php echo $carrinho->id; ?>')">
                    Cancelar Venda
                </button>

                @if ($carrinho->cliente_id_alltech)
                    <button type="button" class="btn btn-outline-info col-md-2 mx-4 mt-3"
                        onclick="salvarItensRetornados('<?php echo $cliente_carrinho[0]->nome; ?>', '<?php echo $cliente_carrinho[0]->id; ?>')">
                        Salvar Itens
                    </button>
                    <button type="button"
                        class="btn btn-outline-secondary col-md-2 mx-4 mt-3 animate__animated animate__fadeIn animate__delay-0.5s"
                        data-bs-toggle="modal" data-bs-target="#modalDescontoCarrinho">
                        Desconto
                    </button>
                    <button type="button" class="btn btn-outline-success col-md-2 mx-4 mt-3" data-bs-toggle="modal"
                        data-bs-target="#modalFinalizaVendaComCLientSalvo">
                        Finalizar
                    </button>


                    {{-- finaliza venda com cliente  E salvar itens novamente para cliente --}}
                    {{-- @include('vendedor.inc.com-cliente') --}}
                @else
                    <button type="button"
                        class="btn btn-outline-info col-md-2 mx-4 mt-3 animate__animated animate__fadeIn animate__delay-0.5s"
                        data-bs-toggle="modal" data-bs-target="#salvarItens">
                        Salvar Itens
                    </button>
                    <button type="button"
                        class="btn btn-outline-secondary col-md-2 mx-4 mt-3 animate__animated animate__fadeIn animate__delay-0.5s"
                        data-bs-toggle="modal" data-bs-target="#modalDescontoCarrinho">
                        Desconto
                    </button>
                    <button id="abrirModalFinalizaVendaSemCliente" type="button"
                        class="btn btn-outline- col-md-2 mx-4 mt-3 btn-outline-success animate__animated animate__fadeIn animate__delay-0.5s"
                        data-bs-toggle="modal" data-bs-target="#modalFinalizaVendaSemCliente">
                        Finalizar
                    </button>

                    {{-- Finaliza venda sem cliente E Salvar itens --}}
                    @include('vendedor.inc.sem-cliente')
                @endif
            </div>
        </div>

        <hr><br>
        {{-- tabela de itens principal --}}

        <form id="formDescInvalido" action="{{ route('descInvalido', $carrinho->id) }}" method="POST">
            @method('PUT')
            @csrf
        </form>
        <div class="row d-flex justify-content-center p-3">

            @foreach ($carrinho->car_itens as $item)
                <div id="div{{ $item->id }}"
                    class="col-md-8 mt-3 animate__animated animate__fadeInDown animate__delay-0.5s"
                    style="cursor: pointer;">
                    <ul class="list-group">
                        <li class="list-group-item bg-info d-flex justify-content-between align-items-center">
                            {{ $item->nome }}{{ $item->tam ? ' /' . $item->tam : '' }}
                            <span class="badge rounded-pill" data-bs-toggle="modal"
                                data-bs-target="#editaItem{{ $item->id }}">
                                <i style="font-size: 22px; color:white" class="bi bi-pencil-square"
                                    data-bs-target="#editaItem{{ $item->id }}"></i></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Preco:
                            <span id="itemCarrinhoPreco" class="badge bg-primary rounded-pill">R$
                                {{ reais($item->preco) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Quantidade:
                            <span id="itemCarrinhoQtd" class="badge bg-primary rounded-pill">{{ $item->quantidade }}</span>
                        </li>

                        <li id="itemCarrinhoDesconto"
                            class="list-group-item d-flex justify-content-between align-items-center d-none">
                            desconto:
                            <span class="badge bg-primary rounded-pill">{{ $item->valor_desconto }}</span>
                        </li>


                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total:
                            <span id="itemCarrinhoValor" class="badge bg-primary rounded-pill">{{ $item->valor }}</span>
                        </li>
                    </ul>
                </div>

                {{-- <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center"
                            style="border: none; background-color: rgb(193, 194, 201)">
                            <a
                                style="color: black; font-weight: 500; text-decoration:none; font-size:16px">{{ $item->nome }}&ensp;{{ $item->tam ? '/' . $item->tam : '' }}</a>
                            <a class="badge  rounded-pill" data-bs-toggle="modal"
                                data-bs-target="#deleteItemCarrinho{{ $item->id }}"><i class="bi bi-x-square"
                                    style="font-size: 22px; cursor: pointer; color:black;"></i></a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center"
                            style="background-color: rgb(230, 227, 255)">
                            <a href="" style="color: black; font-weight: 400; text-decoration:none;">Total: R$
                                {{ reais($item->valor) }}</a><a class="badge  rounded-pill" data-bs-toggle="modal"
                                data-bs-target="#editaItem{{ $item->id }}"><i class="bi bi-pencil-square"
                                    style="font-size: 22px; cursor: pointer; color:black;"></i></a>
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
                {{-- </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Desconto:
                            @if ($carrinho->tp_desconto == 'porcento_unico' or !$carrinho->tp_desconto)
                                <span>{{ !$carrinho->tp_desconto ? 'Não Aplicado' : 'Porcentagem Única' }}</span>
                            @else
                                <span
                                    class="badge bg-primary rounded-pill">{{ $carrinho->tp_desconto == 'porcento_unico' ? 'Porcentagem única' : ($carrinho->tp_desconto == 'dinheiro_unico' ? 'Unificado em dinheiro' : (!$item->qtd_desconto ? 'Não inserido' : ($item->tipo_desconto == 'porcento' ? '%' . $item->qtd_desconto : "R$" . $item->qtd_desconto))) }}</span>
                            @endif
                        </li>
                    </ul>
                    <br> --}}

                {{-- Modais para editar itens da tabela de itens --}}
                @include('vendedor.inc.modais-lista-carrinho')
            @endforeach
        </div>
    @endif
@endsection
<script type="text/javascript" src="{{ asset('js/carrinho.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('js/venda.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('js/viewhome.js') }}" defer></script>

{{-- <script type="text/javascript" src="{{ asset('js/viewItensCarrinho.js') }}" defer></script> --}}
