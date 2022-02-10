<nav style="margin: 10px;" class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="bar">
        <div class="bar-pesquisa-user-carrinho">
            <div class="bar-items">
                <!-- Logo all tech -->
                <a class="navbar-brand" href="{{ url('/principal') }}">
                    <div class="div-logo-alltech"></div>
                </a>
                <form action="{{ route('principal')}}" method="GET" style="height: 40px; width: 100%; margin: 0 20px;" class="d-flex">
                @csrf
                    <input name="nome" class="form-control mx-2" type="search" placeholder="Busca" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
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
                <a href="{{ route('itens_carrinho') }}">
                <div class="carrinho">
                        <h5 class="quantidade-carrinho">{{ $count_item ? $count_item->carItem->count() : '0' }}</h5>
                    
                </div>
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
    </div>
</nav>

