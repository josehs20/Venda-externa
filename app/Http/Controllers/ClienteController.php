<?php

namespace App\Http\Controllers;

use App\Jobs\ExportaClienteJob;
use App\Models\Carrinho;
use App\Models\CidadeIbge;
use App\Models\Cliente;
use App\Models\InfoCliente;
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
    public function index(Request $request, $msg = null)
    {
        $clientes = Cliente::with('infoCliente', 'enderecos')->where('loja_id', auth()->user()->loja_id)
            ->whereRaw("nome like '%{$request->nome}%'")->orderBy('nome')->paginate(30);

        return view('cliente.index', compact('clientes', 'msg'));
    }
    public function busca_cliente_ajax()
    {
        $nome = $_GET['nome'];
        $codigo = $_GET['codigo'];
        if ($nome) {
            $dados['nome'] = Cliente::where('loja_id', auth()->user()->loja_id)->whereRaw("nome like '%{$_GET['nome']}%'")->take(10)->get();
        }
        if ($codigo) {
            $dados['codigo'] = Cliente::where('loja_id', auth()->user()->loja_id)->where("alltech_id", $_GET['codigo'])->first();
        }
        echo json_encode($dados);
        return;
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
            'alltech_id' => $_POST['documento'],
            'nome' => $_POST['nome'],
            'docto' => $_POST['documento'],
            'tipo' =>  strlen($_POST['documento']) == 11 ? 'F' : 'J',
            'email' => $_POST['email'],
            'fone1' => strlen($_POST['telefones'][0]) > 7 ? $_POST['telefones'][0] : null,
            'fone2' => strlen($_POST['telefones'][1]) > 7 ? $_POST['telefones'][1] : null,
            'celular' => strlen($_POST['telefones'][2]) > 7 ? $_POST['telefones'][2] : null,
        ]);
        $cliente->enderecos()->create([
            'cidade_ibge_id' => $codIbge->id ? $codIbge->id : null,
            'cep' => preg_replace("/[^0-9]/", "", $_POST['cep']),
            'bairro' => $_POST['bairro'],
            'rua' => $_POST['rua']  ? $_POST['rua']  : null,
            'numero' => $_POST['numero'] ? intval($_POST['numero']) : null,
            'compto' => $_POST['complemento'] ? $_POST['complemento'] : null,
        ]);
        $dados['success'] = true;
        echo json_encode($dados);

        $this->jsonClienteStorageJob($cliente);
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
        //    $file = Storage::disk('local')->files('28825657000107');
        //    $a = Storage::get($file[0]);
        //     dd(json_decode($a));
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
            $this->jsonClienteStorageJob($cliente);
        }
        return;
    }

    //Faz o json, envia para storage para assim ser enviado para o ftp
    public function jsonClienteStorageJob($cliente)
    {
        $dados['id'] = $cliente->id;
        $dados['alltech_id'] = strlen($cliente->alltech_id)  >= '11' ? $cliente->alltech_id : "-" . $cliente->alltech_id;
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
        $dados['cidade'] = $cliente->enderecos->cidadeIbge->nome;
        $dados['uf'] = $cliente->enderecos->cidadeIbge->uf;
        $dados['cep'] = $cliente->enderecos->cep;
        $dados['bairro'] = $cliente->enderecos->bairro;
        $dados['rua'] = $cliente->enderecos->rua;
        $dados['numero'] = $cliente->enderecos->numero;
        $dados['compto'] = $cliente->enderecos->compto;
        $dados['tipo'] = $cliente->enderecos->tipo;

        $json = json_encode($dados);
        //dd($json);
        $dir = $cliente->loja->empresa->pasta;
        Storage::disk('local')->makeDirectory($dir);
        $files = Storage::disk('local')->files($dir);
        $count = 1;

        if (count($files) == 0) {

            Storage::put($dir . '/CLIENTE-' . $count . '.json', $json);
            $file = $dir . '/CLIENTE-' . $count . '.json';
        } else {

            foreach ($files as $key => $file) {
                if (str_contains($file, 'CLIENTE')) {
                    $count++;
                }
            }

            Storage::put($dir . '/CLIENTE-' . $count . '.json', $json);
            $file = $dir . '/CLIENTE-' . $count . '.json';
        }
        
        //Class de Jobs para exportação 
        //ExportaClienteJob::dispatch($file, $dir);
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

    public function venda_salva()
    {

        $carrinhos_Salvos = Carrinho::with('carItem')->where('user_id', auth()->user()->id)->where('status', 'Salvo')->get();
        foreach ($carrinhos_Salvos as $key => $carrinho) {
            $carrinho['somaItens'] = $carrinho->carItem()->selectRaw("sum(preco * quantidade) total")->get();
        }
        $cliente_carrinho = Carrinho::where('user_id', auth()->user()->id)->where('status', 'Aberto')->first();

        $clientes_user = Cliente::where('loja_id', auth()->user()->loja_id)->orderBy('nome')->take(100)->get();

        Session::flash('itens_salvo');

        return view('cliente.vendaSalva', compact('carrinhos_Salvos', 'cliente_carrinho', 'clientes_user'));
    }

    public function substitui_carrinho(Request $request, $carrinho)
    {
        $carrinho_substituido = Carrinho::find($carrinho);
        // dd($request->all());
        if ($request->deleteCarrinho) {
            $carrinho_substituido->delete();
            Session::flash('deleta_carrinho');

            return redirect(route('venda_salva'));
        } elseif ($request->substituir) {
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
        Session::flash('success');

        return redirect(route('itens_carrinho', ['user_id' => auth()->user()->id, 'msg' => 'Itens Substituídos Com Sucesso']));
    }

    public function add_observacao(Request $request, $cliente)
    {
        $data = date('d-m-Y', strtotime($request->data_obs));
        // dd($request->all());

        InfoCliente::create([
            'data' => $request->data_obs ?  $data : null,
            'observacao' => $request->observacao,
            'cliente_id' => $cliente,
        ]);
        Session::flash('Add_Obs');
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
