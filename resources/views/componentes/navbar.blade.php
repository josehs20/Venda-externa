<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    li {
        list-style: none;
    }

    a {
        text-decoration: none;
        cursor: pointer;
        color: #ffffff;
        transition: 0.4s;
    }

    a:hover {
        color: #f79831;
    }

    a:hover i {
        color: #f79831;
    }

    i {
        color: #000000;
        transition: 0.4s;
    }

    body {
        overflow-x: hidden;
    }

    header {
        height: 60px;
        display: flex;
        align-items: center;
        border-bottom: 1px solid #eee;
        position: fixed;
        right: 0;
        left: 0;
        top: 0;
        z-index: 447;
        background-image: linear-gradient(to right, #300e4d 10%, #082997 400%);
    }

    header nav {
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 2fr 1fr;
        justify-content: center;
    }

    header nav .logo {
        width: 50%;
        justify-self: center;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    header nav .logo img {
        width: 35%;

    }

    @media (max-width: 576px) {
        header nav .logo {
            width: 80px;

        }

        header nav .logo img {
            width: 60%;

        }
    }

    @media (max-width: 400px) {
        header nav .logo {
            width: 60px;
        }

        header nav .logo img {
            width: 80%;

        }
    }

    header nav .search__bar__header {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;

    }

    header nav .search__bar__header input[type="text"] {
        width: 80%;
        padding: 13px;
        outline: none;
        border-radius: 5px;
        box-shadow: 0px 0px 2px #f79831;
    }

    @media (max-width: 576px) {
        header nav .search__bar__header input[type="text"] {
            padding: 8px;
        }
    }

    header nav .search__bar__header input[type="button"] {
        padding: 13px;
        outline: none;
        border: none;
        box-shadow: 0px 0px 2px #f79831;
        cursor: pointer;
    }

    @media (max-width: 576px) {
        header nav .search__bar__header input[type="button"] {
            padding: 9px;
            font-size: 0.7em;
        }
    }

    header nav .cart__header__desktop {
        display: flex;
        align-items: center;
        justify-content: center;

    }

    header nav .cart__header__desktop i {
        font-size: 1.5em;
        cursor: pointer;
    }

    @media (max-width: 576px) {
        header nav .cart__header__desktop i {
            font-size: 1em;
        }
    }

    header nav .cart__header__desktop span {
        background-color: #f79831;
        padding: 3px 6px;
        margin-left: 3px;
        border-radius: 5px;
        color: #fff;
        margin-bottom: 19px;
    }

    @media (max-width: 576px) {
        header nav .cart__header__desktop span {
            font-size: 0.7em;
            padding: 1px 4px;
        }
    }

    @media (max-width: 992px) {
        header nav {
            grid-template-columns: 1fr 2fr 0.7fr;
        }
    }

    @media (max-width: 768px) {
        header nav {
            grid-template-columns: 0.9fr 2fr 0.5fr;
        }
    }

    @media (max-width: 400px) {
        header nav {
            grid-template-columns: 0.6fr 2fr 0.5fr;
        }
    }

    .main__navigations {
        position: fixed;
        left: 0;
        right: 0;
        top: 59px;
        z-index: 447;
        padding: 8px 0 15px;
        background-image: linear-gradient(to right, #443fa5 10%, #5d6b94 400%);
    }

    .main__navigations__div {
        display: flex;
    }

    .main__navigations__div ul.main__navigations__div__ul {
        width: 50%;
        margin: auto;
        display: flex;
        justify-content: space-evenly;
    }

    .main__navigations__div ul.main__navigations__div__ul .anchor__remains__same i {
        font-size: 0.8em;
    }

    @media (max-width: 1200px) {
        .main__navigations__div ul.main__navigations__div__ul {
            width: 60%;
        }
    }

    @media (max-width: 992px) {
        .main__navigations__div ul.main__navigations__div__ul {
            width: 70%;
        }
    }



    /* formatação do elemento */
    @media (min-width: 769px) {
        #lupa {
            margin: 3px 0px 0px -40px;
            cursor: pointer;
            border-left: 1px solid rgb(78, 78, 78);
            height: 75%;
            width: 15%;
            margin-top: -1px;
            display: flex;
            justify-content: center;
            align-items: center
        }

        /* formatação do conteúdo  */
        #lupa:after {
            font-family: FontAwesome;
            font-size: 14px;
            content: "\f002";
            margin-right: 50px;

        }
    }

    @media (max-width: 768px) {
        .main__navigations__div ul.main__navigations__div__ul {
            width: 80%;
        }

        /* #lupa {
            margin: 3px 0px 0px -40px;
            cursor: pointer;
            border-left: 1px solid rgb(78, 78, 78);
            height: 75%;
            width: 15%;
            margin-top: -1px;
            display: flex;
            justify-content: center;
            align-items: center
        } */

        /* formatação do conteúdo  */
        #lupa:after {
            font-family: FontAwesome;
            font-size: 14px;
            content: "\f002";
        }
    }

    @media (max-width: 576px) {
        .main__navigations__div ul.main__navigations__div__ul {
            width: 100%;
        }
    }

    @media (max-width: 510px) {
        .main__navigations__div ul.main__navigations__div__ul {
            justify-content: space-between;
        }

        .main__navigations__div ul.main__navigations__div__ul .on__mobile_d_none {
            display: none;
        }

        .main__navigations__div ul.main__navigations__div__ul .anchor__remains__same {
            font-size: 0.8em;
        }

        .main__navigations__div ul.main__navigations__div__ul .anchor__remains__same i {
            font-size: 0.8em;
        }

        .main__navigations__div ul.main__navigations__div__ul .save__from__left {
            margin-left: 15px;
        }

        .main__navigations__div ul.main__navigations__div__ul .save__from__right {
            margin-right: 15px;
        }
    }

    .main__navigations__div ul.main__navigations__div__ul li.main__navigations__div__li {
        justify-self: center;
    }

    .main__navigations__div ul.main__navigations__div__ul li.main__navigations__div__li:nth-child(2) a {
        padding-bottom: 10px;
    }

    .main__navigations__div ul.main__navigations__div__ul li.main__navigations__div__li:nth-child(2):hover .div__categories__items {
        opacity: 1;
        visibility: visible;
    }

    .main__navigations__div ul.main__navigations__div__ul li.main__navigations__div__li .div__categories__items {
        position: absolute;
        padding: 20px 40px;
        border-radius: 5px;
        margin-top: 8px;
        background-color: rgba(68, 68, 68, 0.8);
        transition: 0.4s;
        opacity: 0;
        visibility: hidden;
    }

    .main__navigations__div ul.main__navigations__div__ul li.main__navigations__div__li .div__categories__items p {
        margin-bottom: 19px;
    }

    .main__navigations__div ul.main__navigations__div__ul li.main__navigations__div__li .div__categories__items p a {
        color: #fff;
    }

    .main__navigations__div ul.main__navigations__div__ul li.main__navigations__div__li .div__categories__items p a:hover {
        color: #f79831;
    }

    .bars {
        cursor: pointer;
    }

    .bars div {
        width: 22px;
        height: 2px;
        background-color: #ffffff;
        margin: 4px 0;
        transition: .4s;
    }

    @media (min-width: 510px) {
        .bars div {
            display: none;
        }
    }

    .toggle .line1 {
        transform: rotate(-405deg) translate(-3px, 6px);
    }

    .toggle .line2 {
        opacity: 0;
    }

    .toggle .line3 {
        transform: rotate(405deg) translate(-3px, -6px);
    }

    .mobile__nav__fade__and__show {
        position: fixed;
        border-radius: 50%;
        z-index: 449;
        transition: .4s;
        top: -100%;
    }

    @media (min-width: 510px) {
        .mobile__nav__fade__and__show {
            display: none;
        }
    }

    .mobile__nav__fade__and__show li {
        margin-bottom: 15px;
        color: #fff;
    }

    .mobile__nav__fade__and__show li>a {
        font-weight: bold;
        color: #f79831;
        color: #fff;
    }

    .mobile__nav__fade__and__show li>a:hover {
        color: #ccc;
    }

    .mobile__nav__fade__and__show li>div {
        margin-left: 25px;
    }

    .mobile__nav__fade__and__show li>div p {
        margin-top: 8px;
    }

    .mobile__nav__fade__and__show li>div p a {
        color: #fff;
    }

    .mobile__nav__fade__and__show li>div p a:hover {
        color: #ccc;
    }

    .mobile__nav__fade__and__show.open {
        left: 7%;
        top: 3%;
    }

    .mobile__nav__fade__and__show__circle {
        width: 85%;
        height: 455px;
        background-image: linear-gradient(120deg, #c8f17da6 -30%, #000c92 100%);
        box-shadow: 0px 10px 10px rgb(0, 0, 0);
        position: fixed;
        top: 0;
        z-index: 448;
        border-radius: 5%;
        transform: translate(-100%, -100%);
        transition: .4s;
    }

    .mobile__nav__fade__and__show__circle.open {
        transform: translate(-30%, -30%);
    }

    @media (min-width: 510px) {
        .mobile__nav__fade__and__show__circle {
            display: none;
        }
    }

    .login__page {
        background-image: url("../images/img2.jpg");
        height: 900px;
        width: 100%;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        background-position-y: 100px;
    }

    /*# sourceMappingURL=main.css.map */

</style>
<header>
    <nav>
        <a href="{{ route('venda.index') }}" class="logo">
            <img src="{{ asset('image/logo2.png') }}" alt="">
        </a>
        <div class="search__bar__header">
            {{-- <input type="text" />
            <div id="lupa"></div> --}}
            <form class="search" action="{{ route('venda.index') }}" method="GET">
                @csrf
                <input id="search" name="nome" type="search" class="form-control" placeholder="Buscar Produto">
                <button id="preventBuscaProduto" class="lupa"><i class="bi-search"
                        style="color: black"></i></button>
            </form>
        </div>
        <a href="{{ route('carrinho.index') }}" class="cart__header__desktop">
            <i style="color: white;" class="fas fa-shopping-cart"></i>
            <span id="countItensCar"></span>
        </a>
    </nav>
</header>
<div class="main__navigations">

    <div class="main__navigations__div">
        <li class="main__navigations__div__li save__from__left mx-3"><a class="anchor__remains__same"
                style="font-weight: bold; color:rgb(255, 152, 56); font-size:14px;">
                {{$titulo}}</a></li>
        <ul class="main__navigations__div__ul">

            <li class="main__navigations__div__li on__mobile_d_none" style="font-weight: bold;"><a
                    style="font-weight: bold; color:white;" href="{{ route('venda.index') }}">Home</a></li>
            <li class="main__navigations__div__li on__mobile_d_none">
                <a class="categ" style="font-weight: bold; color:white;">Menu <i
                        class="fas fa-caret-down"></i></a>
                <div class="div__categories__items">
                    <p><a href="{{ route('venda.index') }}">Produtos</a></p>
                    <p><a href="{{ route('clientes.index') }}">Clientes</a></p>
                    <p><a href="{{ route('clientes.create') }}">Cadastro De Cliente</a></p>
                    <p><a href="{{ route('vendas_finalizadas') }}">Vendas Finalizadas</a></p>
                    <p><a href="{{ route('venda_salva') }}">Vendas Salvas</a></p>
                    <p><a href="{{ route('vendas_invalidas') }}">Vendas Em Análise</a></p>
                </div>
            </li>

            <li class="main__navigations__div__li save__from__left"><a class="anchor__remains__same"
                    style="font-weight: bold; color:white;"><i style="color: #d1d1d1;" class="fas fa-user"></i>
                    Olá, {{ auth()->user()->name }}</a></li>


            <li class="main__navigations__div__li on__mobile_d_none">
                <a href="{{ route('logout') }}" class="anchor__remains__same"
                    style="padding-bottom: 0;font-weight: bold; color:rgb(255, 255, 255);"
                    href="{{ route('logout') }}" onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                    {{ __('Sair') }} <i style="color: rgb(209, 209, 209)" class="fas fa-sign-out-alt"></i> </a>
            </li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>

            <div class="bars save__from__right">
                <div class="line1"></div>
                <div class="line2"></div>
                <div class="line3"></div>
            </div>
        </ul>

    </div>
</div>

<div class="mobile__nav__fade__and__show">
    <div class="mobile__nav__fade__and__show__text">
        <li>
            <a href="">Menu</a>
            <div>
                <p><a href="{{ route('venda.index') }}">Produtos</a></p>
                <p><a href="{{ route('clientes.index') }}">Clientes</a></p>
                <p><a href="{{ route('clientes.create') }}">Cadastro De Cliente</a></p>
                <p><a href="{{ route('vendas_finalizadas') }}">Vendas Finalizadas</a></p>
                <p><a href="{{ route('venda_salva') }}">Vendas Salvas</a></p>
                <p><a href="{{ route('vendas_invalidas') }}">Vendas Em Análise</a></p>
            </div>
        </li>
        <li class="main__navigations__div__li">
            <a href="{{ route('logout') }}" class="anchor__remains__same"
                style="padding-bottom: 0;font-weight: bold; color:rgb(255, 255, 255);" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                 document.getElementById('logout-form').submit();">
                {{ __('Sair') }} <i style="color: rgb(209, 209, 209)" class="fas fa-sign-out-alt"></i> </a>
        </li>
    </div>
</div>

<div class="mobile__nav__fade__and__show__circle"></div>

<script type="text/javascript" src="{{ asset('js/layoutfisic.js') }}" defer></script>

<script>
    $(".bars").click(() => {

        $(".mobile__nav__fade__and__show__circle").toggleClass("open");
        $(".mobile__nav__fade__and__show").toggleClass("open");
        $(".bars").toggleClass("toggle")

    })
</script>
