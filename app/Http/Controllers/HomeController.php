<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
      
        if (auth()->user()->perfil === 'vendedor') {
            return redirect(route('venda.index'));
            
    }else{
        
        return redirect('/login')->with('message', 'Sem premissão de acesso');
        }
    }
}