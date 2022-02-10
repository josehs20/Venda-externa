@extends('layouts.app')

@section('content')

    @include('componentes.navbar')
    {{-- Modal Desconto Unificado --}}

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Unificar Desconto</h5><br>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
              
                    <form method="post" action="{{ route('unifica_valor_Itens', ['itensCarr' => $itens]) }}">
                        @csrf
                        @method('put')
                <div class="modal-body">
                        <div class="row g-2">
                            <div class="col-8">
                                <div class="form-floating">
                                    <input required name="qtd_unificado" type="number" class="form-control" min="0.01" step="0.01"
                                        id="floatingInputGrid" placeholder="Desconto Geral">
                                    <label for="floatingInputGrid">Quantidade</label>
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
    <div class="container">
        <div class="row d-flex justify-content-center">

            <div class="col-lg-5 col-sm-6">
                <div class="card-box bg-green">
                    <div class="inner">
                        <h6><i class="bi bi-cash-coin"> Sem Desconto:&ensp;R$<u>{{ reais($valor_itens_total->total) }}</u></i></h6>

                    </div>
                    <div class="inner">
                        <h6><i class="bi bi-cash-coin"> Com Desconto:&ensp;R$<u>{{ $valor_itens_desconto }}</u></i></h6>
                    </div>
                    <div class="icon">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <a href="#" class="card-box-footer">Valor Total<i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-5 col-sm-6">
                <div class="card-box bg-orange">
                    <div class="inner">
                        <i class="bi bi-cash-stack">&ensp;{{ $total_desconto_valor }} </i>
                    </div>
                    <div class="inner">
                        <p>&ensp;{{ ($tp_desconto == 'Porcentagem_unificado') ? "Unificado em % $itens->desconto_qtd" : (($tp_desconto == 'Dinheiro_unificado') ? "Unificado em R$ $itens->desconto_qtd" : 'Não unificado') }} </p>
                    </div>
                   
                    <div class="icon">
                        <i class="bi bi-percent"></i>

                    </div>
                    <a href="#" class="card-box-footer">Total De Desconto<i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
    {{-- Fim cards iniciais --}}
    <div class="row d-flex justify-content-center">
        <button type="button" class="btn btn-primary col-4 mx-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
            Unificar Desconto
        </button>

        <a href="{{ route('itens_carrinho')}}" type="button" class="btn btn-danger col-4 mx-3">
            Reverter Unificação
        </a>
    </div>
    <hr><br>
    {{-- tabela de itens --}}
    @if ($itens)
        <div class="container">
            @foreach ($itens->carItem as $item)
                <ul class="list-group">
                    <li class="list-group-item active">
                        <div class="listCar">
                            <p> {{ $item->nome }} </p>
                            <h5>Valor R$ {{ reais($item->valor) }}</h5>
                        </div>
                    </li>

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
            @endforeach
        </div>
    @else
        <div class="alert alert-warning" role="alert">
            Adicione Produtos No Seu Carrinho De Vendas :)
        </div>
    @endif

    {{-- Fim tabela de itens --}}
@endsection
