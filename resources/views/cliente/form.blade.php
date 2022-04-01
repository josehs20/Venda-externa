@extends('layouts.app')


@livewireStyles
@section('content')
    @include('componentes.navbar')
    @include('componentes.titulo', [
        'titlePage' => 'Cadastrar Cliente',
    ])
    

    @livewire('cep')
    
@endsection
<script type="text/javascript" src="{{ asset('js/valida-documento.js') }}" defer></script>

@livewireScripts
