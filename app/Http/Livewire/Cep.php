<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Cep extends Component
{

    public $validCep;
    public $validCidade;

    public $cep;
    public $cidade;
    public $bairro;
    public $rua;
    public $uf;

    public function updatedCidade()
    {
        $dados = curl_init("https://viacep.com.br/ws/$this->uf/$this->cidade/true/json/");
        curl_setopt($dados, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($dados));
        curl_close($dados);

        if ($result) {
            
            $this->cep = $result[0]->cep;
            $this->validCidade = "";
        } else {
            $this->validCidade = "Inválido";
        }
    }

    public function updatedCep()
    {
        $dados = curl_init("https://viacep.com.br/ws/$this->cep/json/");
        curl_setopt($dados, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($dados));
        curl_close($dados);

        if ($result) {
            try {
                $this->cidade = $result->localidade;
                $this->bairro = $result->bairro;
                $this->rua = $result->logradouro;
                $this->uf = $result->uf;
                $this->validCep = "";
            } catch (\Throwable $th) {
                false;
            }
        } else {
            //dd($result);
            $this->validCep = "Cep Inválido";
        }
        // dd($result);
        // dd($this->updatedDocs());
    }

    public function render()
    {
        return view('livewire.cep');
    }
}
