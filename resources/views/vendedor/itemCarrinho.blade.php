@extends('layouts.app')

@section('content')

    <style>
        .carregando {
            display: none;
        }

        #campoInserirEntrada {
            display: none;
        }

    </style>
    @include('componentes.navbar', ['titulo' => 'Itens'])
    <br><br><br><br>

    @if (Session::has('success'))

        <body onload="msgSuccess('<?php echo Session::get('success'); ?>')">
    @endif
    @if (Session::has('error'))

        <body onload="msgError('<?php echo Session::get('error'); ?>')">
    @endif

    @if (!isset($carrinho))

        <div class="alert alert-warning mt-5" role="alert">
            Adicione Produtos No Seu Carrinho De Vendas :)
        </div>
    @else
        <!-- Modal Unifica -->
        <div class="modal fade" id="modalUnifica" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Desconto</h5><br>
                        <button id="closemodalDesconto" type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <form method="post" action="{{ route('unifica_valor_Itens', ['carrinho' => $carrinho->id]) }}">
                        @csrf
                        @method('put')
                        <div class="modal-body">
                            <div class="row g-2">
                                <div class="col-8">
                                    <div class="form-floating">
                                        <input required name="qtd_unificado" type="number" class="form-control" min="0.01"
                                            step="0.01" id="floatingInputGrid" placeholder="Desconto Geral">
                                        <label for="floatingInputGrid">Desconto</label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-floating">
                                        <select name="tipo_unificado" class="form-select" id="floatingSelectGrid"
                                            aria-label="Floating label select example">
                                            <option value="porcento">%</option>
                                            <option value="dinheiro">R$</option>
                                        </select>
                                        <label for="floatingSelectGrid">Tipo</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <a onclick="abremodalConfDesconto()" data-bs-toggle="modal" data-bs-target="#confzerarDesconto"
                                style="width:140px;" type="button" class="btn btn-danger col-5 mx-2">
                                Zerar Desconto
                            </a>
                            <button type="submit" class="btn btn-primary">Confirmar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Fim Modal Unificado --}}

        <!-- Modal confirma Zerar Desconto -->
        <div class="modal fade" id="confzerarDesconto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Confirmação</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Deseja Realmente Zerar o Desconto Deste Carrinho ?
                    </div>

                    <form action="{{ route('zera_desconto', ['carrinho' => $carrinho->id]) }}" method="POST"
                        class="modal-footer">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="verify" value="1">
                        <button onclick="abremodalDesconto()" type="button" class="btn btn-danger"
                            data-bs-dismiss="modal">Não</button>
                        <button type="submit" class="btn btn-primary">Sim</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Fim Modal confirma Zerar descontoa -->


        <!-- Modal confirma concelamento de venda -->
        <div class="modal fade" id="cancelaVenda" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Cancelar venda</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Deseja realmente cancelar todos os itens do carrinho?
                    </div>
                    <form action="{{ route('venda.destroy', ['venda' => $carrinho->id]) }}" method="POST"
                        class="modal-footer">
                        @method('DELETE')
                        @csrf
                        <input type="hidden" name="verify" value="1">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Não</button>
                        <button type="submit" class="btn btn-primary">Sim</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Fim Modal confirma concelamento de venda -->

        {{-- cards iniciais --}}

        <div class="listItemCarrinho">
            <div class="container">
                <div class="row d-flex justify-content-center">

                    <div class="col-lg-6 col-sm-6">
                        <div class="card-box bg-green">
                            <div class="inner">
                                <h6><i class="bi bi-cash-coin">
                                        Subtotal&ensp;R$<u>{{ reais($carrinho->valor_bruto) }}</u></i>
                                </h6>

                            </div>
                            <div class="inner">
                                <h6><i class="bi bi-cash-coin"> Total&ensp;<u>{{ 'R$' . reais($carrinho->total) }}</u></i>
                                </h6>
                            </div>
                            <div class="icon">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <a href="#" class="card-box-footer">Valores</a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6">
                        <div class="card-box bg-orange">
                            <div class="inner">
                                <i class="bi bi-cash-stack">&ensp;{{ $carrinho->valor_desconto }} </i>
                            </div>
                            <div class="inner">
                                <p style="color:black;">
                                    &ensp;{{ $carrinho->tp_desconto == 'porcento_unico' ? "Unificado em % $carrinho->desconto_qtd" : ($carrinho->tp_desconto == 'dinheiro_unico' ? "Unificado em R$ $carrinho->desconto_qtd" : (!$carrinho->tp_desconto ? 'Nenhum Desconto Aplicado' : 'Desconto Dado Parcialmente')) }}
                                </p>
                            </div>


                            <div class="icon">
                                <i class="bi bi-percent"></i>

                            </div>
                            <a href="#" class="card-box-footer">Descontos</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Fim cards iniciais --}}
        <div class="row d-flex justify-content-center">
            <button id="modalUnificaclick" type="button" class="btn btn-primary col-5 mx-2" data-bs-toggle="modal"
                data-bs-target="#modalUnifica">
                Desconto
            </button>

            <button href="" type="button" class="btn btn-danger col-5 mx-2" data-bs-toggle="modal"
                data-bs-target="#cancelaVenda">
                Cancelar Venda
            </button>

        </div>
        <div class="row d-flex justify-content-center" style="margin-top: 10px;">


            @if ($carrinho->cliente)
                <button type="button" class="btn btn-success col-5 mx-2" data-bs-toggle="modal"
                    data-bs-target="#modalFinalizaVendaComCLientSalvo">
                    Finalizar
                </button>
                <button type="button" class="btn btn-info col-5 mx-2" data-bs-toggle="modal"
                    data-bs-target="#salvarItensRetornados">
                    Salvar Itens
                </button>



                {{-- Modal Confirma cliente itens --}}
                <div class="modal fade" id="salvarItensRetornados" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <form action="{{ route('salvar_venda') }}" method="POST" class="modal-content">
                            @method('PUT')
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">Salvar Itens</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Carrinho já esta salvo para <b>{{ $carrinho->cliente->nome }}</b>, deseja
                                continuar?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Não</button>
                                <input type="hidden" name="cliente_id" value="{{ $carrinho->cliente_id }}" />
                                <button type="submit" class="btn btn-primary">Sim</button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <button id="abrirModalFinalizaVendaSemCliente" type="button" class="btn btn-success col-5 mx-2"
                    data-bs-toggle="modal" data-bs-target="#modalFinalizaVendaSemCliente">
                    Finalizar
                </button>
                <button type="button" class="btn btn-info col-5 mx-2" data-bs-toggle="modal" data-bs-target="#salvarItens">
                    Salvar Itens
                </button>
            @endif
        </div>

        {{-- Modal Salvar Itens Sem Clientes --}}
        <div class="modal fade" id="salvarItens" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Salvar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form id="formSalvaItensCliente" method="GET" class="search-cliente">
                        @csrf
                        <input id="nomeClienteSalvarItens" type="text" class="form-control" placeholder="nome">
                        <button id="botaoBuscaClienteAjax" class="lupa"><i class="bi-search"
                                style="color: black"></i></button>
                    </form>
                    <form action="{{ route('salvar_venda') }}" method="POST" class="modal-body">
                        @method('PUT')
                        @csrf
                        <div class="carregando">
                            <img style="width: 50%; heigth: 50%;" src="{{ asset('carregando.gif') }}" alt="">
                            <h6>Buscando Clientes....</h6>
                        </div>
                        <a id="lisClientesModal">
                            <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>
                            <ul class="list-group">
                                @foreach ($clientes_user as $cliente)
                                    <li style="text-align:justify; overflow-x: auto; overflow-y: hidden;overflow-y: hidden;"
                                        class="list-group-item d-flex justify-content-between align-items-center">
                                        {{ $cliente->nome }}
                                        <button type="submit" name="cliente_id" value="{{ $cliente->id }}"
                                            class="lupa-list"><i class="bi bi-save2"></i></button>
                                    </li>
                                @endforeach

                            </ul>
                        </a>
                    </form>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sair</button>
                    </div>

                </div>
            </div>
        </div>
        {{-- Fim Modal Salvar Itens --}}

        <hr><br>


        {{-- tabela de itens principal --}}
        @if ($carrinho)
            <div class="container">
                @foreach ($carrinho->carItem as $item)
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center"
                            style="border: none; background-color: rgb(193, 194, 201)">
                            <a
                                style="color: black; font-weight: 500; text-decoration:none; font-size:16px">{{ $item->produto->nome }}&ensp;{{ $item->iGrade ? '/' . $item->iGrade->tam : '' }}</a>
                            <a class="badge  rounded-pill" data-bs-toggle="modal"
                                data-bs-target="#deleteItemCarrinho{{ $item->id }}"><i class="bi bi-x-square"
                                    style="font-size: 22px; cursor: pointer; color:black;"></i></a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center"
                            style="background-color: rgb(230, 227, 255)">
                            <a href="" style="color: black; font-weight: 400; text-decoration:none;">Total: R$
                                {{ reais($item->valor) }}</a><a class="badge  rounded-pill" data-bs-toggle="modal"
                                data-bs-target="#editaItem{{ $item->id }}"><i class="bi bi-pencil-square"
                                    style="font-size: 22px; cursor: pointer; color:black;"></i></a>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Preço:
                            <span class="badge bg-primary rounded-pill">R$ {{ reais($item->preco) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Quantidade:
                            <span class="badge bg-primary rounded-pill">{{ $item->quantidade }}</span>
                            {{-- <div class="input-group list-qtd" style="width: 50%;">
                            <button class="btn btn-link" type="button"><i class="bi bi-dash-circle"></i></button>
                            <input type="text" class="form-control" placeholder="" value="{{ $item->quantidade }}">
                            <button class="btn btn-link" type="button"><i class="bi bi-plus-circle"></i></button>
                        </div> --}}
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Desconto:
                            @if ($carrinho->tp_desconto == 'porcento_unico' or !$carrinho->tp_desconto)
                                <span>{{ !$carrinho->tp_desconto ? 'Não Aplicado' : 'Porcentagem Única' }}</span>
                            @else
                                <span
                                    class="badge bg-primary rounded-pill">{{ $carrinho->tp_desconto == 'porcento_unico' ? 'Porcentagem única' : ($carrinho->tp_desconto == 'dinheiro_unico' ? 'Unificado em dinheiro' : (!$item->qtd_desconto ? 'Não inserido' : ($item->tipo_desconto == 'porcento' ? '%' . $item->qtd_desconto : "R$" . $item->qtd_desconto))) }}</span>
                            @endif
                        </li>
                        <br>

                        {{-- Modal carrinho item editar --}}
                        <div class="modal fade" id="editaItem{{ $item->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" style="margin-left: 35%;" id="exampleModalLabel">Editar
                                            Item
                                        </h5><br>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <br>
                                    <label class="mx-2" for=""> Produto: <b>
                                            {{ $item->produto->nome }}</b></label>
                                    <hr>

                                    <form method="POST" action="{{ route('venda.update', ['venda' => $item->id]) }}">
                                        @method('PUT')
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row g-2">
                                                <div class="col-8">
                                                    <div class="form-floating">
                                                        {{-- Verifica tipo de qauntidade --}}
                                                        @if ('u' == 'un')
                                                            <input name="quantidade" type="number" class="form-control"
                                                                min="0" value="{{ $item->quantidade }}"
                                                                id="floatingInputGrid" placeholder="quantidade">
                                                            <label for="floatingInputGrid">Quantidadeasd</label>
                                                        @else
                                                            <input name="quantidade" type="number" class="form-control"
                                                                min="0" value="{{ $item->quantidade }}"
                                                                id="floatingInputGrid" placeholder="quantidade">
                                                            <label for="floatingInputGrid">Quantidade</label>
                                                        @endif

                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-floating">
                                                        <select name="tipo_qtd" disabled class="form-select"
                                                            id="floatingSelectGrid"
                                                            aria-label="Floating label select example">
                                                            <option selected value="UN">UN</option>
                                                            {{-- <option value="CX">CX</option> --}}
                                                        </select>
                                                        <label for="floatingSelectGrid">Tipo</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <br>

                                            @if ($carrinho->tp_desconto == 'porcento_unico' or $carrinho->tp_desconto == 'dinheiro_unico')
                                                <span>Desconto único já inserido, para inserir desconto individual é
                                                    necessário
                                                    zerar o desconto.</span>
                                            @else
                                                {{-- --porcentagem --}}
                                                <div class="row g-2">
                                                    <div class="col-8">
                                                        <div class="form-floating">
                                                            <input {{ $item->qtd_desconto ? '' : 'disabled' }}
                                                                name="qtd_desconto" type="number" class="form-control"
                                                                min="0.01" step="0.01" value="{{ $item->qtd_desconto }}"
                                                                id="inputDescontoEditarItem<?php echo $item->id; ?>"
                                                                placeholder="Desconto Geral">
                                                            <label for="floatingInputGrid">Desconto</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <span></span>
                                                        <div class="form-floating">
                                                            <select
                                                                onchange="habilitaDescontoEditarItem(this.value, <?php echo $item->id; ?>)"
                                                                name="tipo_desconto" class="form-select"
                                                                id="floatingSelectGrid"
                                                                aria-label="Floating label select example">
                                                                @if ($item->qtd_desconto)
                                                                    <option value="">selecione...</option>
                                                                    <option value="porcento"
                                                                        {{ $item->tipo_desconto == 'porcento' ? 'selected' : '' }}>
                                                                        %</option>
                                                                    <option value="dinheiro"
                                                                        {{ $item->tipo_desconto == 'dinheiro' ? 'selected' : '' }}>
                                                                        R$</option>
                                                                @else
                                                                    <option value="">selecione...</option>
                                                                    <option value="porcento">%</option>
                                                                    <option value="dinheiro">R$</option>
                                                                @endif

                                                            </select>
                                                            <label for="floatingSelectGrid">Tipo</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                                aria-label="Close">Sair</button>

                                            <button type="submit" class="btn btn-primary">Confirmar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- fim modal editar --}}

                        {{-- Modal Excluir item do Carrinnho --}}
                        <div class="modal fade" id="deleteItemCarrinho{{ $item->id }}" data-bs-backdrop="static"
                            data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Confirmação</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <span>Deseja realmente excluir esse Item,
                                            <b>{{ $item->produto->nome }}</b>?</span>
                                    </div>
                                    <form action="{{ route('destroy_item', ['item' => $item->id]) }}" method="POST"
                                        class="modal-footer">
                                        @method('DELETE')
                                        @csrf
                                        <button type="button" id="#" data-bs-dismiss="modal" aria-label="Close"
                                            class="btn btn-secondary">
                                            Sair
                                        </button>
                                        <button type="submit" name="deleteCarrinho" value="1"
                                            class="btn btn-primary">Sim</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- Fim Modal Excluir item do Carrinnho --}}
                @endforeach
            </div>



            @if ($carrinho->cliente)
                {{-- Modal finaliza venda Com Cliente --}}
                <div class="modal fade" id="modalFinalizaVendaComCLientSalvo" tabindex="-1"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Finalizar Venda
                                </h5><br>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <br>
                            <div class="row mx-1">
                                <div class="col-5">
                                    <h6 class="">Total: R$
                                        <b id="valorTotalModal">{{ reais($carrinho->total) }}</b>
                                    </h6>
                                </div>
                                <div class="col-7">
                                    <h6 class="">Desconto: R$
                                        <b id="valorDescontoModal">{{ reais($carrinho->valor_desconto) }}</b>
                                    </h6>
                                </div>
                            </div>

                            <form id="formFinalizaVendaSubmit" method="POST" name="formFinalizaVenda"
                                action="{{ route('finaliza_venda', ['carrinho' => $carrinho->id]) }}">
                                @method('PUT')
                                @csrf
                                <input type="hidden" name="hiddenInputValorTotalModal" id="hiddenValorTotalModal"
                                    value="<?php echo $carrinho->total; ?>">
                                <input type="hidden" name="hiddenInputValorDescontoModal" id="hiddenValorDescontoModal"
                                    value="<?php echo $carrinho->valor_desconto; ?>">
                                <input type="hidden" name="hiddenInputValorDescontoSobreVendaModal"
                                    id="hiddenValorDescontoSobreVendaModal" value="">

                                <span class="mx-3"><b>Cliente Já Mencionado</b></span>
                                <div class="modal-body">

                                    <div class="row g-2">
                                        <div class="col-8">
                                            <div class="form-floating">
                                                <input id="clienteNomeVenda" Readonly type="text" class="form-control"
                                                    required value="{{ $carrinho->cliente->nome }}"
                                                    id="floatingInputGrid">

                                                <label for="floatingInputGrid">Cliente</label>

                                            </div>
                                            <span id="nomeValid"></span>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-floating">
                                                <input required name="cliente_alltech_id" Readonly id="clienteCodigo"
                                                    type="number" step="1" class="form-control"
                                                    value="{{ $carrinho->cliente_id ? $carrinho->cliente->alltech_id : null }}"
                                                    name="codigoCliente" id="floatingInputGrid">

                                                <label for="floatingInputGrid">Cod.</label>
                                            </div>
                                        </div>
                                    </div>

                                    <br>
                                    <span style="font-size: 14px;" class="mx-1"><u>Deseja aplicar desconto
                                            sobre a
                                            venda?</u></span>
                                    <br><br>
                                    <div class="row g-2">
                                        <div class="col-8">
                                            <div class="form-floating">
                                                <input disabled
                                                    onkeyup="calculoDescontoSobreVenda(<?php echo $carrinho->total; ?>, <?php echo $carrinho->valor_desconto; ?>)"
                                                    name="qtd_desconto_sobre_venda" type="number" required
                                                    class="form-control" min="0.01" step="0.01"
                                                    value="{{ $carrinho->qtd_unificado }}" id="inputDesconto">
                                                <label for="floatingInputGrid">Desconto</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <span></span>
                                            <div class="form-floating">
                                                <select name="tp_desconto_sb_venda" class="form-select"
                                                    id="tp_desconto_sobre_venda_modal"
                                                    onchange="verificaDesconto(this.value, <?php echo $carrinho->total; ?>, <?php echo $carrinho->valor_desconto; ?>)"
                                                    aria-label="Floating label select example">
                                                    <option selected value="0">selecione...</option>
                                                    <option value="porcento">%</option>
                                                    <option value="dinheiro">R$</option>
                                                </select>
                                                <label for="floatingSelectGrid">Tipo</label>
                                            </div>
                                        </div>
                                    </div>



                                    <br>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="form-floating">
                                                <input name="parcelas" disabled min="1" type="text" class="form-control"
                                                    placeholder="1" value="1" id="inputParcelas">
                                                <label for="floatingInputGrid">Parcelas</label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-floating">
                                                <select name="tipo_pagamento" class="form-select"
                                                    id="floatingSelectGrid" aria-label="Floating label select example"
                                                    onchange="verificaAvistaAprazo(this.value)">
                                                    <option value="AV">À VISTA</option>
                                                    <option value="AP">A PRAZO</option>
                                                </select>
                                                <label for="floatingSelectGrid">Tipo</label>
                                            </div>
                                        </div>
                                    </div>
                                    <br>

                                    {{-- Tipo pagamento --}}
                                    <div class="row g-2">
                                        <div id="campoInserirEntrada" class="col-12">
                                            <div class="form-floating">
                                                <input name="valor_entrada" type="number" class="form-control" min="0">

                                                <label for="floatingInputGrid">Aplicar algum valor de entrada ?</label>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="col-12">
                                            <div class="form-floating">
                                                <select name="forma_pagamento" class="form-select">

                                                    <option value="dinheiro">Dinheiro</option>
                                                    <option value="cartao">Cartao</option>
                                                    <option value="digital">Digital</option>
                                                    <option value="promissoria">Promissória</option>
                                                    <option value="credito">Crédito</option>
                                                    <option value="cheque">Cheque</option>
                                                </select>
                                                <label for="floatingSelectGrid">Tipo</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="closeModalFinalizaVenda" class="btn btn-secondary"
                                        data-bs-dismiss="modal" aria-label="Close">Sair</button>

                                    <button type="submit" class="btn btn-primary">Finalizar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- fim modal finalizar venda com cliente --}}
            @else
                {{-- Modal finaliza venda Sem Cliente --}}
                <div class="modal fade" id="modalFinalizaVendaSemCliente" tabindex="-1"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg"">
                                                                        <div class="             modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Finalizar Venda
                            </h5><br>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <br>
                        <div class="row mx-1">
                            <div class="col-5">
                                <h6 class="">Total: R$
                                    <b id="valorTotalModal">{{ reais($carrinho->total) }}</b>
                                </h6>
                            </div>
                            <div class="col-7">
                                <h6 class="">Desconto: R$
                                    <b id="valorDescontoModal">{{ reais($carrinho->valor_desconto) }}</b>
                                </h6>
                            </div>
                        </div>

                        <form id="formFinalizaVendaSubmit" method="POST" name="formFinalizaVenda"
                            action="{{ route('finaliza_venda', ['carrinho' => $carrinho->id]) }}">
                            @method('PUT')
                            @csrf
                            <input type="hidden" name="hiddenInputValorTotalModal" id="hiddenValorTotalModal"
                                value="<?php echo $carrinho->total; ?>">
                            <input type="hidden" name="hiddenInputValorDescontoModal" id="hiddenValorDescontoModal"
                                value="<?php echo $carrinho->valor_desconto; ?>">
                            <input type="hidden" name="hiddenInputValorDescontoSobreVendaModal"
                                id="hiddenValorDescontoSobreVendaModal" value="">

                            <div class="modal-body">
                                <div class="row g-2">
                                    <div class="col-4">
                                        <div class="form-floating">
                                            <input required name="cliente_alltech_id" id="clienteCodigo" type="number"
                                                step="1" class="form-control" {{-- value="{{ $carrinho->cliente_id ? $carrinho->cliente->alltech_id : '999999' }}" --}} name="codigoCliente"
                                                id="floatingInputGrid">
                                            <label for="floatingInputGrid">Cod.</label>
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="form-floating">
                                            {{-- id="clienteNomeVenda" --}}

                                            <input onclick="selectClienteFinalizaVenda()" id="clienteNomeVenda"
                                                list="listaClientes" required type="text" class="form-control" required
                                                name="nomeCLienteFinaliza" {{-- value="VENDA A VISTA"> --}}>
                                            <label for="floatingInputGrid">Cliente</label>
                                            <datalist id="listaClientes">


                                                @foreach ($clientes_user as $cliente)
                                                    <option value="{{ $cliente->nome }}">{{ $cliente->alltech_id }}
                                                    </option>
                                                @endforeach

                                            </datalist>
                                        </div>

                                        <span id="nomeValid"></span>
                                    </div>

                                    <a onclick="fechaModalfinalizaVenda()" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalbuscaClienteNomeVendaAjax"><i class="bi-search"
                                            style="color: rgb(255, 255, 255)"></i></a>
                                </div>
                                <br>

                                <span style="font-size: 14px;" class="mx-1"><u>Deseja aplicar desconto
                                        sobre a
                                        venda?</u></span>
                                <br><br>

                                {{-- Desconto --}}
                                <div class="row g-2">
                                    <div class="col-8">
                                        <div class="form-floating">
                                            <input disabled required
                                                onkeyup="calculoDescontoSobreVenda(<?php echo $carrinho->total; ?>, <?php echo $carrinho->valor_desconto; ?>)"
                                                name="qtd_desconto_sobre_venda" type="number" class="form-control"
                                                min="0" id="inputDesconto">
                                            <label for="floatingInputGrid">Desconto</label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-floating">
                                            <select name="tp_desconto_sb_venda" class="form-select"
                                                id="tp_desconto_sobre_venda_modal"
                                                onchange="verificaDesconto(this.value, <?php echo $carrinho->total; ?>, <?php echo $carrinho->valor_desconto; ?>)"
                                                aria-label="Floating label select example">
                                                <option selected value="0">selecione...</option>
                                                <option value="porcento">%</option>
                                                <option value="dinheiro">R$</option>
                                            </select>
                                            <label for="floatingSelectGrid">Tipo</label>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                {{-- Parcelas --}}
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input disabled required name="parcelas" type="text" class="form-control"
                                                placeholder="1" value="1" id="inputParcelas">
                                            <label for="floatingInputGrid">Parcelas</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <select name="tipo_pagamento" class="form-select" id="floatingSelectGrid"
                                                aria-label="Floating label select example"
                                                onchange="verificaAvistaAprazo(this.value)">
                                                <option value="AV">À VISTA</option>
                                                <option value="AP">A PRAZO</option>
                                            </select>
                                            <label for="floatingSelectGrid">Tipo</label>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                {{-- Tipo pagamento --}}
                                <div class="row g-2">
                                    <div id="campoInserirEntrada" class="col-12">
                                        <div class="form-floating">
                                            <input name="valor_entrada" type="number" class="form-control" min="0">

                                            <label for="floatingInputGrid">Aplicar algum valor de entrada ?</label>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <select name="forma_pagamento" class="form-select">

                                                <option selected value="dinheiro">Dinheiro</option>
                                                <option value="cartao">Cartao</option>
                                                <option value="digital">Digital</option>
                                                <option value="promissoria">Promissória</option>
                                                <option value="credito">Crédito</option>
                                                <option value="cheque">Cheque</option>
                                            </select>
                                            <label for="floatingSelectGrid">Tipo</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" id="closeModalFinalizaVenda" class="btn btn-secondary"
                                    data-bs-dismiss="modal" aria-label="Close">Sair</button>

                                <button type="submit" class="btn btn-primary">Finalizar</button>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
                {{-- fim Modal finaliza venda Sem Cliente --}}

                {{-- Modal modalbuscaClienteNomeVendaAjax --}}
                <div class="modal fade" id="modalbuscaClienteNomeVendaAjax" tabindex="-1"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Clientes</h5>
                                <button onclick="buscaClienteNomeVendaAjax()" type="button" class="btn-close"
                                    data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            {{-- usa o mesmo id para salvar clientes porque é a mesma busca que faz no controller via ajax --}}
                            <form id="formSalvaItensCliente" method="GET" class="search-cliente">
                                @csrf
                                <input id="buscaNomeClienteVendaAjax" type="text" class="form-control"
                                    placeholder="nome">
                                <button id="botaoBuscaClienteNomeAjax" class="lupa"><i class="bi-search"
                                        style="color: black"></i></button>
                            </form>
                            <form id="buscaAlltech_idClienteVendaAjax" method="GET" class="modal-body">
                                @csrf
                                <div class="carregando">
                                    <img style="width: 50%; heigth: 50%;" src="{{ asset('carregando.gif') }}" alt="">
                                    <h6>Buscando Clientes....</h6>
                                </div>
                                <a id="MontaBuscaClienteFinaliza">

                                    <ul class="list-group">
                                        @foreach ($clientes_user as $cliente)
                                            <li style="text-align:justify; overflow-x: auto; overflow-y: hidden; overflow-y: hidden; width: 93%;"
                                                class="list-group-item d-flex justify-content-between align-items-center mx-2">
                                                {{ $cliente->nome }}
                                                <button type="submit" name="cliente_id"
                                                    onclick="buttonAlltech_id(<?php echo $cliente->alltech_id; ?>)"
                                                    class="lupa-list"><i class="bi bi-save2"></i></button>
                                            </li>
                                        @endforeach

                                    </ul>
                                </a>
                            </form>
                            <div class="modal-footer">
                                <button onclick="buscaClienteNomeVendaAjax()" id="closeModalbuscaClienteNomeVendaAjax"
                                    type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sair</button>
                            </div>

                        </div>
                    </div>
                </div>
            @endif
            {{-- Fim Modal modalbuscaClienteNomeVendaAjax --}}
        @endif

    @endif
    {{-- Fim tabela de itens --}}
    </div>
    </div>

@endsection

<script type="text/javascript" src="{{ asset('js/viewItensCarrinho.js') }}" defer></script>
