@extends('layouts.app')

@section('content')
    @include('componentes.navbar')
    @include('componentes.titulo', [
        'titlePage' => 'Carrinho',
    ])
    {{-- Modal Desconto Unificado --}}
    @if (Session::has('message'))

        <body onload="msgContato(msg = 3)">
        @elseif (Session::has('carrinho_salvo'))

            <body onload="msgContato(msg = 4)">
            @elseif(Session::has('substituicao'))

                <body onload="msgContato(msg = 8)">
                @elseif(Session::has('item_deletado_carrinho'))

                    <body onload="msgContato(msg = 9)">
                    @elseif(Session::has('item_alterado_carrinho'))

                        <body onload="msgContato(msg = 10)">
                        @elseif(Session::has('item_unificado_carrinho'))

                            <body onload="msgContato(msg = 11)">
                            @elseif(Session::has('item_zerado_carrinho'))

                                <body onload="msgContato(msg = 12)">
                                @elseif(Session::has('quantidade_alterada'))

                                    <body onload="msgContato(msg = 13)">
    @endif
    @if (!isset($itens))
        <div id="contentIndex">
            <div class="alert alert-warning mt-5" role="alert">
                Adicione Produtos No Seu Carrinho De Vendas :)
            </div>
        </div>
    @else
        <!-- Modal Unifica -->
        <div class="modal fade" id="modalUnifica" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Unificar Desconto</h5><br>
                        <button id="fechaModalUnificaDesconto" type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <form method="post" action="{{ route('unifica_valor_Itens', ['carrinho' => $itens->id]) }}">
                        @csrf
                        @method('put')
                        <div class="modal-body">
                            <div class="row g-2">
                                <div class="col-8">
                                    <div class="form-floating">
                                        <input required name="qtd_unificado" type="number" class="form-control" min="0.01"
                                            step="0.01" id="floatingInputGrid" placeholder="Desconto Geral">
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
                            <a onclick="fechaModalUnificaDesconto()" data-bs-toggle="modal"
                                data-bs-target="#confzerarDesconto" style="width:140px;" type="button"
                                class="btn btn-danger col-5 mx-2">
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
                        <h5 class="modal-title" id="staticBackdropLabel">Cancelar venda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Deseja Realmente Zerar o Desconto Deste Carrinho ?
                    </div>

                    <form action="{{ route('zeraDesconto', ['carrinho' => $itens]) }}" method="POST"
                        class="modal-footer">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="verify" value="1">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
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
                    <form action="{{ route('venda.destroy', ['venda' => $itens]) }}" method="POST" class="modal-footer">
                        @method('DELETE')
                        @csrf
                        <input type="hidden" name="verify" value="1">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não</button>
                        <button type="submit" class="btn btn-primary">Sim</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Fim Modal confirma concelamento de venda -->

        {{-- cards iniciais --}}
        <div class="listItemCarrinho">
            <div class="container">
                <div class="row d-flex justify-content-center">

                    <div class="col-lg-6 col-sm-6">
                        <div class="card-box bg-green">
                            <div class="inner">
                                <h6><i class="bi bi-cash-coin"> Sem
                                        Desconto:&ensp;R$<u>{{ reais($itens->valor_bruto) }}</u></i>
                                </h6>

                            </div>
                            <div class="inner">
                                <h6><i class="bi bi-cash-coin"> Com
                                        Desconto:&ensp;<u>{{ 'R$' . reais($valor_itens_desconto) }}</u></i>
                                </h6>
                            </div>
                            <div class="icon">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <a href="#" class="card-box-footer">Valor Total<i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="card-box bg-orange">
                            <div class="inner">
                                <i class="bi bi-cash-stack">&ensp;{{ $itens->valor_desconto }} </i>
                            </div>
                            <div class="inner">
                                <p>&ensp;{{ $itens->tp_desconto == 'porcento_unico'? "Unificado em % $itens->desconto_qtd": ($itens->tp_desconto == 'dinheiro_unico'? "Unificado em R$ $itens->desconto_qtd": (!$itens->tp_desconto? 'Nenhum Desconto Aplicado': 'Desconto Dado Parcialmente')) }}
                                </p>
                            </div>


                            <div class="icon">
                                <i class="bi bi-percent"></i>

                            </div>
                            <a href="#" class="card-box-footer">Total De Desconto<i
                                    class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Fim cards iniciais --}}
        <div class="row d-flex justify-content-center">
            <button type="button" class="btn btn-primary col-5 mx-2" data-bs-toggle="modal" data-bs-target="#modalUnifica">
                Desconto
            </button>

            <button href="" type="button" class="btn btn-danger col-5 mx-2" data-bs-toggle="modal"
                data-bs-target="#cancelaVenda">
                Cancelar Venda
            </button>

        </div>
        <div class="row d-flex justify-content-center" style="margin-top: 10px;">

            <button type="button" class="btn btn-success col-5 mx-2" data-bs-toggle="modal" data-bs-target="#modalUnifica">
                Finalizar
            </button>

            @if ($itens->cliente)
                <button type="button" class="btn btn-info col-5 mx-2" data-bs-toggle="modal"
                    data-bs-target="#salvarItensRetornados">
                    Salvar Itens
                </button>

                {{-- Modal salvar itens --}}
                <div class="modal fade" id="salvarItensRetornados" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('salvar_venda') }}" method="POST" class="modal-content">
                            @method('PUT')
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Salvar Itens</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Carrinho já esta salvo para <b>{{ $itens->cliente->nome }}</b>, deseja continuar?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sair</button>
                                <input type="hidden" name="cliente_id" value="{{ $itens->cliente_id }}" />
                                <button type="submit" class="btn btn-primary">Sim</button>
                            </div>
                        </form>
                    </div>
                </div>
        </div>
    @else
        <button type="button" class="btn btn-info col-5 mx-2" data-bs-toggle="modal" data-bs-target="#salvarItens">
            Salvar Itens
        </button>
    @endif
    {{-- Modal Salvar Itens --}}
    <div class="modal fade" id="salvarItens" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Salvar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('busca_cliente_ajax') }}" method="GET" class="search-cliente">
                    @csrf
                    <input id="nomeCliente" name="nomeCliente" type="text" class="form-control" placeholder="nome">
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
                                    <button type="submit" name="cliente_id" value="{{ $cliente->id }}"
                                        class="lupa-list"><i class="bi bi-save2"></i></button>
                                </li>
                            @endforeach

                        </ul>
                    </a>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sair</button>
                </div>

            </div>
        </div>
    </div>
    {{-- Fim Modal Salvar Itens --}}
    </div>
    <hr><br>
    {{-- tabela de itens --}}
    @if ($itens)
        <div class="container">
            @foreach ($itens->carItem as $item)
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
                        @if ($tp_desconto)
                            <span>Desconto Unico</span>
                        @else
                            <span
                                class="badge bg-primary rounded-pill">{{ $itens->tp_desconto == 'porcento_unico'? 'Porcentagem única': ($itens->tp_desconto == 'dinheiro_unico'? 'Unificado em dinheiro': (!$item->qtd_desconto? 'Não inserido': ($item->tipo_desconto == 'porcento'? '%' . $item->qtd_desconto: "R$" . $item->qtd_desconto))) }}</span>
                        @endif
                    </li>
                    <br>

                    {{-- Modal carrinho item editar --}}
                    <div class="modal fade" id="editaItem{{ $item->id }}" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" style="margin-left: 35%;" id="exampleModalLabel">Editar
                                        Item
                                    </h5><br>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <br>
                                <label class="mx-2" for=""> Produto: <b>
                                        {{ $item->produto->nome }}</b></label>
                                <hr>
                                <label class="mx-2" for=""> Quantidade Atual: <b>
                                        {{ $item->quantidade }}</b></label>
                                <hr>
                                <label class="mx-2" for=""> Preco: <b>R$
                                        {{ reais($item->preco) }}</b></label>
                                <hr>


                                <form method="POST" action="{{ route('venda.update', ['venda' => $item->id]) }}">
                                    @method('PUT')
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row g-2">
                                            <div class="col-8">
                                                <div class="form-floating">
                                                    <input name="quantidade" type="number" class="form-control"
                                                        min="0.01" step="0.01" value="{{ $item->quantidade }}"
                                                        id="floatingInputGrid" placeholder="quantidade">
                                                    <label for="floatingInputGrid">Quantidade</label>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-floating">
                                                    <select name="tipo_qtd" class="form-select" id="floatingSelectGrid"
                                                        aria-label="Floating label select example">
                                                        <option value="UN">UN</option>
                                                        <option value="CX">CX</option>
                                                    </select>
                                                    <label for="floatingSelectGrid">Tipo</label>
                                                </div>
                                            </div>
                                        </div>

                                        <br>

                                        @if ($itens->tp_desconto == 'porcento_unico' or $itens->tp_desconto == 'dinheiro_unico')
                                            <span>Desconto único já inserido, para inserir desconto individual é necessário
                                                zerar o desconto.</span>
                                        @else
                                            <div class="row g-2">
                                                <div class="col-8">
                                                    <div class="form-floating">
                                                        <input name="qtd_desconto" type="number" class="form-control"
                                                            min="0.01" step="0.01" value="{{ $item->qtd_unificado }}"
                                                            id="floatingInputGrid" placeholder="Desconto Geral">
                                                        <label for="floatingInputGrid">Desconto</label>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-floating">
                                                        <select name="tipo_desconto" class="form-select"
                                                            id="floatingSelectGrid"
                                                            aria-label="Floating label select example">
                                                            <option disabled selected>selecione...</option>
                                                            <option value="porcento">%</option>
                                                            <option value="dinheiro">R$</option>
                                                        </select>
                                                        <label for="floatingSelectGrid">Tipo</label>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                            aria-label="Close">Sair</button>

                                        <button type="submit" class="btn btn-primary">Confirmar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- fim modal editar --}}

                    {{-- Modal Excluir item do Carrinnho --}}
                    <div class="modal fade" id="deleteItemCarrinho{{ $item->id }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Confirmação</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <span>Deseja realmente excluir esse Item, <b>{{ $item->produto->nome }}</b>?</span>
                                </div>
                                <form action="{{ route('destroyItem', ['item' => $item->id]) }}" method="POST"
                                    class="modal-footer">
                                    @method('DELETE')
                                    @csrf
                                    <button type="button" id="#" data-bs-dismiss="modal" aria-label="Close"
                                        class="btn btn-secondary">
                                        Sair
                                    </button>
                                    <button type="submit" name="deleteCarrinho" value="1"
                                        class="btn btn-primary">Sim</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    {{-- Fim Modal Excluir item do Carrinnho --}}
            @endforeach
        </div>
    @endif
    @endif
    {{-- Fim tabela de itens --}}
@endsection
<script type="text/javascript" src="{{ asset('js/viewItensCarrinho.js') }}" defer></script>
