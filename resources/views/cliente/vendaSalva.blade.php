@extends('layouts.app')

@section('content')

    <style>
        .collapsible:after {
            display: none;
        }
    </style>

    @include('componentes.navbar', ['titulo' => 'Salvos'])
    <br><br><br><br><br>

    @if (Session::has('deleta_carrinho'))

        <body onload="msgContato(msg = 7)">
    @endif


    <div class="listCliente">
        <div class="container">
            <form action="{{ route('venda_salva', auth()->user()->id) }}" class="position-relative col-md-6">

                <input name="nome_n_pedido" class="form-control" style=" border-radius: 15px;" placeholder="Buscar Venda Salva"
                    type="search">
                <button class="btn position-absolute iconeSearchCliente">
                    <i class="fa fa-search"></i>
                </button>
            </form>
            @foreach ($carrinhos as $c)
                <ul class="list-group mt-3">
                    <button class="collapsible" data-bs-toggle="modal" data-bs-target="#vendaSalva{{ $c->id }}"
                        style="background-color: rgb(58, 36, 252); font-size:16px;border-radius:7px;cursor: pointer; white-space: nowrap; overflow:hidden">
                        <div style="display: flex; justify-content:space-between;">
                            <div style="width: 80%;white-space: nowrap;">
                                <h6
                                    style="margin-top:-10px; margin-left: 10px;white-space: nowrap;
                                overflow: hidden !important;
                                ">
                                    {{ $c->cliente ? $c->cliente->nome : 'Cliente Não Informado' }}
                                </h6>
                            </div>
                            <div>
                                <h6 style="margin-top:-10px; margin-left: 10px;">
                                    {{ $c->n_pedido ? $c->n_pedido : '' }}

                                </h6>
                            </div>
                        </div>
                    </button>

                    <!-- Modal Venda Salva-->
                    <div class="modal fade" id="vendaSalva{{ $c->id }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Itens</h5>

                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        {{-- ->selectRaw("sum(preco * quantidade) total")->get() --}}
                                        <h6 class="col-5">Total: R$
                                            <b>{{ reais($c->carItem()->selectRaw('sum(preco * quantidade) total')->first()->total) }}</b>

                                        </h6>
                                        <h6 class="col-5">Descontos Totais: R$
                                            <b>{{ reais($c->valor_desconto + $c->valor_desconto_sb_venda) }}</b>
                                        </h6>
                                    </div>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th colspan="2">Nome</th>
                                                <th>Preço</th>
                                                <th>Qtd</th>
                                                <th>Valor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($c->carItem as $item)
                                                <tr>
                                                    <td colspan="2">
                                                        {{ substr($item->produto['nome'], 0, 25) }}&ensp;{{ $item->iGrade ? '/' . $item->iGrade->tam : '' }}
                                                    </td>
                                                    <th>{{ reais($item->preco) }}</th>
                                                    <th>{{ $item->quantidade }}</th>
                                                    <th>{{ reais($item->preco * $item->quantidade) }}</th>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>

                                </div>
                                <div style="display: flex; justify-content:space-between" class="modal-footer">

                                    <div class="col-12" style="display: flex;">
                                        <h6 class="col-6">Status:
                                            <b> {{ $c->status }}</b>
                                        </h6>
                                        <h6 class="col-6">Pedido Nº:
                                            <b>{{ $c->n_pedido }}</b>
                                        </h6>

                                    </div>

                                    <div class="col-12">
                                        {{-- Verifica se contem item no carrinho caso sim verifica se tem cliente ja salvo no carrinho --}}
                                        <div style="float: right;">
                                            @if (!count_item(auth()->user()->id))
                                                <form action="{{ route('substitui_carrinho', $c->id) }}" method="POST">
                                                    @method('PUT')
                                                    @csrf

                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Sair</button>

                                                    <button type="submit" name="substituir" value="0"
                                                        class="btn btn-primary">Continuar Venda</button>

                                                </form>
                                            @else
                                                <button type="button" class="btn btn-secondary fechaModal"
                                                    id="fechaModalVendaSalva<?php echo $c->id; ?>" data-bs-dismiss="modal"
                                                    ata-bs-dismiss="modal" aria-label="Close">Sair</button>

                                                <button type="button" data-bs-toggle="modal" class="btn btn-primary"
                                                    id="continuaVenda<?php echo $c->id; ?>"
                                                    onclick="continuaVenda(<?php echo $c->id; ?>)"
                                                    data-bs-target="#confirmarContinuacaoVenda{{ $c->id }}">Continuar
                                                    Venda</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal  Confirma continuar venda-->
                    <div class="modal fade" id="confirmarContinuacaoVenda{{ $c->id }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Confirmação</h5>
                                    <button type="button" id="fechaModalSalva" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
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
                    </div>
                    <!-- Fim Modal  Confirma continuar venda-->
                </ul>
            @endforeach
            <br>
            @if (!count($carrinhos))
                <div class="alert alert-warning mt-5" role="alert">
                    Nenhuma venda em espera !
                </div>
            @else
                {{-- paginação --}}
                <nav class="navegacao" aria-label="Navegação">
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
                </nav>
        </div>

    </div>
    @endif
    </div>
@endsection
<script type="text/javascript" src="{{ asset('js/viewcliente.js') }}" defer></script>
