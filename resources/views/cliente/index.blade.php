@extends('layouts.app')

{{-- Remove contador input type number --}}
<style>
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }

    #addContato {
        position: fixed;
        bottom: 60px;
        z-index: 9999;
        opacity: 0.7;
        position: fixed;
        right: 0;
        background-color: #0d6efd;
    }

    #buscaCliente {
        position: fixed;
        bottom: 100px;
        z-index: 9999;
        opacity: 0.7;
        position: fixed;
        right: 0;
        background-color: #b87518;
    }

</style>
@section('content')

    @include('componentes.navbar')
    @include('componentes.titulo', [
        'titlePage' => 'Clientes',
    ])

    <a id="addContato" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ModaladdContato"><i
            class="bi bi-person-plus"></i></a>
    <a id="buscaCliente" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBuscaCliente"><i
            class="bi-search"></i></a>

    @if (Session::has('clienteAddObs'))

            <body onload="msgContato(msg = 2)">
            @elseif(Session::has('deleta_cliente'))

                <body onload="msgContato(msg = 5)">
                @elseif(Session::has('updateCliente'))

                    <body onload="msgContato(msg = 6)">
    @endif

    {{-- Modal search --}}
    <form action="{{ route('clientes.index', auth()->user()->id) }} " method="GET">
        @csrf
        <div class="modal fade" id="modalBuscaCliente" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Buscar Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <input required name="nome" type="search" class="form-control search col-12"
                            placeholder="Buscar Cliente">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sair</button>
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @if ($clientes->count())
        <div class="listCliente">
            <div class="container">

                @foreach ($clientes as $cliente)
                    <ul class="list-group mt-3">
                        <button class="collapsible"
                            style="background-color: rgb(58, 36, 252); font-size:16px;border-radius:7px;cursor: pointer;white-space: nowrap; overflow: hidden">
                            <div style="width:50%;">
                                <h6 style="margin-top: -10px; margin-left:20px;"> {{ $cliente->nome }}</h6>
                            </div>
                        </button>
                        <div class="contentconspllan" style="border-radius:7px;margin-top:-1px;">
                            <div class="list-group">
                                <ul class="list-group-item list-group-item-action flex-column align-items-start">
                                    <li class="list-group-item">
                                        <a style="font: italic bold 15px monospace">
                                            {{ $cliente->tipo == 'J' ? 'CNPJ : ' : 'CPF : ' }}</a>
                                        &nbsp;&nbsp;{{ $cliente->docto ? $cliente->docto : 'Não Informado' }}

                                    </li>

                                    <li class="list-group-item">
                                        <a style="font: italic bold 15px monospace"> Cidade : </a>
                                        &nbsp;&nbsp;{{ $cliente->enderecos->cidadeIbge ? $cliente->enderecos->cidadeIbge->nome : 'Não Informado' }}&nbsp;&nbsp;{{ $cliente->enderecos ? '| UF : ' . $cliente->enderecos->cidadeIbge->uf : 'UF: Não Informado' }}

                                    </li>
                                    <li class="list-group-item">
                                        <a style="font: italic bold 15px monospace"> Bairro : </a>
                                        &nbsp;&nbsp;{{ $cliente->enderecos ? $cliente->enderecos->bairro : 'Não Informado' }}

                                    </li>
                                    <li class="list-group-item">
                                        <a style="font: italic bold 15px monospace"> Rua : </a>
                                        &nbsp;&nbsp;{{ $cliente->enderecos ? $cliente->enderecos->rua : 'Não Informado' }}
                                        &nbsp;&nbsp;{{ $cliente->enderecos->numero ? ' | Nº : ' . $cliente->enderecos->numero : '| Nº : SN' }}

                                    </li>
                                    <li class="list-group-item">
                                        <a style="font: italic bold 15px monospace"> Complemento : </a>
                    
                                        &nbsp;&nbsp;{{ $cliente->enderecos ? $cliente->enderecos->compto : 'Não Informado' }}

                                    </li>
                                    <li class="list-group-item">
                                        <a style="font: italic bold 15px monospace"> E-mail : </a>&nbsp;&nbsp;
                                        {{ $cliente->email ? $cliente->email : 'Não Informado' }}

                                        <span data-bs-toggle="modal" data-bs-target="#contato{{ $cliente->id }}"
                                            style="float: right" class="badge bg-secondary"><i data-bs-toggle="modal"
                                                data-bs-target="#contato{{ $cliente->id }}"
                                                class="bi bi-telephone"></i></span>

                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="{{route('clientes.edit', $cliente->id)}}" style="cursor: pointer;" class="badge bg-primary rounded-pill">Editar
                                            Cliente</a>

                                        <span style="cursor: pointer;" class="badge bg-primary rounded-pill"
                                            data-bs-toggle="modal" data-bs-target="#addObs{{ $cliente->id }}">Adicionar
                                            Observação</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Modal Contato Cliente-->
                            <div class="modal fade" id="contato{{ $cliente->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">{{ $cliente->nome }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>

                                        </div>
                                        <div class="modal-body">
                                            <div class="row mx-3"><b> Telefones</b></div>
                                            <div class="list-group">
                                                <ul class="flex-column align-items-start me-5">
                                                    <li class="list-group-item">
                                                        <a style="font: italic bold 15px monospace">
                                                            Telefone 1 :</a>
                                                        &nbsp;&nbsp;{{ $cliente->fone1 ? $cliente->fone1 : 'Não Informado' }}

                                                    </li>
                                                    <li class="list-group-item">
                                                        <a style="font: italic bold 15px monospace">
                                                            Telefone 2 :</a>
                                                        &nbsp;&nbsp;{{ $cliente->fone2 ? $cliente->fone2 : 'Não Informado' }}

                                                    </li>
                                                    <li class="list-group-item">
                                                        <a style="font: italic bold 15px monospace">
                                                            Celular : </a>
                                                        &nbsp;&nbsp;{{ $cliente->celular ? $cliente->celular : 'Não Informado' }}

                                                    </li>
                                                </ul>
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Sair</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Adiciona Observação --}}

                            <div class="modal fade" id="addObs{{ $cliente->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="false">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Adicionar Observação</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('vendedor.cliente.store', $cliente->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">

                                                <div class="mb-3">
                                                    <label for="recipient-name" class="col-form-label">Adicionar uma
                                                        Data:</label>
                                                    <input type="date" name="data_obs" class="form-control">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="exampleFormControlTextarea1"
                                                        class="form-label">Observação</label>
                                                    <textarea required id="add_obs" class="form-control" name="observacao" rows="3" maxlength="255"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Sair</button>
                                                <button type="submit" class="btn btn-primary js-add">Salvar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            @php
                                $i = 1;
                            @endphp

                            @foreach ($cliente->infoCliente as $info)
                                <form name="csrf-token" action="{{ route('deleta_obs', $info->id) }}" method="post">
                                    <meta name="csrf-token" content="{{ csrf_token() }}">
                                    @csrf
                                    @method('DELETE')

                                    <div class="list-group" id="<?php echo $info->id; ?>">
                                        <a style="background-color: rgb(172, 172, 172)"
                                            class="list-group-item list-group-item-action flex-column align-items-start">
                                            {{ $i++ }}º&emsp;Observaçao: <h6 style="word-break:break-all;">
                                            </h6>
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 style="word-break:break-all;">{{ $info->observacao }}
                                                </h6> <br>

                                            </div>

                                            <small class="text-muted"><b>Data:
                                                </b>{{ $info->data ? $info->data : 'Não informado ' }}</small>
                                            <button
                                                style="float: right; border:none!important;
                                                                                                                                                                background-color: rgb(172, 172, 172); "
                                                type="submit" class="js-del"
                                                onclick="botaoInfo(<?php echo $info->id; ?>)">

                                                <i class="bi bi-x-square"></i>
                                            </button>
                                        </a>
                                    </div>


                                </form>
                            @endforeach
                    </ul>
                @endforeach
            </div>
        </div>
    @else
        <div class="alert alert-warning" style="margin-top: 100px;" role="alert">
            Nenhum Cliente Cadastrado !
        </div>
    @endif

    {{-- evento conspllan --}}
    <script>
        var coll = document.getElementsByClassName("collapsible");
        var i;

        for (i = 0; i < coll.length; i++) {
            coll[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var content = this.nextElementSibling;
                if (content.style.maxHeight) {
                    content.style.maxHeight = null;
                } else {
                    content.style.maxHeight = content.scrollHeight + "px";
                }
            });
        }
    </script>
@endsection
<script type="text/javascript" src="{{ asset('js/viewcliente.js') }}" defer></script>
