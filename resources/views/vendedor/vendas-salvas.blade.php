@extends('layouts.app')

@section('content')
    <style>
        .collapsible:after {
            display: none;
        }
    </style>

    @include('componentes.navbar', ['titulo' => 'Salvos'])
    <br><br><br><br><br>

    @if (Session::has('success'))

        <body onload="alertPadrao('<?php echo Session::get('success'); ?>', 'success')">
    @endif
    @if (Session::has('error'))

        <body onload="msgError('<?php echo Session::get('error'); ?>', 'error')">
    @endif


    <div class="listCliente">
        <div class="container">
            <form action="{{ route('vendas_salvas', auth()->user()->id) }}" class="position-relative col-md-6">
                <input name="nome_n_pedido" class="form-control" style=" border-radius: 15px;" placeholder="Buscar Venda Salva"
                    type="search">
                <button class="btn position-absolute iconeSearchCliente">
                    <i class="fa fa-search"></i>
                </button>
            </form>

            <ul class="list-group" style="cursor: pointer;">
                @foreach ($carrinhos as $key_cliente => $carrinho)
                    @foreach ($carrinho as $c)
                        <li onclick='modalItensVendaSalva(<?php echo $c; ?>,<?php echo json_encode($produtos); ?> )'
                            style="background-color: #00a3ef; border-radius:10px;"
                            class="list-group-item d-flex justify-content-between align-items-center mt-3">
                            <a
                                style="color: white; cursor: pointer;">{{ $clientes[$c->cliente_id_alltech] ? $clientes[$c->cliente_id_alltech]->nome : 'Cliente não informado' }}</a>
                            <span class="badge rounded-pill">{{ $c->n_pedido }}</span>
                        </li>
                        {{-- <ul class="list-group mt-3">
                        <button class="collapsible" data-bs-toggle="modal" data-bs-target="#vendaSalva{{ $c->id }}"
                            style="background-color: rgb(58, 36, 252); font-size:16px;border-radius:7px;cursor: pointer; white-space: nowrap; overflow:hidden">
                            <div style="display: flex; justify-content:space-between;">
                                <div style="width: 80%;white-space: nowrap;">
                                    <h6
                                        style="margin-top:-10px; margin-left: 10px;white-space: nowrap;
                                overflow: hidden !important;
                                ">
                                {{dd($c->cliente_id_alltech)}}
                                        {{ $c->cliente ? $c->cliente->nome : 'Cliente Não Informado' }}
                                    </h6>
                                </div>
                                <div>
                                    <h6 style="margin-top:-10px; margin-left: 10px;">
                                        {{ $c->n_pedido ? $c->n_pedido : '' }}
                                        
                                    </h6>
                                </div>
                            </div>
                        </button> --}}

                        <!-- Modal Venda Salva-->


                        <!-- Modal  Confirma continuar venda-->
                        {{-- <div class="modal fade" id="confirmarContinuacaoVenda{{ $c->id }}" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Confirmação</h5>
                                        <button type="button" id="fechaModalSalva" class="btn-close"
                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" id="notificacaoCarrinhoItens">

                                        @if ($cliente_carrinho)
                                            @if ($cliente_carrinho->cliente)
                                                <span>Seu carrinho contém itens, salvos para
                                                    <b>{{ $cliente_carrinho->cliente->nome }}</b> deseja
                                                    salvar e substituir ?</span>

                                                    <form action="{{ route('substitui_carrinho', $c->id) }}" method="POST"
                                                        class="modal-footer">
                                                        @method('PUT')
                                                        @csrf
                                                        
                                                        <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Sair</button>
                                                        
                                                        <button type="submit" class="btn btn-primary js-salvaItens"
                                                        name="substituir" value="1">Salvar e
                                                        substituir</button>

                                                    </form>
                                            @else
                                                <span>Seu carrinho contém itens, deseja substituir?</span>
                                                
                                                <form action="{{ route('substitui_carrinho', $c->id) }}" method="POST"
                                                    class="modal-footer">
                                                    @method('PUT')
                                                    @csrf
                                                    
                                                    <button type="button" data-bs-dismiss="modal"
                                                    class="btn btn-secondary">Sair</button>
                                                    <button type="submit" class="btn btn-primary" name="substituir"
                                                        value="1">Substituir</button>
                                                        
                                                </form>
                                            @endif
                                            @endif
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <!-- Fim Modal  Confirma continuar venda-->
                        {{-- </ul> --}}
                    @endforeach
                @endforeach
            </ul>
            <br>
            @if (!count($carrinhos))
                <div class="alert alert-warning mt-5" role="alert">
                    Nenhuma venda em espera !
                </div>
            @else
                {{-- paginação --}}
                {{-- <nav class="navegacao" aria-label="Navegação">
                    <ul class="pagination" style="justify-content: center;">
                        <li class="page-item">
                            <a class="page-link" href="{{ $carrinhos->previousPageUrl() }}" aria-label="Anterior">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">voltar</span>
                            </a>
                        </li>

                        @for ($i = 1; $i <= ($carrinhos->lastPage() >= 6 ? 6 : $carrinhos->lastPage()); $i++)
                            <!-- a Tag for another page -->
                            <li class="page-item"><a class="page-link"
                                    href="{{ $carrinhos->url($i) }}">{{ $i }}</a></li>
                        @endfor

                        <li class="page-item">
                            <a class="page-link" href="{{ $carrinhos->url($carrinhos->lastPage()) }}"
                                aria-label="Próximo">
                                <span aria-hidden="true">... {{ $carrinhos->lastPage() }}</span>

                            </a>
                        </li>
                    </ul>
                </nav> --}}
        </div>

    </div>
    @endif
@endsection
<script type="text/javascript" src="{{ asset('js/vendaSalva.js') }}" defer></script>

{{-- <script type="text/javascript" src="{{ asset('js/viewcliente.js') }}" defer></script> --}}
