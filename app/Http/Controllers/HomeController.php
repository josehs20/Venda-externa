<?php

namespace App\Http\Controllers;

use App\Models\Funario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if (auth()->user()) {
            Session::flash('success', 'Bem vindo');
            return redirect(route('venda.index'));
        }

        Session::flash('error', 'Sem permissão de acesso');
        return redirect('/');
    }
}
