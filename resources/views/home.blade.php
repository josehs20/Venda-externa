@extends('layouts.app')
<meta name="csrf_token" content="{{ csrf_token() }}" />
@section('content')
    <style>
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

    </style>
    @include('componentes.navbar')
    @include('componentes.titulo', ['titlePage' => 'Produtos'])
    @if (Session::has('cancelar_carrinho'))

        <body onload="msgSuccess('Venda Cancelado Com Sucesso')">
        @elseif (Session::has('carrinho_finalizado'))

            <body onload="msgSuccess('Venda Finalizado Com Sucesso')">
    @endif
    <div id="contentIndex">
        <div id="elemento_ajax_html" name="addItem" method="POST" class="list">

            @foreach ($produtos as $produto)
                <a class="listHome" style="cursor: pointer">
                    <ul class="list-group">
                        <li class="list-group-item" style="background-color: rgb(58, 36, 252)">
                            <div class="listCar">
                                <h6 style="color: white">{{ $produto->nome }}</h6>
                                @if ($produto->grades)
                                    <button type="button" class="buttonAdd" data-bs-toggle="modal"
                                        data-bs-target="#Grade{{ $produto->id }}"><img id="imgg" class="imgCarr"
                                            src="addCar.ico" alt=""></button>
                                @else
                                   
                                    <button type="button" class="buttonAdd" data-bs-toggle="modal"
                                        data-bs-target="#SemGrade{{ $produto->id }}"><img id="imgg" class="imgCarr"
                                            src="addCar.ico" alt="">
                                @endif

                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="listCar">
                                <span> Preço :</span>

                                <h5>R$ {{ reais($produto->preco) }}</h5>

                            </div>
                        </li>

                    </ul>
                </a>
                {{-- Modal Sem grade --}}
                <div class="modal fade" id="SemGrade{{ $produto->id }}" data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <form id="formModalSemGrade" name="addItemSemGrade" method="POST" class="list">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">{{ $produto->nome }}</h5>

                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <div class="input-group mb-3">
                                        <div class="input-group-text">
                                            <span>Quantidade</span>
                                        </div>
                                       
                                        @if (array_key_exists($produto->id, $produtos_carrinho_quantidade['itemCarrinho']))
                                            <input id="inputProdutoSemGrade<?php echo $produto->id; ?>" class="form-control"
                                                type="number" min="0" max="100000" step=".01"
                                                value="{{ $produtos_carrinho_quantidade['itemCarrinho'][$produto->id] }}">
                                        @else
                                            <input id="inputProdutoSemGrade<?php echo $produto->id; ?>" class="form-control"
                                                type="number" min="0" max="100000" step=".01" placeholder="0.0">
                                        @endif
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button id="fechaModalSemGrade<?php echo $produto->id; ?>" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sair</button>
                                    <button onclick="cli(<?php echo $produto->id; ?>)" type="submit"
                                        class="btn btn-primary">Adicionar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- Fim Modal sem Grade --}}

                @if ($produto->grades)
                    {{-- Modal Caso Produtos tenham grade --}}
                    <div class="modal fade" id="Grade{{ $produto->id }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable">
                            <form id="formModalComGrade" name="addItemComGrade" method="POST" class="list">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">{{ $produto->nome }} /
                                            {{ $produto->grades->nome }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @foreach ($produto->grades->igrades as $ig)
                                            @if (array_key_exists($ig->id, $produtos_carrinho_quantidade['itemCarrinhoGrade']) && $produtos_carrinho_quantidade['itemCarrinhoGrade']['produto_id'] == $produto->id)
                                                <div class="input-group mb-3">
                                                    <div class="input-group-text">
                                                        <input class="form-check-input mt-0 valid_check" type="checkbox"
                                                            value="{{ $ig->id }}">
                                                    </div>
                                                    <div class="input-group-text">
                                                        <span class="">{{ $ig->tam }}</span>
                                                    </div>
                                                    <input class="form-control valid_input" type="number" min="0" max="100000" step=".01"
                                                        placeholder="{{ $produtos_carrinho_quantidade['itemCarrinhoGrade'][$ig->id] }}">


                                                    {{-- <span class="input-group-text">% {{'a'}}</span> --}}
                                                </div>
                                            @else
                                                <div class="input-group mb-3">
                                                    <div class="input-group-text">
                                                        <input class="form-check-input mt-0 valid_check" type="checkbox"
                                                            value="{{ $ig->id }}">
                                                    </div>
                                                    <div class="input-group-text">
                                                        <span class="">{{ $ig->tam }}</span>
                                                    </div>
                                                    <input class="form-control valid_input" type="number" min="0" max="100000" step=".01" placeholder="Quantidade">

                                                </div>
                                            @endif
                                        @endforeach

                                    </div>
                                    <div class="modal-footer">
                                        <button id="fechaModalComGrade<?php echo $produto->id; ?>" type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Sair</button>
                                        <button onclick="verifyGrade(<?php echo $produto->id; ?>)" type="submit"
                                            class="btn btn-primary">Adicionar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- Fim Modal Item Com Grade --}}
                @endif
            @endforeach
        </div>


        {{-- paginação --}}
        <nav class="navegacao" aria-label="Navegação">
            <ul class="pagination" style="justify-content: center;">
                <li class="page-item">
                    <a class="page-link" href="{{ $produtos->previousPageUrl() }}" aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">voltar</span>
                    </a>
                </li>

                @for ($i = 1; $i <= ($produtos->lastPage() >= 6 ? 6 : $produtos->lastPage()); $i++)
                    <!-- a Tag for another page -->
                    <li class="page-item"><a class="page-link"
                            href="{{ $produtos->url($i) }}">{{ $i }}</a></li>
                @endfor

                <li class="page-item">
                    <a class="page-link" href="{{ $produtos->url($produtos->lastPage()) }}" aria-label="Próximo">
                        <span aria-hidden="true">... {{ $produtos->lastPage() }}</span>

                    </a>
                </li>
            </ul>
        </nav>
    </div>
@endsection
<script type="text/javascript" src="{{ asset('js/viewhome.js') }}" defer></script>
