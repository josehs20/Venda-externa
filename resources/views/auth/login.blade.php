@extends('layouts.app')

@section('content')
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="entrar/images/icons/favicon.ico" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="entrar/vendor/bootstrap/css/bootstrap.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="entrar/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="entrar/vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="entrar/vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="entrar/vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="entrar/css/util.css">
    <link rel="stylesheet" type="text/css" href="entrar/css/main.css">
    <!--===============================================================================================-->

    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="{{ asset('entrar/images/logo1alltech.jpeg') }}" alt="IMG">
                </div>

                <form class="login100-form validate-form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <span class="login100-form-title">
                        Sistema de Vendas 
                    </span>

                    <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                        <input class="input100" campo @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}" required autocomplete="email" autofocus>


                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <div class="wrap-input100 validate-input" data-validate="Password is required">
                        <input placeholder="Senha" id="password" type="password" class="input100 campo @error('password') is-invalid @enderror" name="password" required
                        autocomplete="current-password">

                        <span class="focus-input100"></span>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>
                    {{-- placeholder="Senha" id="password" type="password"
                        class="form-control campo @error('password') is-invalid @enderror" name="password" required
                        autocomplete="current-password"> --}}

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <div class="container-login100-form-btn">
                        <button type="submit" class="login100-form-btn">
                            Login
                        </button>
                    </div>



                    <input class="form-check-input mx-4" type="checkbox" name="remember" id="remember"
                        {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label mx-4" for="remember">
                        Lembre-se de mim
                    </label>

                    @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        Esqueceu sua senha?
                    </a>
                @endif
                </form>
            </div>
        </div>
    </div>




    <!--===============================================================================================-->
    <script src="entrar/vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="entrar/vendor/bootstrap/js/popper.js"></script>
    <script src="entrar/vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="entrar/vendor/select2/select2.min.js"></script>
    <!--===============================================================================================-->
    <script src="entrar/vendor/tilt/tilt.jquery.min.js"></script>
    <script>
        $('.js-tilt').tilt({
            scale: 1.1
        })
    </script>
    <!--===============================================================================================-->
    <script src="entrar/js/main.js"></script>
    {{-- <div class="div-externa-login">

        <div class="div-interna-login">
            <div class="div-logo">
                <div></div>
            </div>

            <div class="form-login">

                <h2>Vendas</h2>
                <br>
                <form class="form1" method="POST" action="{{ route('login') }}">
                    <h4>Entrar</h4>
                    @csrf
                    <!-- Campo de inserir Email -->
                    <input placeholder="Email" id="email" type="email"
                        class="form-control campo @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}"
                        required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <!-- Campo de inserir senha -->
                    <input placeholder="Senha" id="password" type="password"
                        class="form-control campo @error('password') is-invalid @enderror" name="password" required
                        autocomplete="current-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <!-- Botao de lembrar do login -->
                    <div>
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>
    
                        <label class="form-check-label" for="remember">
                            Lembre-se de mim
                        </label>
                    </div>
                    <br>
                    <!-- Botao de entrar na conta -->
                    <button type="submit" class="btn btn-primary">
                        Entrar
                    </button>
                    <br>
                    @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            Esqueceu sua senha?
                        </a>
                    @endif
                </form>

            </div>

        </div>
    </div> --}}
@endsection
