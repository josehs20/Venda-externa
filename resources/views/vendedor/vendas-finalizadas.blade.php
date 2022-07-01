@extends('layouts.app')


@section('content')
    <style>
        .collapsible:after {
            display: none;
        }

        .iconeSearchCliente {
            margin-top: -3px;
        }
    </style>
    @include('componentes.navbar', ['titulo' => 'Finalizadas'])
    <br><br><br><br><br>
    <div class="listCliente">
        <div class="container">
            <form action="{{ route('vendas_finalizadas', auth()->user()->id) }}" class="position-relative col-md-6">

                <input name="nome_n_pedido" class="form-control" style=" border-radius: 15px;" placeholder="Buscar Venda"
                    type="search">
                <button class="btn position-absolute iconeSearchCliente">
                    <i class="fa fa-search"></i>
                </button>
            </form>

            @foreach ($carrinhos as $c)
                <ul class="list-group mt-3">
                    <button class="collapsible" data-bs-toggle="modal"
                        data-bs-target="#modalVendaFinalizada{{ $c->id }}"
                        style="background-color: rgb(58, 36, 252); font-size:16px;border-radius:7px;cursor: pointer;">
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
                    <div class="modal fade" id="modalVendaFinalizada{{ $c->id }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">ITENS
                                    </h5>

                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="row">
                                        <h6 class="col-4">Valor Bruto: R$
                                            <b>{{ reais($c->valor_bruto) }}</b>
                                        </h6>
                                        <h6 class="col-4">Descontos Totais: R$
                                            <b>{{ $c->valor_desconto + $c->valor_desconto_sb_venda }}</b>
                                        </h6>
                                        <h6 class="col-4">Total: R$
                                            <b>{{ reais($c->total) }}</b>
                                        </h6>
                                    </div>

                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th colspan="2">Nome</th>
                                                <th>Preço</th>
                                                <th>Qtd</th>
                                                <th>valor</th>
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
                                        <h6>Status:
                                            <b>{{ $c->status == 'fechado' ? 'Aguardando confirmação no EGI' : 'Fechado' }}</b>
                                        </h6>

                                        <h6 class="mx-5">Pedido Nº:
                                            <b>{{ $c->n_pedido }}</b>
                                        </h6>
                                        <br>
                                    </div>
                                    <div class="col-12">
                                        <div style="float: right;">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                                aria-label="Close">Sair</button>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                </ul>
            @endforeach
            <br>
            @if (!count($carrinhos))
                <div class="alert alert-warning mt-5" role="alert">
                    Nenhuma Venda Encontrada !
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

@endsection
