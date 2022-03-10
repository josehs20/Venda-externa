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
    @endif
    @if (!$itens)
        <div class="alert alert-warning mt-5" role="alert">
            Adicione Produtos No Seu Carrinho De Vendas :)
        </div>
    @else
        <!-- Modal Unifica -->
        <div class="modal fade" id="modalUnifica" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Unificar Desconto</h5><br>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form method="post"
                        action="{{ route('unifica_valor_Itens', ['itensCarr' => $itens ? $itens : null]) }}">
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
                                            <option value="Porcentagem">%</option>
                                            <option value="Dinheiro">R$</option>
                                        </select>
                                        <label for="floatingSelectGrid">Tipo</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <a {{-- href="{{ route('itens_carrinho') }}" --}} style="width:140px;" type="button" class="btn btn-danger col-5 mx-2">
                                Zerar Desconto
                            </a>
                            <button type="submit" class="btn btn-primary">Confirmar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Fim Modal Unificado --}}

        {{-- Modal Salvar Itens --}}
        <div class="modal fade" id="salvarItens" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Salvar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('salvar_venda') }}" method="post" class="search-cliente">
                        @method('PUT')
                        @csrf
                        <input id="nomeCliente" required name="nomeCliente" type="text" class="form-control"
                            placeholder="nome">
                        <button type="submit" class="lupa"><img src="{{ asset('image/disquete.png') }}"
                                alt=""></button>
                    </form>

                    <div class="modal-body">
                        <ul class="list-group">
                            <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>
                            @foreach ($clientes_user as $cliente)
                                <form action="{{ route('salvar_venda') }}" method="post" class="mt-1">
                                    @method('PUT')
                                    @csrf
                                    <li style="text-align:justify; overflow-x: auto; overflow-y: hidden;overflow-y: hidden;"
                                        class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $cliente->nome }}
                                        <input type="hidden" name="cliente_id" value="{{ $cliente->id }}" />
                                        <button type="submit" class="lupa-list"><i class="bi bi-save2"></i></button>
                                    </li>
                                </form>
                            @endforeach

                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sair</button>
                    </div>

                </div>
            </div>
        </div>
        {{-- Fim Modal Salvar Itens --}}

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
                        Deseja realmente retirar todos os itens do carrinho?
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
                                        Desconto:&ensp;R$<u>{{ reais($valor_itens_total->total) }}</u></i></h6>

                            </div>
                            <div class="inner">
                                <h6><i class="bi bi-cash-coin"> Com
                                        Desconto:&ensp;R$<u>{{ reais($valor_itens_desconto) }}</u></i>
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
                                <i class="bi bi-cash-stack">&ensp;{{ $total_desconto_valor }} </i>
                            </div>
                            <div class="inner">
                                <p>&ensp;{{ $tp_desconto == 'Porcentagem_unificado'? "Unificado em % $itens->desconto_qtd": ($tp_desconto == 'Dinheiro_unificado'? "Unificado em R$ $itens->desconto_qtd": 'Não unificado') }}
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

            <button href="" type="button" class="btn btn-danger col-5 mx-2" data-bs-toggle="modal" data-bs-target="#cancelaVenda">
                Cancelar Venda
            </button>

        </div>
        <div class="row d-flex justify-content-center" style="margin-top: 10px;">
            <button type="button" class="btn btn-success col-5 mx-2" data-bs-toggle="modal" data-bs-target="#modalUnifica">
                Finalizar
            </button>

            <button type="button" class="btn btn-info col-5 mx-2" data-bs-toggle="modal" data-bs-target="#salvarItens">
                Salvar Itens
            </button>
        </div>
        <hr><br>
        {{-- tabela de itens --}}
        @if ($itens)
            <div class="container">
                @foreach ($itens->carItem as $item)
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center"
                            style="border: none; background-color: rgb(193, 194, 201)">
                            <a href=""
                                style="color: black; font-weight: 500; text-decoration:none;">{{ $item->nome }}</a>
                            <span class="badge  rounded-pill"><i class="bi bi-x-square"
                                    style="font-size: 22px; color:black"></i></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center"
                            style="background-color: rgb(230, 227, 255)">
                            <a href="" style="color: black; font-weight: 400; text-decoration:none;">Total: R$
                                {{ reais($item->preco * $item->quantidade) }}</a>
                            <span class="badge rounded-pill"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Preço:
                            <span class="badge bg-primary rounded-pill">R$ {{ reais($item->preco) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Quantidade:
                            {{-- <span class="badge bg-primary rounded-pill">{{ $item->quantidade }}</span> --}}
                            <div class="input-group list-qtd" style="width: 50%;">
                                <button class="btn btn-link" type="button"><i class="bi bi-dash-circle"></i></button>
                                <input type="text" class="form-control" placeholder="" value="{{ $item->quantidade }}">
                                <button class="btn btn-link" type="button"><i class="bi bi-plus-circle"></i></button>
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Desconto:
                            @if ($tp_desconto)
                                <span>Desconto Unico</span>
                            @else
                                <span
                                    class="badge bg-primary rounded-pill">{{ !$item->qtd_desconto? 'Não inserido': ($item->tipo_desconto == 'Porcentagem'? '%' . $item->qtd_desconto: "R$" . $item->qtd_desconto) }}</span>
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
                                    <label class="mx-2" for=""> Produto: <b> {{ $item->nome }}</b></label>
                                    <hr>
                                    <label class="mx-2" for=""> Quantidade Atual: <b>
                                            {{ $item->quantidade }}</b></label>
                                    <hr>
                                    <label class="mx-2" for=""> Preco: <b>R$
                                            {{ reais($item->preco) }}</b></label>
                                    <hr>
                                    <form method="post"
                                        action="{{ route('unifica_valor_Itens', ['itensCarr' => $itens ? $itens : null]) }}">
                                        @csrf
                                        @method('put')
                                        <div class="modal-body">
                                            <div class="row g-2">
                                                <div class="col-8">
                                                    <div class="form-floating">
                                                        <input required name="quantidade" type="number"
                                                            class="form-control" min="0.01" step="0.01"
                                                            id="floatingInputGrid" placeholder="quantidade">
                                                        <label for="floatingInputGrid">Quantidade</label>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-floating">
                                                        <select name="tipo_unificado" class="form-select"
                                                            id="floatingSelectGrid"
                                                            aria-label="Floating label select example">
                                                            <option value="un">UN</option>
                                                            <option value="cx">CX</option>
                                                        </select>
                                                        <label for="floatingSelectGrid">Tipo</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <br>

                                            <div class="row g-2">
                                                <div class="col-8">
                                                    <div class="form-floating">
                                                        <input required name="qtd_unificado" type="number"
                                                            class="form-control" min="0.01" step="0.01"
                                                            id="floatingInputGrid" placeholder="Desconto Geral">
                                                        <label for="floatingInputGrid">Desconto</label>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-floating">
                                                        <select name="tipo_unificado" class="form-select"
                                                            id="floatingSelectGrid"
                                                            aria-label="Floating label select example">
                                                            <option value="Porcentagem">%</option>
                                                            <option value="Dinheiro">R$</option>
                                                        </select>
                                                        <label for="floatingSelectGrid">Tipo</label>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-danger mx-5">Deletar Produto</button>
                                            <button type="submit" class="btn btn-primary">Confirmar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- fim modal editar --}}
                @endforeach
            </div>
        @endif
    @endif
    {{-- Fim tabela de itens --}}
@endsection
