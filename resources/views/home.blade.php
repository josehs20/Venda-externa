@extends('layouts.app')

@section('content')

    @include('componentes.navbar')

    @if (Session::has('message'))
        <div class="alert alert-success">
            <h5 style=""> {{ Session::get('message') }}</h5>
        </div>
    @endif
    @if (Session::has('error'))
        <div class="alert alert-danger">
            <h5 style=""> {{ Session::get('error') }}</h5>
        </div>
    @endif
    <div class="container">

        @foreach ($produtos as $produto)
            <a style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#venda{{ $produto->id }}">
                <ul class="list-group">
                    <li class="list-group-item active">
                        <div class="listCar">
                            <h6>{{ $produto->nome }}</h6>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="listCar">
                            <h6> Preço :</h6>
                            <h4>R$ {{ reais($produto->preco) }}</h4>
                        </div>
                    </li>
                </ul>
            </a>
            {{-- Modal --}}
            <div class="modal fade" id="venda{{ $produto->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ $produto->nome }} <br> Preço Venda:
                                <b>R${{ reais($produto->preco) }}</b></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form action="{{ route('carrinho', ['produto_id' => $produto->id]) }}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="row g-2">
                                    <div class="col-8">
                                        <div class="form-floating">
                                            <input required class="form-control" type="number" placeholder="QUANTIDADE"
                                                name="quantidade" min="0.01" step="0.01">
                                            <label for="floatingInputGrid">Quantiadade</label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-floating">
                                            <select name="tipo_unificado" class="form-select" id="floatingSelectGrid"
                                                aria-label="Floating label select example">
                                                <option value="un">UN</option>
                                                <option value="cx">CX</option>
                                            </select>
                                            <label for="floatingSelectGrid">Tipo</label>
                                        </div>
                                    </div>
                                </div>
                                <br><br>
                                {{-- --------------------------DESCONTO---------------------------- --}}
                                <div class="row g-2">
                                    @if ($count_item)

                                        @foreach ($count_item->carItem as $item)
                                            @if ($item->produto_id == $produto->id && $item->qtd_desconto > 0)
                                                <label for="">Esse Item Já Contém desconto Individual
                                                    de
                                                    <b>{{ $item->tipo_desconto == 'Porcentagem' ? '%' . $item->qtd_desconto : "R$" . $item->qtd_desconto }}</b>
                                                    para sua quantidade, Caso
                                                    insira um novo valor o mesmo será alterado!!</label>
                                            @endif
                                        @endforeach
                                    @endif
                                    <div class="col-8">
                                        <div class="form-floating">
                                            <input class="form-control" type="number"
                                                placeholder="DESCONTO AO PRODUTO ?" name="qtd_desconto" min="0.01"
                                                step="0.01">
                                            <label for="floatingInputGrid">Desconto</label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-floating">
                                            <select class="form-select" name="desc_tipo">
                                                <option value="Porcentagem"><b>
                                                        <h4> % </h4>
                                                </option>
                                                <option value="Dinheiro"><b>
                                                        <h4> $ </h4>
                                                </option>
                                            </select>
                                            <label for="floatingSelectGrid">Tipo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Adicionar ao carrinho</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- paginação --}}
    <nav aria-label="Navegação de página exemplo">
        <ul class="pagination">
            <li class="page-item">
                <a class="page-link" href="{{ $produtos->previousPageUrl() }}" aria-label="Anterior">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Anterior</span>
                </a>
            </li>

            @for ($i = 1; $i <= 3; $i++)
                <!-- a Tag for another page -->
                <li class="page-item"><a class="page-link"
                        href="{{ $produtos->url($i) }}">{{ $i }}</a></li>
            @endfor

            <li class="page-item">
                <a class="page-link" href="{{ $produtos->nextPageUrl() }}" aria-label="Próximo">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Próximo</span>
                </a>
            </li>
        </ul>
    </nav>

@endsection
