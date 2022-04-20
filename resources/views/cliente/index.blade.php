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

</style>

@section('content')

    @include('componentes.navbar')
    @include('componentes.titulo', [
        'titlePage' => 'Clientes',
    ])
    @if (Session::has('Add_Obs'))

    <body onload="msgSuccess('Observação Adicionada Com Sucesso')">
    @endif

    <form action="{{ route('clientes.index', auth()->user()->id) }}">

        <input name="nome" class="inputBuscaCliente " id="inputBuscaCliente" type="text" placeholder="Buscar Cliente">

        <a id="buscaCliente" class="btn btn-primary"><i class="bi bi-arrow-left-right"></i></i></a>
    </form>

    <div id="contentIndex">
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
                                <div class="list-group" style="word-wrap: break-word;">
                                    {{-- <ul class="list-group-item list-group-item-action flex-column align-items-start"> --}}
                                    <li class="list-group-item">
                                        <a style="font: italic bold 15px monospace">
                                            {{ $cliente->tipo == 'J' ? 'CNPJ : ' : 'CPF : ' }}</a>
                                        &nbsp;&nbsp;{{ $cliente->docto ? $cliente->docto : 'Não Informado' }}

                                        <span data-bs-toggle="modal" data-bs-target="#contato{{ $cliente->id }}"
                                            style="float: right" class="badge bg-secondary"><i data-bs-toggle="modal"
                                                data-bs-target="#contato{{ $cliente->id }}"
                                                class="bi bi-telephone"></i></span>
                                    </li>

                                    <li class="list-group-item">

                                        <a style="font: italic bold 15px monospace"> Cidade : </a>
                                        &nbsp;&nbsp;{{ $cliente->enderecos && $cliente->enderecos->cidadeIbge? $cliente->enderecos->cidadeIbge->nome: 'Não Informado' }}&nbsp;&nbsp;{{ $cliente->enderecos && $cliente->enderecos->cidadeIbge? '| UF : ' . $cliente->enderecos->cidadeIbge->uf: 'UF: Não Informado' }}

                                    </li>
                                    <li class="list-group-item">
                                        <a style="font: italic bold 15px monospace"> Bairro : </a>
                                        &nbsp;&nbsp;{{ $cliente->enderecos ? $cliente->enderecos->bairro : 'Não Informado' }}

                                    </li>
                                    <li class="list-group-item">
                                        <a style="font: italic bold 15px monospace"> Rua : </a>
                                        &nbsp;&nbsp;{{ $cliente->enderecos ? $cliente->enderecos->rua : 'Não Informado' }}
                                        &nbsp;&nbsp;{{ $cliente->enderecos && $cliente->enderecos->numero ? ' | Nº : ' . $cliente->enderecos->numero : '| Nº : SN' }}

                                    </li>
                                    <li class="list-group-item">
                                        <a style="font: italic bold 15px monospace"> Complemento : </a>

                                        &nbsp;&nbsp;{{ $cliente->enderecos && $cliente->enderecos->compto ? $cliente->enderecos->compto : 'Não Informado' }}

                                    </li>
                                    <li class="list-group-item">
                                        <a style="font: italic bold 15px monospace"> E-mail : </a>&nbsp;&nbsp;
                                        {{ $cliente->email ? $cliente->email : 'Não Informado' }}

                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="{{ route('clientes.edit', $cliente->id) }}" style="cursor: pointer;"
                                            class="badge bg-primary rounded-pill">Editar
                                            Cliente</a>

                                        <span style="cursor: pointer;" class="badge bg-primary rounded-pill"
                                            data-bs-toggle="modal" data-bs-target="#addObs{{ $cliente->id }}">Adicionar
                                            Observação</span>
                                    </li>
                                    {{-- </ul> --}}
                                </div>

                                <!-- Modal Contato Cliente-->
                                <div class="modal fade" id="contato{{ $cliente->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">{{ $cliente->nome }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>

                                            </div>
                                            <div class="modal-body">
                                                <div class="row"><b> Telefones</b></div>
                                                <div class="list-group">

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
                                            <form action="{{ route('add_observacao', $cliente->id) }}" method="POST">
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

        {{-- paginação --}}
        <nav class="navegacao mt-3" aria-label="Navegação">
            <ul class="pagination" style="justify-content: center;">
                <li class="page-item">
                    <a class="page-link" href="{{ $clientes->previousPageUrl() }}" aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">voltar</span>
                    </a>
                </li>

                @for ($i = 1; $i <= ($clientes->lastPage() >= 6 ? 6 : $clientes->lastPage()); $i++)
                    <!-- a Tag for another page -->
                    <li class="page-item"><a class="page-link"
                            href="{{ $clientes->url($i) }}">{{ $i }}</a></li>
                @endfor

                <li class="page-item">
                    <a class="page-link" href="{{ $clientes->url($clientes->lastPage()) }}" aria-label="Próximo">
                        <span aria-hidden="true">... {{ $clientes->lastPage() }}</span>

                    </a>
                </li>
            </ul>
        </nav>
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

    </div>
@endsection
<script type="text/javascript" src="{{ asset('js/viewcliente.js') }}" defer></script>
