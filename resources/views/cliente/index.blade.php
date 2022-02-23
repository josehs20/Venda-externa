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
        margin-top: 30px;
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
                <div class="modal-body">
                    <form action="{{ route('vendedor.cliente.store', auth()->user()->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Nome:</label>
                            <input type="text" required name="nome" class="form-control" id="recipient-name">
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Telefone:</label>
                            <input type="number" name="tel" class="form-control" id="message-text">
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
                            style="background-color: rgb(58, 36, 252); font-size:20px;">{{ $cliente->nome }}</button>
                        <div class="contentconspllan">
                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1">Observação</h5>
                                        <small class="text-muted">{{ $cliente->updated_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed
                                        diam eget risus varius blandit.</p>
                                    <small class="text-muted">Donec id elit non mi porta.</small>
                                </a>
                            </div>
                        </div>

                    </ul>
                @endforeach
            </div>
        </div>
    @else
        <div class="alert alert-warning mt-5" role="alert">
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
