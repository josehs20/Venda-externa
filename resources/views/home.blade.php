@extends('layouts.app')
<meta name="csrf_token" content="{{ csrf_token() }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
@section('content')
    <style>
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        .cardStyle {
            border-top: 5px solid blue;
        }

        .carFlutuante {
            position: fixed;
            right: 45px;
            bottom: 20px;
            z-index: 99;
        }

        */
    </style>




    @if (Session::has('success'))

        <body onload="msgSuccess('<?php echo Session::get('success'); ?>')">
    @endif
    @if (Session::has('solicitacaoDesconto'))

        <body onload="solicitacaoDesconto('<?php echo Session::get('solicitacaoDesconto'); ?>')">
    @endif

    @include('componentes.navbar', ['titulo' => 'Produtos'])


    <div class="col-md-12" style="border: 1px solid red; margin-top:110px;">
        <div class="row mt-3 d-flex justify-content-center">

            @foreach ($estoques as $e)
                <div onclick="modalAddProduto('<?php echo $e->produto->nome; ?>', 
                '<?php echo $e->i_grade && $e->i_grade->tam ? reais($e->produto->preco + $e->produto->preco * ($e->i_grade->fator / 100)) : reais($e->produto->preco); ?>',
                 '<?php echo $e->i_grade && $e->i_grade->tam ? $e->i_grade->tam : ''; ?>',
                  '<?php echo $e->id; ?>', '<?php echo array_key_exists($e->id, $item_carrinho) ? $item_carrinho[$e->id]['quantidade'] : null; ?>', 
                  '<?php echo $e->produto->un; ?>'
                  )" style="cursor: pointer"
                    class="card col-md-5 mb-3 m-2 cardStyle">
                    <div
                        class="card-header bg-transparent border-primary card-title d-flex justify-content-between align-items-center">
                        <h5 class="card-title text-primary">{{ $e->produto->nome }}</h5>
                        <h5 class="card-title text-info">{{ $e->i_grade ? $e->i_grade->tam : '' }}</h5>
                    </div>
                    <div class="card-body text">
                        <div class="d-flex justify-content-between align-items-center">
                            {{-- ------------------ Verifica se sem fator de acrescimo de preco---------------------------------- --}}
                            @if ($e->i_grade && $e->i_grade->tam)
                                <p class="card-text m-0"><b>R$:
                                        {{ reais($e->produto->preco + $e->produto->preco * ($e->i_grade->fator / 100)) }}</b>
                                </p>
                            @else
                                <p class="card-text m-0"><b>R$: {{ reais($e->produto->preco) }}</b></p>
                            @endif

                            <span class="badge bg-{{ $e->saldo <= 0 ? 'danger' : 'primary' }} rounded-pill">Saldo
                                :{{ $e->saldo }}</span>

                        </div>
                    </div>
                    {{-- <div class="card-footer bg-transparent border-primary">Footer</div> --}}
                </div>
            @endforeach
        </div>
    </div>
   
    <div id="paginate">

        {{ $estoques->withQueryString()->links() }}
    </div>
    <!--botao whatsapp -->
    <a onclick="carrinhoFixedHome()" style="padding: 10px; !important; display: block !important""
        class="btn btn-lg btn-primary carFlutuante"> <i style="color: white;" class="fas fa-shopping-cart"></i>

    </a>
@endsection
<script type="text/javascript" src="{{ asset('js/viewhome.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('js/paginacao.js') }}" defer></script>
