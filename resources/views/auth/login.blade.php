@extends('layouts.app')

@section('content')

    <div class="div-externa-login">

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
    </div>
@endsection
