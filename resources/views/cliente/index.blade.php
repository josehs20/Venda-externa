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
    }

</style>
@section('content')
    @include('componentes.navbar')
    @include('componentes.titulo', ['titlePage' => 'Clientes'])
    <a id="addContato" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal"><i
            class="bi bi-person-plus"></i></a>

    @if (Session::has('message'))

        <body onload="msgContato(msg = 1)">
    @endif

    {{-- Modal Cadastro usuario --}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Adicionar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="margin-top: -20px;">
                    <form action="{{ route('vendedor.cliente.store', auth()->user()->id) }}" method="POST">
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
                                <input type="number" name="n_rua" class="form-control" id="message-text">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Observação</label>
                            <textarea class="form-control" name="observacao" id="exampleFormControlTextarea1"
                                rows="2"></textarea>
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

    {{-- Modal Adiciona Observação --}}
    <div class="modal fade" id="addObs" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Adicionar Observação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('adiciona_obs', auth()->user()->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Observação:</label>
                            <input type="text" required name="nome" class="form-control" id="recipient-name">
                        </div>
                        <div class="mb-3">
                            <label for="exampleFormControlTextarea1" class="form-label">Observação</label>
                            <textarea class="form-control" name="observacao" id="exampleFormControlTextarea1"
                                rows="3"></textarea>
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
                            style="background-color: rgb(58, 36, 252); font-size:16px;border-radius:7px;cursor: pointer;">
                            <h6 style="margin-top:-10px;">{{ $cliente->nome }}</h6>
                        </button>
                        <div class="contentconspllan" style="border-radius:7px;margin-top:-1px;">
                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                                    <div class="" style="word-break:break-all;">
                                        <small class="text-muted"><b> E-mail:</b>
                                            {{ $cliente->email ? $cliente->email : 'Não Informado' }}</small><br>
                                        <small class="text-muted"><b> Telefone:
                                            </b>{{ $cliente->telefone ? $cliente->telefone : 'Não Informado' }}</small><br>

                                        <div class="d-flex w-100 justify-content-between">
                                            <small class="text-muted"><b> Cidade:
                                                </b>{{ $cliente->cidade ? $cliente->cidade : 'Não Informado' }}</small><br>
                                            <small class="text-muted" style="float: right;" data-bs-toggle="modal"
                                                data-bs-target="#addObs"><i class="bi bi-plus-circle"></i></small>

                                        </div>

                                        <small class="text-muted"><b> Rua:
                                            </b>{{ $cliente->rua ? $cliente->rua : 'Não Informado' }} &nbsp;&nbsp; &nbsp;
                                            <b>Nº:</b>{{ $cliente->numero_rua ? $cliente->numero_rua : 'S/N' }}</small>


                                    </div>

                                </a>
                            </div>
                            @php
                                $i = 1;
                            @endphp

                            @foreach ($cliente->infoCliente as $info)
                                <form action="{{ route('deleta_obs', $info->id) }}" data-method="DELETE"
                                    data-confirm="Deseja realmente excluir esta empresa?">
                                    <form method="POST" action="{{ route('route.name', ['deleta_obs' => $info->id]) }}">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">

                                        <div class="list-group">
                                            <a style="background-color: rgb(172, 172, 172) "
                                                class="list-group-item list-group-item-action flex-column align-items-start">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 style="word-break:break-all;">{{ $i++ }}&emsp;Observaçao:
                                                        {{ $info->observacao }}</h6>
                                                </div>

                                                <a type="submit" class="js-del">
                                                    <i class="bi bi-dash-circle"></i>
                                                </a>
                                                {{-- <button type="submit" id="deleta_obs_ajax"
                                                onclick="deleta_obs_ajax(" class="text-muted"
                                                style="float: right;"><i class="bi bi-dash-circle"></i></button> --}}
                                    </form>
                                    <small class="text-muted"><b>Data: </b>{{ $info->data }} </small>
                                    </a>


                        </div>
                @endforeach

                {{-- </form> --}}
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
