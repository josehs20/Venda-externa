<?php

namespace App\Http\Controllers;

use App\Jobs\ExportaClienteJob;
use App\Models\Alltech;
use App\Models\Carrinho;
use App\Models\CidadeIbge;
use App\Models\Cliente;
use App\Models\InfoCliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Builder;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clientes = Alltech::get_cliente_nome($request->nome);
        $cidades_ibge = [];
        $clientesIds = [];

        foreach ($clientes as $key => $c) {
            $clientesIds[] = $c->id;
            if ($c->enderecos->cidade_ibge_id) {
                $cidades_ibge[] = $c->enderecos->cidade_ibge_id;
            }
        }

        $infos = InfoCliente::whereIn('cliente_id', array_unique($clientesIds))->get();
        $infoCientes = [];
        foreach ($infos as $key => $i) {
            $infoCientes[$i->cliente_id][] = $i;
        }

        $cidades_ibge = Alltech::get_cidades_ibge($cidades_ibge);
        $cidades = [];

        foreach ($cidades_ibge as $key => $c) {
            $cidades[$c->id] = $c;
        }

        $clientes = Alltech::paginate($clientes);
        $clientes->withPath('/clientes?');

        return view('cliente.index', compact('clientes', 'cidades', 'infoCientes'));
    }

    // public function busca_cliente_ajax()
    // {
    //     $nome = $_GET['nome'];
    //     $codigo = $_GET['codigo'];

    //     if ($nome || $nome === "") {

    //         if ($nome === "") {
    //             $clientes = Cliente::where('loja_id', auth()->user()->loja_id)->get();
    //             //dd($clientes);
    //         } else {
    //             $clientes = Cliente::where('loja_id', auth()->user()->loja_id)->whereRaw("nome like '%{$_GET['nome']}%'")->get();
    //         }

    //         $dados['nome'] = $clientes->reject(function ($cliente) {
    //             return $cliente->alltech_id == '999999';
    //         });
    //     }

    //     if ($codigo) {
    //         //$cliente = Cliente::where('loja_id', auth()->user()->loja_id)->where('alltech_id', $_GET['codigo'])->orWhere('docto', $_GET['codigo'])->first();
    //         $cliente = Cliente::with('enderecos')->where('loja_id', auth()->user()->loja_id)->where("alltech_id", $_GET['codigo'])->orWhere('docto', $_GET['codigo'])->first();
    //         $dados['codigo'] = $cliente;
    //         if ($cliente) {
    //             $dados['cidade'] = $cliente->enderecos->cidadeIbge;
    //         }
    //     }

    //     echo json_encode($dados);
    //     return;
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cliente = null;
        // return view('cliente.form', compact('cliente'));
        return view('cliente.create', compact('cliente'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dados['create_cliente'] = [
            'alltech_id' => $_POST['documento'],
            'nome' => $_POST['nome'],
            'docto' => $_POST['documento'],
            'tipo' =>  strlen($_POST['documento']) == 11 ? 'F' : 'J',
            'email' => trim($_POST['email']) ? trim($_POST['email']) : null,
            'fone1' => strlen($_POST['telefones'][0]) > 7 ? $_POST['telefones'][0] : null,
            'fone2' => strlen($_POST['telefones'][1]) > 7 ? $_POST['telefones'][1] : null,
            'celular' => strlen($_POST['telefones'][2]) > 7 ? $_POST['telefones'][2] : null
        ];

        $dados['endereco'] = [
            'cidade_ibge_id' => trim($_POST['codIbge']) ? trim($_POST['codIbge']) : null,
            'cep' => preg_replace("/[^0-9]/", "", $_POST['cep']),
            'bairro' => $_POST['bairro'],
            'rua' => $_POST['rua']  ? $_POST['rua']  : null,
            'numero' => $_POST['numero'] ? intval($_POST['numero']) : null,
            'compto' => $_POST['complemento'] ? $_POST['complemento'] : null,
            'tipo' => 'R',
        ];

    
        $response = Alltech::create_cliente($dados);

        $cliente = $response->object();

        if ($response->status() == 400) {

            return response()->json(['msg' => 'Cliente com esse documento já existe ' . $cliente->verificaCliente->nome, 'success' => false]);
        }
        return response()->json(['msg' => 'Cliente cadastrado com sucesso ' . $cliente->nome, 'success' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cliente = Alltech::get_clientes_alltech([$id])[0];
        $cidade = Alltech::get_cidades_ibge([$cliente->enderecos->cidade_ibge_id])[0];

        $cliente->enderecos->cidadeIbge = $cidade;

        return view('cliente.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        // if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT')) {
        //     parse_str(file_get_contents('php://input'), $_PUT);

        //     $codIbge = CidadeIbge::where('codigo', trim($_PUT['codIbge']))->first();

        //     $cliente = Cliente::find($_PUT['id']);

        //     if (!$cliente->docto) {

        //         $dado['cliente'] = Cliente::where('loja_id', auth()->user()->loja_id)->where('docto', $_PUT['docto'])->first();

        //         //return caso já exista client com o mesmo docto
        //         if ($dado['cliente']) {
        //             $dado['success'] = false;
        //             echo json_encode($dado);
        //             return;
        //         }
        //     }

        //     $lojas = $cliente->loja->empresa->lojas;

        //     //Atualiza cliente parar todas lojas
        //     foreach ($lojas as $key => $loja) {

        //         $cliente =  $loja->clientes()->where('alltech_id', $cliente->alltech_id)->first();

        //         $cliente->update([
        //             'docto' => $_PUT['docto'],
        //             'nome' => $_PUT['nome'],
        //             'email' => $_PUT['email'],
        //             'fone1' => strlen($_PUT['telefones'][0]) > 7 ? $_PUT['telefones'][0] : null,
        //             'fone2' => strlen($_PUT['telefones'][1]) > 7 ? $_PUT['telefones'][1] : null,
        //             'celular' => strlen($_PUT['telefones'][2]) > 7 ? $_PUT['telefones'][2] : null,
        //         ]);
        //         $cliente->enderecos()->update([
        //             'cidade_ibge_id' => $codIbge->id ? $codIbge->id : null,
        //             'cep' => preg_replace("/[^0-9]/", "", $_PUT['cep']),
        //             'bairro' => $_PUT['bairro'],
        //             'rua' => $_PUT['rua']  ? $_PUT['rua']  : null,
        //             'numero' => $_PUT['numero'] ? intval($_PUT['numero']) : null,
        //             'compto' => $_PUT['complemento'] ? $_PUT['complemento'] : null,
        //         ]);
        //     }

        //     $dados['success'] = true;
        //     echo json_encode($dados);
        //     $cliente = Cliente::find($_PUT['id']);

        //     $this->jsonClienteStorageJob($cliente);
        // }
        // return;

        $dados['update_cliente'] = [
            'docto' => $request->docto,
            'email' => trim($request->email) ? trim($request->email) : null,
            'fone1' => strlen($request->telefones[0]) > 7 ? $request->telefones[0] : null,
            'fone2' => strlen($request->telefones[1]) > 7 ? $request->telefones[1] : null,
            'celular' => strlen($request->telefones[2]) > 7 ? $request->telefones[2] : null
        ];

        $dados['endereco'] = [
            'cidade_ibge_id' => trim($request->codIbge) ? trim($request->codIbge) : null,
            'cep' => preg_replace("/[^0-9]/", "", $request->cep),
            'uf' => $request->uf,
            'bairro' => $request->bairro,
            'rua' => $request->rua  ? $request->rua  : null,
            'tipo' => "R",
            'numero' => $request->numero ? $request->numero : null,
            'compto' => $request->complemento ? $request->complemento : null,
        ];

        $response = Alltech::update_cliente($dados, $id);

        $cliente = $response->object();
        return response()->json(['msg' => 'Cliente atualizado com sucesso ' . $cliente->nome, 'success' => true]);
    }

    //Faz o json, envia para storage para assim ser enviado para o ftp
    public function jsonClienteStorageJob($cliente)
    {
        $dados['id'] = $cliente->id;
        // $dados['alltech_id'] = $_SERVER['REQUEST_METHOD'] == 'PUT' ? "-" . $cliente->alltech_id : $cliente->alltech_id;
        $dados['loja_id'] = $cliente->loja_id;
        $dados['loja_alltech_id'] = $cliente->loja->alltech_id;
        $dados['nome'] = $cliente->nome;
        $dados['docto'] = $_SERVER['REQUEST_METHOD'] == 'PUT' ? '-' . $cliente->docto : $cliente->docto;
        $dados['tipo'] =  $cliente->tipo;
        $dados['email'] = trim($cliente->email) ? trim($cliente->email) : null;
        $dados['fone1'] = $cliente->fone1;
        $dados['fone2'] = $cliente->fone2;
        $dados['celular'] = $cliente->celular;
        $dados['cidade_ibge'] = $cliente->enderecos->cidadeIbge->codigo;
        $dados['cidade'] = $cliente->enderecos->cidadeIbge->nome;
        $dados['uf'] = $cliente->enderecos->cidadeIbge->uf;
        $dados['cep'] = $cliente->enderecos->cep;
        $dados['bairro'] = $cliente->enderecos->bairro;
        $dados['rua'] = $cliente->enderecos->rua;
        $dados['numero'] = $cliente->enderecos->numero;
        $dados['compto'] = $cliente->enderecos->compto;
        $dados['tipo_endereco'] = $cliente->enderecos->tipo;

        $json =  json_encode($dados, JSON_PRETTY_PRINT);

        $dir = $cliente->loja->empresa->pasta;

        //Class de Jobs para exportação 
        ExportaClienteJob::dispatch($json, $dir);
        return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // public function venda_salva(Request $request)
    // {

    //     $busca = $request->nome_n_pedido;

    //     $carrinhos = Carrinho::with('carItem', 'cliente')->where('user_id', auth()->user()->id)
    //         ->where('status', 'like', 'Salvo')->where('n_pedido', 'like', '%' . $busca . '%')
    //         ->orWhereHas('cliente', function (Builder $query) use ($busca) {

    //             $query->where('loja_id', auth()->user()->loja_id)->where('nome', 'like', '%' . $busca . '%');

    //         })->get()->reject(function ($c) {
    //             return $c->user_id != auth()->user()->id;
    //         });

    //     $carrinhos = $carrinhos->count() ? $carrinhos->toQuery()->paginate(30) : [];

    //     $cliente_carrinho = Carrinho::where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();

    //     return view('cliente.vendaSalva', compact('carrinhos', 'cliente_carrinho'));
    // }

    public function substitui_carrinho(Request $request, $carrinho)
    {
        $carrinho_substituido = Carrinho::find($carrinho);

        if ($request->substituir) {
            $cliente_carrinho = Carrinho::where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();
            // dd($cliente_carrinho);
            if ($cliente_carrinho->cliente) {
                $cliente_carrinho->update(['status' => "Salvo"]);

                $carrinho_substituido->update(['status' => "Aberto"]);
            } else {
                $cliente_carrinho->delete();

                Carrinho::find($carrinho)->update(['status' => "Aberto"]);
            }
        } else {
            //  dd(Carrinho::find($carrinho)->first());
            Carrinho::find($carrinho)->update(['status' => "Aberto"]);
        }
        //  dd('a');
        Session::flash('success', 'Itens Substituídos Com Sucesso');

        return redirect(route('itens_carrinho', ['user_id' => auth()->user()->id]));
    }

    public function add_observacao(Request $request, $cliente)
    {
        $data = date('d-m-Y', strtotime($request->data_obs));
        InfoCliente::create([
            'data' => $request->data_obs ?  $data : null,
            'observacao' => $request->observacao,
            'cliente_id' => $cliente,
        ]);
        Session::flash('success', 'Observacao adicionada');
        return redirect(route('clientes.index'));
    }

    public function deleta_obs_ajax($observacao)
    {
        $response = InfoCliente::find($observacao)->delete();
        if ($response) {
            $dado['success'] = true;
        } else {
            $dado['success'] = false;
        }
        return json_encode($dado);
    }
}
