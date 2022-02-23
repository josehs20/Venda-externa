@extends('layouts.app')

@section('content')
@include('componentes.navbar')
    @include('componentes.titulo', ['titlePage' => 'Carrinho'])
    {{-- Modal Desconto Unificado --}}
    @if (Session::has('message'))
        <div class="alert alert-success">
            <h5 style=""> {{ Session::get('message') }}</h5>
        </div>
    @endif
    @if (!$itens)
        <div class="alert alert-warning" role="alert">
            Adicione Produtos No Seu Carrinho De Vendas :)
        </div>
    @else

        <!-- Modal Unifica -->
        <div class="modal fade" id="modalUnifica" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
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
                            <button type="submit" class="btn btn-primary">Confirmar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Fim Modal Unificado --}}
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
                Unificar Desconto
            </button>

            <a href="{{ route('itens_carrinho') }}" type="button" class="btn btn-danger col-5 mx-2">
                Reverter Unificação
            </a>
        </div>
        <div class="row d-flex justify-content-center" style="margin-top: 10px;">
            <button type="button" class="btn btn-warning col-5 mx-2" data-bs-toggle="modal" data-bs-target="#modalUnifica">
                Finalizar Venda
            </button>

            <a href="{{ route('itens_carrinho') }}" type="button" class="btn btn-info col-5 mx-2">
                Salvar.
            </a>
        </div>
        <hr><br>
        {{-- tabela de itens --}}
        @if ($itens)
            <div class="container">
                @foreach ($itens->carItem as $item)
                    <ul class="list-group">
                        <a style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#editaItem{{ $item->id }}">
                            <li class="list-group-item" style="background-color: rgb(58, 36, 252)">
                                <div style="color: white;" class="listCar">
                                    <p> {{ $item->nome }} </p>
                                    <h5>Valor R$ {{ reais($item->preco * $item->quantidade) }}</h5>
                                </div>
                            </li>
                        </a>
                        <li class="list-group-item">
                            <div class="listCar">
                                <p> Preço </p>
                                <span>{{ reais($item->preco) }}</span>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="listCar">
                                <p> Quantidade </p>
                                <span>{{ $item->quantidade }}</span>
                            </div>
                        </li>

                        <li class="list-group-item">
                            <div class="listCar">
                                <p> Desconto </p>
                                @if ($tp_desconto)
                                    <span>Desconto Unico</span>
                                @else

                                    <span>{{ !$item->qtd_desconto? 'Não inserido': ($item->tipo_desconto == 'Porcentagem'? '%' . $item->qtd_desconto: "R$" . $item->qtd_desconto) }}</span>
                                @endif
                            </div>
                        </li>
                    </ul>
                    <br>

                    {{-- Modal carrinho item editar --}}
                    <div class="modal fade" id="editaItem{{ $item->id }}" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" style="margin-left: 35%;" id="exampleModalLabel">Editar Item
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
                                <label class="mx-2" for=""> Preco: <b>R$ {{ reais($item->preco) }}</b></label>
                                <hr>
                                <form method="post"
                                    action="{{ route('unifica_valor_Itens', ['itensCarr' => $itens ? $itens : null]) }}">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body">
                                        <div class="row g-2">
                                            <div class="col-8">
                                                <div class="form-floating">
                                                    <input required name="quantidade" type="number" class="form-control"
                                                        min="0.01" step="0.01" id="floatingInputGrid"
                                                        placeholder="quantidade">
                                                    <label for="floatingInputGrid">Quantidade</label>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-floating">
                                                    <select name="tipo_unificado" class="form-select"
                                                        id="floatingSelectGrid" aria-label="Floating label select example">
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
                                                        class="form-control" min="0.01" step="0.01" id="floatingInputGrid"
                                                        placeholder="Desconto Geral">
                                                    <label for="floatingInputGrid">Desconto</label>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="form-floating">
                                                    <select name="tipo_unificado" class="form-select"
                                                        id="floatingSelectGrid" aria-label="Floating label select example">
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
