<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="bar">
        <div class="bar-pesquisa-user-carrinho">
            <div class="bar-items">
                <!-- Logo all tech -->
                <a class="navbar-brand" href="{{ url('/principal') }}">
                    <div class="div-logo-alltech"></div>
                </a>
                {{-- <form action="{{ route('principal') }}" method="GET" style="height: 40px; width: 60%; margin: 0 20px;"
                    class="d-flex">
                    @csrf --}}
                <div class="container">
                    <div class="row height d-flex justify-content-center align-items-center">
                        <div class="">
                            <div class="search"><input type="text" class="form-control"
                                    placeholder="Buscar Produto"> <button class="lupa"><i class="bi-search"
                                        style="color: black"></i></button> </div>
                        </div>
                    </div>
                </div>
                {{-- <input name="nome" class="form-control" type="search" placeholder="Buscar Produto"
                        aria-label="Search">
                    <button class="btn btn-outline-primary" type="submit" style="background-color: rgb(17, 0, 255) ">
                        <i class="bi bi-search" style="color: white"></i>
                    </button> --}}
                {{-- </form> --}}
            </div>
            <div class="bar-user-carrinho">
                <!-- Carrinho -->
                {{-- <div class="carrinho">
                    <a href="{{ route('itens_carrinho') }}">
                        <p class="quantidade-carrinho">
                            {{ $count_item ? $count_item->carItem->count() : '0' }}
                        </p>
                        <i class="bi bi-cart2 icone-carrinho"></i>
                    </a>
                </div> --}}

                {{-- <a href="{{ route('itens_carrinho') }}">
                    <div class="quanti" style="background-color: rgb(102, 10, 10);">
                        <h6 style="margin-left: 3px; margin-top: 3px; color:white">
                            {{ $count_item ? $count_item->carItem->count() : '0' }}</h6>
                    </div> --}}
               
            <a href="{{ url('/principal') }}" > <img class="inicio" src="{{ asset('home.png') }}" alt="home"></a>
                <a class="fot" href="{{ route('itens_carrinho') }}">
                    <img class="fotocarr" src="{{ asset('carlimp.png') }}">
                    <h6 class="quanti" style="color:white">
                        {{ $count_item ? $count_item->carItem->count() : '0' }}</h6>
                </a>

                <ul class="navbar-nav mx-3">
                    <li>
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <!-- Usuario -->
                        <h6 class="nome-user" style="margin-top: -1px; color:white;">
                            {{ Auth::user()->name }}
                        </h6>

                        <div class="logout">
                            <a style="color: rgb(136, 0, 0);" href="{{ route('logout') }}"
                                onclick="event.preventDefault();  document.getElementById('logout-form').submit();">
                                {{ __('Sair') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                        </li>
                    @endguest
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
