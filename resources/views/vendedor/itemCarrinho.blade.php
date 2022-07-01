@extends('layouts.app')

@section('content')

    <style>
        .carregando {
            display: none;
        }

        #campoInserirEntrada {
            display: none;
        }

    </style>
    @include('componentes.navbar', ['titulo' => 'Itens'])
    <br><br><br><br>

    @if (Session::has('success'))

        <body onload="msgSuccess('<?php echo Session::get('success'); ?>')">
    @endif
    @if (Session::has('error'))

        <body onload="msgError('<?php echo Session::get('error'); ?>')">
    @endif
    @if (Session::has('descInvalido'))

        <body onload="descInvalido('<?php echo Session::get('descIvalido'); ?>')">
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
            <button id="modalUnificaclick" type="button" class="btn btn-primary col-5 mx-2" data-bs-toggle="modal"
                data-bs-target="#modalUnifica">
                Desconto
            </button>

            <button href="" type="button" class="btn btn-danger col-5 mx-2" data-bs-toggle="modal"
                data-bs-target="#cancelaVenda">
                Cancelar Venda
            </button>

        </div>
        <div class="row d-flex justify-content-center" style="margin-top: 10px;">

            @if ($carrinho->cliente)
                <button type="button" class="btn btn-success col-5 mx-2" data-bs-toggle="modal"
                    data-bs-target="#modalFinalizaVendaComCLientSalvo">
                    Finalizar
                </button>
                <button type="button" class="btn btn-info col-5 mx-2" data-bs-toggle="modal"
                    data-bs-target="#salvarItensRetornados">
                    Salvar Itens
                </button>
             
                {{-- finaliza venda com cliente  E salvar itens novamente para cliente --}}
                @include('vendedor.inc.com-cliente')
            @else
                <button id="abrirModalFinalizaVendaSemCliente" type="button" class="btn btn-success col-5 mx-2"
                    data-bs-toggle="modal" data-bs-target="#modalFinalizaVendaSemCliente">
                    Finalizar
                </button>
                <button type="button" class="btn btn-info col-5 mx-2" data-bs-toggle="modal" data-bs-target="#salvarItens">
                    Salvar Itens
                </button>

                {{-- Finaliza venda sem cliente E Salvar itens --}}
                @include('vendedor.inc.sem-cliente')
            @endif
        </div>
        <hr><br>
        {{-- tabela de itens principal --}}
        @if ($carrinho)
        <form id="formDescInvalido" action="{{route('descInvalido', $carrinho->id)}}" method="POST">
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
                        </li>
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
                    <br>

                    {{-- Modais para editar itens da tabela de itens --}}
                    @include('vendedor.inc.modais-lista-carrinho')
                @endforeach
            </div>
        @endif
    @endif
@endsection

<script type="text/javascript" src="{{ asset('js/viewItensCarrinho.js') }}" defer></script>
