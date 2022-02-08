@extends('layouts.app')

@section('content')
    <div class="row col-12 mx-1">

        @include('componentes.navbar')
    </div>

    @if (Session::has('message'))
        <div class="alert alert-success">{{ Session::get('message') }}</div>
    @endif

    <div class="externa">
        @foreach ($produtos as $produto)
            <div class="card col-5 mx-2""""""""" my-2 d-inline-flex" style="background-color: rgb(96, 43, 241)">
                <a data-bs-toggle="modal" data-bs-target="#venda{{ $produto->id }}">

                    <div class="card-body">
                       <h6 style="color: white">{{ $produto->nome }}</h6> 
                       <h6 style="color: white"> R$ : {{ reais($produto->preco) }}</h6>
                    </div>
                </a>
            </div>

    {{-- Modal --}}
    <div class="modal fade" id="venda{{ $produto->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $produto->nome }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><b>Preço Venda: </b> {{ reais($produto->preco) }}</p>
                    <p><b>Estoque: </b> </p>

                    <form action="{{ route('carrinho', ['produto_id' => $produto->id]) }}" method="post">
                        @csrf

                        <div class="form-row my-5">

                            <input required class="col-8 mx-2" type="number" placeholder="QUANTIDADE" name="quantidade"
                                min="00.00" max="100" step="0.01">

                            <div>
                                <select name="qtd_tipo" class="custom-select mx-1" id="inlineFormCustomSelect">

                                    <option value="un"><b>
                                            <h4> UN </h4>
                                    </option>
                                    <option value="cx"><b>
                                            <h4> CAIXA </h4>
                                    </option>

                                </select>
                            </div>

                        </div>

                        <div class="form-row">

                            <input class="col-8 mx-2" type="number" placeholder="DESCONTO AO PRODUTO ?"
                                name="qtd_desconto" min="00.00" max="100" step="0.01">

                            <div>
                                <select name="desc_tipo" class="custom-select mx-1" id="inlineFormCustomSelect">

                                    <option value="Porcentagem"><b>
                                            <h4> % </h4>
                                    </option>
                                    <option value="Dinheiro"><b>
                                            <h4> $ </h4>
                                    </option>

                                </select>
                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Sair</button>
                            <button type="submit" class="btn btn-primary">Adicionar ao carrinho</button>
                        </div>
                    </form>


                </div>

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
