@extends('layouts.app')

@section('content')

    @include('componentes.navbar')

    @if (Session::has('message'))
        <div class="alert alert-success">
            <h5 style=""> {{ Session::get('message') }}</h5>
        </div>
    @endif

    <div class="container">

        @foreach ($produtos as $produto)
            <a style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#venda{{ $produto->id }}">
                <ul class="list-group">
                    <li class="list-group-item active">
                        <div class="listCar">
                            <h6>{{$produto->nome}}</h6>
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
          <br>
            {{-- Modal --}}
            <div class="modal fade" id="venda{{ $produto->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{ $produto->nome }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            
                            <p>Preço Venda: <b>{{ reais($produto->preco) }}</b></p>
                        
                            <form action="{{ route('carrinho', ['produto_id' => $produto->id]) }}" method="post">
                                @csrf

                                <div class="form-row my-5">

                                    <input required class="col-8 mx-2" type="number" placeholder="QUANTIDADE"
                                        name="quantidade" min="0.01" step="0.01">

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
                                    <hr>
                                    <input class="col-8 mx-2" type="number" placeholder="DESCONTO AO PRODUTO ?"
                                        name="qtd_desconto" min="0.01" step="0.01">
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
