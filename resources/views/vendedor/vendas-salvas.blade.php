@extends('layouts.app')

@section('content')
    <style>
        .collapsible:after {
            display: none;
        }
    </style>

    @include('componentes.navbar', ['titulo' => 'Salvos'])
    <br><br><br><br><br>

    @if (Session::has('success'))

        <body onload="alertPadrao('<?php echo Session::get('success'); ?>', 'success')">
    @endif
    @if (Session::has('error'))

        <body onload="msgError('<?php echo Session::get('error'); ?>', 'error')">
    @endif

    <div style="margin: 2%;" class="listCliente">
        <form action="{{ route('vendas_salvas', auth()->user()->id) }}" class="position-relative col-md-6">
            <input name="nome_n_pedido" class="form-control" style=" border-radius: 15px;" placeholder="Buscar Venda Salva"
                type="search">
            <button class="btn position-absolute iconeSearchCliente">
                <i class="fa fa-search"></i>
            </button>
        </form>

        @if (!count($carrinhos))
            <div class="alert alert-warning mt-5" role="alert">
                Nenhuma venda salva encontrada!
            </div>
        @else
            <ul class="list-group" style="cursor: pointer;">
                @foreach ($carrinhos as $key_cliente => $carrinho)
                    @foreach ($carrinho as $c)
                        <li onclick='modalItensVendaSalva(<?php echo $c; ?>,<?php echo json_encode($produtos); ?> )'
                            style="background-color: #00a3ef; border-radius:10px;"
                            class="list-group-item d-flex justify-content-between align-items-center mt-3">
                            <a
                                style="color: white; cursor: pointer;">{{ $clientes[$c->cliente_id_alltech] ? $clientes[$c->cliente_id_alltech]->nome : 'Cliente não informado' }}</a>
                            <span class="badge rounded-pill">{{ $c->n_pedido }}</span>
                        </li>
                    @endforeach
                @endforeach
            </ul>
            <div class="d-flex justify-content-center mt-3" id="paginate">
                {{ $carrinhos->withQueryString()->links() }}
            </div>
        @endif
        <br>
    </div>

@endsection
<script type="text/javascript" src="{{ asset('js/vendaSalva.js') }}" defer></script>
<script src="{{ asset('js/carrinho.js') }}" defer></script>
{{-- <script type="text/javascript" src="{{ asset('js/viewcliente.js') }}" defer></script> --}}
