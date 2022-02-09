<nav style="margin: 10px;" class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="bar">
        <div class="bar-pesquisa-user-carrinho">
            <div class="bar-items">
                <!-- Logo all tech -->
                <a class="navbar-brand" href="{{ url('/principal') }}">
                    <div class="div-logo-alltech"></div>
                </a>
                <form style="height: 40px; width: 100%; margin: 0 20px;" class="d-flex">
                    <input class="form-control mx-2" type="search" placeholder="Busca" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>

            <div class="bar-user-carrinho">
                <!-- Carrinho -->
                <div class="carrinho">
                    <a href="{{ route('itens_carrinho') }}">
                        <p class="quantidade-carrinho">
                            {{ $count_item ? $count_item->carItem->count() : '0' }}
                        </p>
                        <i class="bi bi-cart2 icone-carrinho"></i>
                    </a>
                </div>
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
                        <li class="nav-item dropdown">
                            <!-- Usuario -->
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                                                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                    </li>
                </ul>
            </div>
        </div>


        {{-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button> --}}
    </div>
</nav>







{{-- <nav class="navbar navbar-expand-md navbar-light bg-white barra-navegacao">
    <div class="container">
        <a href="{{ url('/principal') }}">
            <div class="div-logo-alltech"></div>
        </a>
        <!-- Pesquisa -->
        <nav style="width: 100%;" class="navbar navbar-light bg-light">
            <div class="container">
                <form style="width: 100%; margin: 0 10px;" class="d-flex">
                    <!-- Campo de pesquisa -->
                    <input style="width: 100%;" class="form-control me-2" type="search" placeholder="Busca"
                        aria-label="Search">
                    <!-- Botao de pesquisa -->
                    <i class="bi bi-search" style="font-size: 23px;"></i>
                </form>
            </div>
        </nav>
        <div class="container collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ms-auto">
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
                    <li class="nav-item dropdown">
                        <!-- Usuario -->
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                                                         document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
        <!-- Carrinho -->
        <div class="carrinho">
            <a href="{{ route('itens_carrinho') }}">
                <p class="quantidade-carrinho">
                    {{ $count_item ? $count_item->carItem->count() : '0' }}
                </p>
                <i class="bi bi-cart2 icone-carrinho"></i>
            </a>
        </div>

        <!-- Botao mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav> --}}
