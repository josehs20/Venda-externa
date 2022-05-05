@extends('layouts.app')

@section('content')
  
   

        @include('componentes.navbar')
        <br><br><br><br><br>
        <div class="container" >
            <div class="card border-primary mb-3 mx-3">
                <div class="card-header d-flex d-flex justify-content-center">
                    <h5>Cadastro de Cliente</h5>
                </div>

                <form id="CadastroCliente" class="row g-3 d-flex justify-content-center px-2">
                    @csrf

                    @include('cliente.inc.form', ['cliente' => ''])

                </form>
            </div>
        </div>
    
@endsection
<script type="text/javascript" src="{{ asset('js/valida-documento.js') }}" defer></script>
