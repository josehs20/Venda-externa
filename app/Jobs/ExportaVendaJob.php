<?php

namespace App\Jobs;

use App\Models\Empresa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ExportaVendaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $json;
    private $dir;
    private $carrinho;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($json, $dir, $carrinho)
    {
        $this->json = $json;
        $this->dir = $dir;
        $this->carrinho = $carrinho;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $empresa = Empresa::where('pasta', $this->dir)->first();

        Storage::disk('local')->makeDirectory($this->dir);

        $files = Storage::disk('local')->files($this->dir);

        $count = 1;

        //numeração de loja para nomear arquivo
        $num = "000";
        $num .= $this->carrinho->usuario->loja->alltech_id;
        $num = substr($num, -3);

        if (count($files) == 0) {

            Storage::put($this->dir . '/VONLINE-' . $count . '-' . $num . '.json', $this->json);
            $file = $this->dir . '/VONLINE-' . $count . '-' . $num . '.json';
        } else {

            foreach ($files as $key => $file) {
                $diretorioBanco = $this->dir . '/VONLINE-' . $count . '-' . $num . '.json';
                $fileStorage = '/VONLINE-' . $count . '-' . $num;
                if ($this->filePermited($empresa, $file, $fileStorage, $diretorioBanco)) {
                    $count++;
                }
                //dd($count);
            }
            Storage::put($this->dir . '/VONLINE-' . $count . '-' . $num . '.json', $this->json);
            $file = $this->dir . '/VONLINE-' . $count . '-' . $num . '.json';
        }

        //monta diretorio da empresa no ftp caso não tenha 
        Storage::disk('ftp')->makeDirectory($this->dir);

        //pega da storage local e exporta para ftp
        Storage::disk('ftp')->put($file, Storage::get($file));

        $empresa->arquivosExp()->create(['nome' => $file, 'processado' => 1]);

        $empresa->update(['ultima_sincronizacao' => now()]);
        $empresa->logs()->create(['log' => "[Venda] Exportado Pasta/dir = {$file}"]);
    }

    private function filePermited($empresa, $file, $fileStorage, $diretorioBanco)
    {
        return (str_contains($file, $fileStorage) and $empresa->arquivosExp()->where('nome', $diretorioBanco)->first());
    }
}
