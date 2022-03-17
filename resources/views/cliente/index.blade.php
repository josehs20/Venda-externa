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
    <a id="buscaCliente" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCuscaCliente"><i
            class="bi-search"></i></a>

    @if (Session::has('message'))

        <body onload="msgContato(msg = 1)">
        @elseif(Session::has('clienteadd'))

            <body onload="msgContato(msg = 2)">
            @elseif(Session::has('deleta_cliente'))

                <body onload="msgContato(msg = 5)">
                @elseif(Session::has('updateCliente'))

                    <body onload="msgContato(msg = 6)">
    @endif

    {{-- Modal search --}}
    <form action="{{ route('vendedor.cliente.index', auth()->user()->id) }} " method="GET">
        @csrf
        <div class="modal fade" id="modalCuscaCliente" tabindex="-1" aria-labelledby="exampleModalLabel"
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
    {{-- Modal Cadastro usuario --}}
    <div class="modal fade" id="ModaladdContato" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Adicionar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="margin-top: -20px;">
                    <form action="{{ route('vendedor.cliente.create', auth()->user()->id) }}" method="GET">
                        @csrf
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Nome:</label>
                            <input type="text" required name="nome" class="form-control" id="recipient-name">
                        </div>
                        <div class="mb-3" style="margin-top: -20px;">
                            <label for="recipient-name" class="col-form-label">email:</label>
                            <input type="email" name="email" class="form-control" id="recipient-name">
                        </div>
                        <div class="mb-3" style="margin-top: -20px;">
                            <label for="message-text" class="col-form-label">Telefone:</label>
                            <input type="number" name="telefone" class="form-control" id="message-text">
                        </div>
                        <div class="mb-3" style="margin-top: -20px;">
                            <label for="recipient-name" class="col-form-label">Cidade:</label>
                            <input type="text" name="cidade" class="form-control" id="recipient-name">
                        </div>
                        <div class="row" style="margin-top: -20px;">
                            <div class="mb-3 col-8">
                                <label for="recipient-name" class="col-form-label">Rua:</label>
                                <input type="text" name="rua" class="form-control" id="recipient-name">
                            </div>
                            <div class="mb-3 col-4">
                                <label for="message-text" class="col-form-label">Nº:</label>
                                <input type="number" name="numero_rua" class="form-control" id="message-text">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Observação</label>
                            <textarea class="form-control" name="observacao" id="exampleFormControlTextarea1" rows="2"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sair</button>
                    <button onclick="msgContato()" type="submit" class="btn btn-primary">Salvar</button>
                </div>
                </form>
            </div>
        </div>
    </div>


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
                                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                                    <div class="" style="word-break:break-all;">
                                        <small class="text-muted"><b> E-mail:</b>
                                            {{ $cliente->email ? $cliente->email : 'Não Informado' }}</small><br>

                                        <small class="text-muted"><b> Telefone 1:
                                            </b>{{ $cliente->fone1 ? $cliente->fone1 : 'Não Informado' }}
                                            {{-- <i
                                                class="bi bi-pencil-square" style="float: right" data-bs-toggle="modal"
                                                data-bs-target="#editarCliente{{ $cliente->id }}"></i> --}}</small>
                                        <br>
                                        <small class="text-muted"><b> Telefone 2:
                                            </b>{{ $cliente->fone2 ? $cliente->fone2 : 'Não Informado' }}</small>
                                        <br>

                                        <div class="d-flex w-100 justify-content-between">
                                            <small class="text-muted"><b> Celular:
                                                </b>{{ $cliente->celular ? $cliente->celular : 'Não Informado' }}</small><br>

                                            {{-- <small class="text-muted" style="float: right;" data-bs-toggle="modal"
                                        data-bs-target="#addObs{{ $cliente->id }}"><i
                                            class="bi bi-plus-square"></i>} --}}

                                            </small>

                                        </div>
                                        <small class="text-muted" style="float: right;" data-bs-toggle="modal"
                                            data-bs-target="#addObs{{ $cliente->id }}"><i
                                                class="bi bi-plus-square"></i></small>
                                        {{-- <small class="text-muted" style="float: right;" data-bs-toggle="modal"
                                            data-bs-target="#deletaCliente{{ $cliente->id }}"><i
                                                class="bi bi-x-square"></i> --}}

                                    </div>

                                </a>
                            </div>

                            {{-- Modal deleta Cliente --}}
                            <div class="modal fade" id="deletaCliente{{ $cliente->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Deseja excluir este cliente ?
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="list-group">


                                                <div class="row" style="word-break:break-all;">
                                                    <small class="text-muted"><b> E-mail:</b>
                                                        {{ $cliente->email ? $cliente->email : 'Não Informado' }}</small><br>
                                                    <small class="text-muted"><b> Telefone:
                                                        </b>{{ $cliente->fone1 ? $cliente->fone1 : 'Não Informado' }}</small><br>

                                                    <div class="d-flex w-100 justify-content-between">
                                                        <small class="text-muted"><b> Cidade:
                                                            </b>{{ $cliente->cidade ? $cliente->cidade : 'Não Informado' }}</small><br>

                                                        </small>

                                                    </div>

                                                    <small class="text-muted"><b> Rua:
                                                        </b>{{ $cliente->rua ? $cliente->rua : 'Não Informado' }}
                                                        &nbsp;&nbsp; &nbsp;
                                                        <b>Nº:</b>{{ $cliente->numero_rua ? $cliente->numero_rua : 'S/N' }}</small>

                                                </div>


                                            </div>
                                            <br>
                                            <span style="font-size: 12px;">Ao Excluir este cliente os Itens salvos também
                                                serão excluídos!!</span>
                                        </div>
                                        <form
                                            action="{{ route('vendedor.cliente.destroy', ['vendedor' => auth()->user()->id, 'cliente' => $cliente->id]) }}"
                                            method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <input type="hidden" value="1" name="verify">
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Sair</button>
                                                <button type="submit" class="btn btn-primary">Excluir</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            {{-- Fim Modal Deleta Cliente --}}

                            {{-- Modal Edita usuario Cliente --}}
                            <div class="modal fade" id="editarCliente{{ $cliente->id }}" tabindex="-1"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Editar Cliente</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form
                                            action="{{ route('vendedor.cliente.update', ['vendedor' => auth()->user()->id, 'cliente' => $cliente->id]) }}"
                                            method="POST">
                                            @method('PUT')
                                            @csrf
                                            <div class="modal-body" style="margin-top: -20px;">

                                                <div class="mb-3">
                                                    <label for="recipient-name" class="col-form-label">Nome:</label>
                                                    <input type="text" required name="nome" class="form-control"
                                                        id="recipient-name" value="{{ $cliente->nome }}">
                                                </div>
                                                <div class="mb-3" style="margin-top: -20px;">
                                                    <label for="recipient-name" class="col-form-label">email:</label>
                                                    <input type="email" name="email" class="form-control"
                                                        id="recipient-name" value="{{ $cliente->email }}">
                                                </div>
                                                <div class="mb-3" style="margin-top: -20px;">
                                                    <label for="message-text" class="col-form-label">Telefone:</label>
                                                    <input type="number" name="telefone" class="form-control"
                                                        id="message-text" value="{{ $cliente->telefone }}">
                                                </div>
                                                <div class="mb-3" style="margin-top: -20px;">
                                                    <label for="recipient-name" class="col-form-label">Cidade:</label>
                                                    <input type="text" name="cidade" class="form-control"
                                                        id="recipient-name" value="{{ $cliente->cidade }}">
                                                </div>
                                                <div class="row" style="margin-top: -20px;">
                                                    <div class="mb-3 col-8">
                                                        <label for="recipient-name" class="col-form-label">Rua:</label>
                                                        <input type="text" name="rua" class="form-control"
                                                            id="recipient-name" value="{{ $cliente->rua }}">
                                                    </div>
                                                    <div class="mb-3 col-4">
                                                        <label for="message-text" class="col-form-label">Nº:</label>
                                                        <input type="number" name="numero_rua" class="form-control"
                                                            id="message-text" value="{{ $cliente->numero_rua }}">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Sair</button>
                                                <button onclick="msgContato()" type="submit"
                                                    class="btn btn-primary">Editar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            {{-- Fim Modal Edita usuario Cliente --}}


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
