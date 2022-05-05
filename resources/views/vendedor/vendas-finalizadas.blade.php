@extends('layouts.app')


@section('content')
 

    <div id="contentIndex">
        @include('componentes.navbar')
        @include('componentes.titulo', [
            'titlePage' => 'Vendas Finalizadas',
        ])
        @if (sizeof($carrinho) == 0)
            <div class="alert alert-warning mt-5" role="alert">
                Nenhuma Venda Finalizada
            </div>
        @else
            <div class="listCliente">
                <div class="container">

                    @foreach ($carrinho as $c)
                        <ul class="list-group mt-3">
                            <button class="collapsible" data-bs-toggle="modal"
                                data-bs-target="#modalVendaFinalizada{{ $c->id }}"
                                style="background-color: rgb(58, 36, 252); font-size:16px;border-radius:7px;cursor: pointer; white-space: nowrap; overflow:hidden">
                                <h6 style="margin-top:-10px; margin-left: 10px;">
                                    {{ $c->cliente ? $c->cliente->nome : 'Cliente Não Informado' }}
                                </h6>
                            </button>

                            <!-- Modal Venda Salva-->
                            <div class="modal fade" id="modalVendaFinalizada{{ $c->id }}"
                                data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">Itens Da Venda
                                            </h5>

                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <h6 class="col-5">Valor Bruto: R$
                                                    <b>{{ reais($c->valor_bruto) }}</b>
                                                </h6>
                                                <h6 class="col-5">Descontos Totais: R$
                                                    <b>{{ $c->valor_desconto + $c->valor_desconto_sb_venda }}</b>
                                                </h6>
                                            </div>
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th colspan="2">Nome</th>
                                                        <th>Preço</th>
                                                        <th>Qtd.</th>
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
                                            <div class="row">
                                                <h6 class="col-12">Total: R$
                                                    <b>{{ reais($c->total) }}</b>
                                                </h6>
                                               
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                                aria-label="Close">Sair</button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </ul>
                    @endforeach
                </div>

            </div>
        @endif
    </div>
@endsection
