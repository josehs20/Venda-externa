@extends('layouts.app')

@section('content')

@foreach ($produtos as $produto)
<div class="card">
  <div class="card-body">
    {{$produto->nome}} <br>
    {{$produto->preco}}
  </div>
</div>
@endforeach

{{--paginação--}}
<nav aria-label="Navegação de página exemplo">
  <ul class="pagination">
    <li class="page-item">
      <a class="page-link" href="{{$produtos->previousPageUrl()}}" aria-label="Anterior">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Anterior</span>
      </a>
    </li>

    @for($i=1;$i<=3;$i++)

      <!-- a Tag for another page -->
      <li class="page-item"><a class="page-link" href="{{$produtos->url($i)}}">{{$i}}</a></li>

      @endfor

      <li class="page-item">
        <a class="page-link" href="{{$produtos->nextPageUrl()}}" aria-label="Próximo">
          <span aria-hidden="true">&raquo;</span>
          <span class="sr-only">Próximo</span>
        </a>
      </li>
  </ul>
</nav>


@endsection