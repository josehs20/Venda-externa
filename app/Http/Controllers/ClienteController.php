<?php

namespace App\Http\Controllers;

use App\Models\CidadeIbge;
use App\Models\Cliente;
use App\Models\InfoCliente;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //  dd(Storage::disk('ftp')->directories());
        $clientes = Cliente::with('infoCliente', 'enderecos')->where('loja_id', auth()->user()->loja_id)
            ->whereRaw("nome like '%{$request->nome}%'")->orderBy('nome')->paginate(30);

        return view('cliente.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cliente = null;
        return view('cliente.form', compact('cliente'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $codIbge = CidadeIbge::where('codigo', trim($_POST['codIbge']))->first();

        $cliente =  Cliente::create([
            'loja_id' => auth()->user()->loja_id,
            'alltech_id' => '0000',
            'nome' => $_POST['nome'],
            'docto' => $_POST['documento'],
            'tipo' =>  strlen($_POST['documento']) == 11 ? 'F' : 'J',
            'email' => $_POST['email'],
            'fone1' => strlen($_POST['telefones'][0]) > 7 ? $_POST['telefones'][0] : null,
            'fone2' => strlen($_POST['telefones'][1]) > 7 ? $_POST['telefones'][1] : null,
            'celular' => strlen($_POST['telefones'][2]) > 7 ? $_POST['telefones'][2] : null,
        ]);
        $endereco = $cliente->enderecos()->create([
            'cidade_ibge_id' => $codIbge->id ? $codIbge->id : null,
            'cep' => preg_replace("/[^0-9]/", "", $_POST['cep']),
            'bairro' => $_POST['bairro'],
            'rua' => $_POST['rua']  ? $_POST['rua']  : null,
            'numero' => $_POST['numero'] ? intval($_POST['numero']) : null,
            'compto' => $_POST['complemento'] ? $_POST['complemento'] : null,
        ]);
        $this->exportaClienteNovo($cliente, $endereco);
        $dados['success'] = true;
        echo json_encode($dados);
        Session::flash('clienteadd');
        return;
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
        $cliente = Cliente::find($id);
        return view('cliente.formUpdate', compact('cliente'));
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
        if (!strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT')) {
            parse_str(file_get_contents('php://input'), $_PUT);

            $codIbge = CidadeIbge::where('codigo', trim($_PUT['codIbge']))->first();

            $cliente = Cliente::find($_PUT['id']);

            $cliente->update([
                'email' => $_PUT['email'],
                'fone1' => strlen($_PUT['telefones'][0]) > 7 ? $_PUT['telefones'][0] : null,
                'fone2' => strlen($_PUT['telefones'][1]) > 7 ? $_PUT['telefones'][1] : null,
                'celular' => strlen($_PUT['telefones'][2]) > 7 ? $_PUT['telefones'][2] : null,
            ]);
            $cliente->enderecos()->update([
                'cidade_ibge_id' => $codIbge->id ? $codIbge->id : null,
                'cep' => preg_replace("/[^0-9]/", "", $_PUT['cep']),
                'bairro' => $_PUT['bairro'],
                'rua' => $_PUT['rua']  ? $_PUT['rua']  : null,
                'numero' => $_PUT['numero'] ? intval($_PUT['numero']) : null,
                'compto' => $_PUT['complemento'] ? $_PUT['complemento'] : null,
            ]);
            $dados['success'] = true;
            echo json_encode($dados);

            $cliente = Cliente::with('enderecos')->find($_PUT['id']);
            $dados['cliente'] = $this->jsonClienteUpdateStorage($cliente);
        }
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
    public function addObservacao(Request $request, $cliente)
    {
        $data = date('d-m-Y', strtotime($request->data_obs));
        dd($request->all());
        InfoCliente::create([
            'data' => $request->data_obs ?  $data : null,
            'observacao' => $request->observacao,
            'cliente_id' => $cliente,
        ]);
        Session::flash('clienteAddObs', "Adicionado Com Sucesso!!");
        return redirect()->back();
    }

    private function jsonClienteUpdateStorage($cliente)
    {

        $dados['id'] = $cliente->id;
        $dados['alltech_id'] = "-" . $cliente->alltech_id;
        $dados['loja_id'] = $cliente->loja_id;
        $dados['loja_alltech_id'] = $cliente->loja->alltech_id;
        $dados['nome'] = $cliente->nome;
        $dados['docto'] = $cliente->docto;
        $dados['tipo'] = $cliente->tipo;
        $dados['email'] = $cliente->email;
        $dados['fone1'] = $cliente->fone1;
        $dados['fone2'] = $cliente->fone2;
        $dados['celular'] = $cliente->celular;
        $dados['cidade_ibge'] = $cliente->enderecos->cidadeIbge->codigo;
        $dados['cep'] = $cliente->enderecos->cep;
        $dados['bairro'] = $cliente->enderecos->bairro;
        $dados['rua'] = $cliente->enderecos->rua;
        $dados['numero'] = $cliente->enderecos->numero;
        $dados['compto'] = $cliente->enderecos->compto;
        $dados['tipo'] = $cliente->enderecos->tipo;
        // Storage::disk('local')->put($file, Storage::disk('ftp')->get($file));
        $file = json_encode($dados);
        $dir = $cliente->loja->empresa->pasta;
        Storage::disk('local')->makeDirectory($dir);
        $files = Storage::disk('local')->files($dir);
        if (count($files) == 0) {
            dd(Storage::put($dir . '/CLIENTE-1.json', $file));
        } else {
            foreach ($files as $key => $file) {
                if (str_contains($file, 'CLIENTE-')) {
                    $files[] = $file;
                    Storage::put($dir . '/CLIENTE-' . count($files) . '.json', $file);
                }
            }
        }

        // $count = Storage::get(str_contains($file, '-CLIENTE-'));

        return $dados;
    }
}
