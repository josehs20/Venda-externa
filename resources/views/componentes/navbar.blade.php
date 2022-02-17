<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="bar">
        <div class="bar-pesquisa-user-carrinho">
            <div class="bar-items">
                <!-- Logo all tech -->
                <a class="navbar-brand" href="{{ url('/principal') }}">
                    <div class="div-logo-alltech"></div>
                </a>
                <form class="search" action="{{ route('principal') }}" method="GET">
                    @csrf
                   <input name="nome" type="search" class="form-control"
                            placeholder="Buscar Produto"> <button class="lupa"><i class="bi-search"
                                style="color: black"></i></button>
                </form>
                <div class="home-carrinho">
                    <a href="{{ url('/principal') }}"> <img href="{{ url('/principal') }}" class="inicio"
                            src="{{ asset('home.png') }}" alt="home"></a>
                    <a href="{{ route('itens_carrinho') }}">
                        <img class="fotocarr" src="{{ asset('carlimp.ico') }}">
                        <h6 class="quanti" style="color:#fff">
                            {{ $count_item ? $count_item->carItem->count() : '0' }}</h6>
                    </a>
                </div>

            </div>

            <div class="bar-user-sair">
                <!-- Carrinho -->



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
                        </a>      &emsp;&emsp;&emsp;
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



