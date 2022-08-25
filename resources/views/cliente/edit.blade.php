@extends('layouts.app')

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

        #uf {
            text-transform: uppercase;
        }

    </style>

    @include('componentes.navbar', ['titulo' => 'Editar'])
    <br><br><br><br><br>
    <div class="container">
        <div class="card border-primary mb-3 mx-3">
            <div class="card-header d-flex d-flex justify-content-center">
                <h5>Editar Cliente</h5>
            </div>

            <form id="updateCliente" class="row g-3 d-flex justify-content-center px-2">
                @csrf

                @include('cliente.inc.form', ['cliente' => $cliente])

            </form>
        </div>
    </div>
    @endsection
    <script src="{{ asset('js/carrinho.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('js/valida-documento.js') }}" defer></script>
