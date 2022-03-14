@extends('layouts.app')

@section('content')

    @include('componentes.navbar')
    @include('componentes.titulo', ['titlePage' => 'Vendas Salvas'])

    @if (Session::has('deleta_carrinho'))

        <body onload="msgContato(msg = 7)">
    @endif
    @if (sizeof($carrinhos_Salvos) == 0)
        <div class="alert alert-warning mt-5" role="alert">
            Nenhuma Venda Salva :)
        </div>
    @else
        <div class="listCliente">
            <div class="container">

                @foreach ($carrinhos_Salvos as $carrinho)
                    <ul class="list-group mt-3">
                        <button class="collapsible" data-bs-toggle="modal"
                            data-bs-target="#vendaSalva{{ $carrinho->id }}"
                            style="background-color: rgb(58, 36, 252); font-size:16px;border-radius:7px;cursor: pointer; white-space: nowrap; overflow:hidden">
                            <h6 style="margin-top:-10px; margin-left: 10px;">
                                {{ $carrinho->cliente ? $carrinho->cliente->nome : 'Cliente Não Informado' }}
                            </h6>
                        </button>

                        <!-- Modal Venda Salva-->
                        <div class="modal fade" id="vendaSalva{{ $carrinho->id }}" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Itens salvos neste
                                            carrinho</h5>

                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <h6 class="col-5">Total: R$
                                                <b>{{ reais($carrinho['somaItens'][0]['total']) }}</b>
                                            </h6>
                                            <h6 class="col-7">Descontos Totais: 0000</h6>
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
                                                @foreach ($carrinho->carItem as $item)
                                                    <tr>
                                                        <td colspan="2">{{ substr($item['nome'], 0, 25) }}</td>
                                                        <th>{{ reais($item->preco) }}</th>
                                                        <th>{{ $item->quantidade }}</th>
                                                        <th>{{ reais($item->preco * $item->quantidade) }}</th>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                    {{-- Verifica se contem item no carrinho caso sim verifica se tem cliente ja salvo no carrinho --}}
                                    @if (!count_item(auth()->user()->id))
                                        )

                                        <form action="{{ route('substitui_carrinho', $carrinho->id) }}" method="POST"
                                            class="modal-footer">
                                            @method('PUT')
                                            @csrf

                                            <button type="button" class="btn btn-secondary"
                                                id="fechaModal{{ $carrinho->id }}" data-bs-dismiss="modal">Sair</button>

                                            <button type="button" onclick="fechaModal(<?php echo $carrinho->id; ?>)"
                                                class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#ConfirmDeleteVendaSalva">
                                                Excluir
                                            </button>

                                            <button type="submit" name="substituir" value="0"
                                                class="btn btn-primary">Continuar Venda</button>

                                        </form>
                                    @else
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                id="fechaModal{{ $carrinho->id }}" data-bs-dismiss="modal">Sair</button>

                                            <button type="button" onclick="fechaModal(<?php echo $carrinho->id; ?>)"
                                                class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#ConfirmDeleteVendaSalva">
                                                Excluir
                                            </button>

                                            <button type="button" onclick="fechaModal(<?php echo $carrinho->id; ?>)"
                                                data-bs-toggle="modal" class="btn btn-primary"
                                                id="confirmarContinuacaoVenda"
                                                data-bs-target="#confirmarContinuacaoVenda{{ $carrinho->id }}">Continuar
                                                Venda</button>

                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Modal  Confirma continuar venda-->
                        <div class="modal fade" id="confirmarContinuacaoVenda{{ $carrinho->id }}"
                            data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
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

                                            <form action="{{ route('substitui_carrinho', $carrinho->id) }}" method="POST"
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
                                            <span>Seu carrinho contém itens, deseja substituir ou salvar eles ?</span>

                                            <form action="{{ route('substitui_carrinho', $carrinho->id) }}" method="POST"
                                                class="modal-footer">
                                                @method('PUT')
                                                @csrf

                                                <button type="submit" class="btn btn-secondary" name="substituir"
                                                    value="1">Substituir</button>
                                                <button type="button" onclick="fechaModalSalvaVenda()"
                                                    data-bs-toggle="modal" class="btn btn-primary"
                                                    data-bs-target="#salvarItens">Salvar</button>

                                            </form>
                                        @endif
                                    @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Fim Modal  Confirma continuar venda-->

                        {{-- Modal Salvar Itens --}}
                        <div class="modal fade" id="salvarItens" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Salvar</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>

                                    <form action="{{ route('busca_cliente_ajax') }}" method="GET" class="search-cliente">
                                        @csrf
                                        <input id="nomeCliente" name="nomeCliente" type="text" class="form-control"
                                            placeholder="nome">
                                        <button id="preventCliente" class="lupa"><i class="bi-search"
                                                style="color: black"></i></button>
                                    </form>
                                    <form action="{{ route('salvar_venda') }}" method="POST" class="modal-body">
                                        @method('PUT')
                                        @csrf
                                        <a id="lisClientesModal">
                                            <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>
                                            <ul class="list-group">
                                               
                                                @foreach ($clientes_user as $cliente)
                                                    <li style="text-align:justify; overflow-x: auto; overflow-y: hidden;overflow-y: hidden;"
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        {{ $cliente->nome }}
                                                        <input type="hidden" name="cliente_id"
                                                            value="{{ $cliente->id }}" />
                                                        <button type="submit" class="lupa-list"><i
                                                                class="bi bi-save2"></i></button>
                                                    </li>
                                                @endforeach

                                            </ul>
                                        </a>
                                    </form>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal" aria-label="Close">Sair</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        {{-- Fim Modal Salvar Itens --}}

                        {{-- Modal Confirma Exluir venda Salva --}}
                        <div class="modal fade" id="ConfirmDeleteVendaSalva" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Confirmação</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <span>Deseja Realmente excluir esse carrinho?</span>
                                    </div>
                                    <form action="{{ route('substitui_carrinho', $carrinho->id) }}" method="POST"
                                        class="modal-footer">
                                        @method('PUT')
                                        @csrf
                                        <button type="button" id="fechaModal{{ $carrinho->id }}" data-bs-dismiss="modal"
                                            aria-label="Close" class="btn btn-secondary">
                                            Sair
                                        </button>
                                        <button type="submit" name="deleteCarrinho" value="1"
                                            class="btn btn-primary">Sim</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- Modal Exluir venda Salva --}}
                    </ul>
                @endforeach
            </div>
           
        </div>
    @endif
@endsection
<script type="text/javascript" src="{{ asset('js/viewcliente.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('js/viewItensCarrinho.js') }}" defer></script>
