<nav class="navbar navbar-expand-lg navbar-light bg-light" style="z-index: 99; margin-top:2%;">
    <div class="bar" style="position: fixed;">
        <div class="bar-pesquisa-user-carrinho">
            <div class="bar-items">
                <!-- Logo all tech -->
                <a class="navbar-brand" href="{{ route('venda.index') }}">
                    <div class="div-logo-alltech"></div>
                </a>

                <form class="search" action="{{ route('venda.index') }}" method="GET">
                    @csrf
                    <input id="search" name="nome" type="search" class="form-control" placeholder="Buscar Produto">
                    <button id="preventBuscaProduto" class="lupa"><i class="bi-search" style="color: black"></i></button>
                </form>
                <div class="home-carrinho">
                    {{-- Menu Personalizado --}}
                    <div class="btn-group dropstart">
                        <a type="button" class="dropdown" data-bs-toggle="dropdown" aria-expanded="false">

                            <div id="btnMenu" class="btnMenu">
                                <div class="" id="linha1"></div>
                                <div class="" id="linha2"></div>
                                <div class="" id="linha3"></div>
                            </div>
                        </a>

                        <ul class="dropdown-menu">

                            <li><a class="dropdown-item"
                                    href="{{ route('vendedor.cliente.index', auth()->user()->id) }} ">Clientes</a></li>
                            <li><a class="dropdown-item" href="{{ route('venda_salva') }} ">Vendas Salvas</a></li>

                        </ul>
                    </div>

                    <a href="{{ route('itens_carrinho', ['user_id' => auth()->user()->id]) }}">
                        <img class="fotocarr" src="{{ asset('navCarr.ico') }}">
                        <h6 class="quanti" style="color:#fff">
                            <div class="quantiCar" style="width: 25px; height: 20px; text-align:center;"><p>{{count_item(auth()->user()->id)}}</p></div>
                            </h6>
                    </a>
                </div>
            </div>

            <div class="bar-user-sair">

                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    @endif

                    @if (Route::has('register'))
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    @endif
                @else
                    <!-- Usuario -->

                    <div class="sair">
                        <a style="color: white;" role="button">
                            {{ Auth::user()->name }}
                        </a> &emsp;&emsp;&emsp;
                        <a style="color: rgb(138, 138, 138);" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                                                                                                                             document.getElementById('logout-form').submit();">
                            {{ __('Sair') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>

                @endguest
            </div>
        </div>
    </div>
</nav>
<script type="text/javascript" src="{{ asset('js/layoutfisic.js') }}" defer></script>
