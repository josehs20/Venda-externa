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
    @if (Session::has('error'))

        <body onload="msgError('<?php echo Session::get('error'); ?>')">
    @endif
    <style>
        .tituloLogin {
            text-align: center;
            border-radius: 20px;
            height: 50px;
            /* background: rgb(72,61,139); */
        }

        .letraTitulo {
            /* text-decoration: none; */
            color: rgb(72, 61, 139);
            font-weight: 1000;
            letter-spacing: 5px;
            /* font-family: 'Blippo', 'fantasy' */
        }

        .letraTitulo:after {
            content: '|';
            margin-left: 5px;
            opacity: 1;
            animation: flash .7s infinite;
        }

        @keyframes flash {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }
    </style>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100" style="margin-right: 20px;">
                <div class="login100-pic js-tilt" data-tilt>
                    <img src="{{ asset('entrar/images/logo1alltech.jpeg') }}" alt="IMG">
                </div>
                <div>

                    <div class="d-flex justify-content-center tituloLogin mb-5">
                        <h3 class="letraTitulo mt-2">Venda Externa</h3>

                    </div>
                    <div>
                        <form class="fom1" method="POST" id="loginAuth">
                            <h4>Entrar</h4>
                            @csrf
                            <!-- Campo de inserir Email -->
                            <input placeholder="Email" id="email" type="email"
                                class="form-control mt-2 campo @error('email') is-invalid @enderror" name="email"
                                value="{{ old('email') }}" required autocomplete="email" autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <!-- Campo de inserir senha -->
                            <input placeholder="Senha" id="senha" type="password"
                                class="form-control mt-3 campo @error('password') is-invalid @enderror" name="password"
                                required autocomplete="current-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <!-- Botao de lembrar do login -->
                            <div>
                                <input class="form-check-input mx-1" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label mx-2" for="remember">
                                    Lembre-se de mim
                                </label>
                            </div>
                            <br>
                            <!-- Botao de entrar na conta -->
                            <button type="submit" class="btn btn-primary">
                                Entrar
                            </button>
                            <br>
                            {{-- @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            Esqueceu sua senha?
                        </a>
                    @endif --}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script type="text/javascript" src="{{ asset('js/layoutfisic.js') }}" defer></script>
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
    <script>
        $(function() {
            $('form[id="loginAuth"]').submit(function(event) {
                event.preventDefault();
                var email = document.getElementById('email').value
                var senha = document.getElementById('senha').value

                $.ajax({
                    url: "http://localhost:8000/api/auth/login",
                    type: "POST",
                    data: {
                        email: email,
                        password: senha,

                    },
                    dataType: 'json',
                    success: function(response) {
                        Cookies.set('token_jwt', response.token.original.access_token, {
                            expires: 1
                        });
                       set_redireciona(response.usuario)
                    },
                    error: function(response) {
                        if (response.statusText == 'Unauthorized') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Permissão negada',
                                text: 'Usuário não cadastrado ou inativo.',
                                showConfirmButton: false,
                                timer: 3500
                            });
                        }
                    }
                })
            })
        })

        function set_redireciona(id) {
            $.ajax({
                url: "/get_usuario",
                type: "GET",
                data: {
                   usuario: id,
                },
                dataType: 'json',
                success: function(response) {
                   console.log(response);
                    //window.location.href = '/home';
                },
                error: function(response) {
                    console.log(response);
                    // if (response.statusText == 'Unauthorized') {
                    //     Swal.fire({
                    //         icon: 'error',
                    //         title: 'Permissão negada',
                    //         text: 'Usuário não cadastrado ou inativo.',
                    //         showConfirmButton: false,
                    //         timer: 3500
                    //     });
                    // }
                }
            })
        }

        function typeWrite(e) {
            const textoArray = e.innerHTML.split('');
            e.innerHTML = ' ';
            textoArray.forEach(function(letter, i) {
                setTimeout(function() {
                    e.innerHTML += letter;
                }, 200 * i);
            });
        }
        const phrase = document.querySelector('.letraTitulo');

        typeWrite(phrase);
    </script>
@endsection
