@extends('layouts.app')
<meta name="csrf_token" content="{{ csrf_token() }}" />
@section('content')
    @include('componentes.navbar')
    @include('componentes.titulo', ['titlePage' => 'Produtos'])
    @if (Session::has('cancelar_carrinho'))

        <body onload="msgContato(msg = 7)">
    @endif
    <form id="elemento_ajax_html" name="addItem" method="POST" class="list">
        @csrf
        @foreach ($produtos as $produto)
            <a class="listHome" style="cursor: pointer">
                <ul class="list-group">
                    <li class="list-group-item" style="background-color: rgb(58, 36, 252)">
                        <div class="listCar">
                            <h6 style="color: white">{{ $produto->nome }}</h6>
                            <button type="submit" onclick="cli(<?php echo $produto->id; ?>)" class="buttonAdd"><img id="imgg"
                                    class="imgCarr" src="addCar.ico" alt=""></button>
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

            {{-- Fim Desconto Modal --}}
        @endforeach
    </form>


    {{-- paginação --}}
    <nav class="navegacao" aria-label="Navegação">
        <ul class="pagination" style="justify-content: center;">
            <li class="page-item">
                <a class="page-link" href="{{ $produtos->previousPageUrl() }}" aria-label="Anterior">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">voltar</span>
                </a>
            </li>

            @for ($i = 1; $i <= 6; $i++)
                <!-- a Tag for another page -->
                <li class="page-item"><a class="page-link"
                       
                        href="{{ $produtos->url($i) }}">{{ $i }}</a></li>
            @endfor

            <li class="page-item">
                <a class="page-link" href="{{$produtos->url($produtos->lastPage())}}" aria-label="Próximo">
                    <span aria-hidden="true">... {{$produtos->lastPage()}}</span>
                   
                </a>
            </li>
        </ul>
    </nav>
@endsection
<script type="text/javascript" src="{{ asset('js/viewhome.js') }}" defer></script>
