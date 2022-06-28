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


class ExportaClienteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $json;
    private $dir;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($json, $dir)
    {
        $this->json = $json;
        $this->dir = $dir;
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

        if (count($files) == 0) {

            Storage::put($this->dir . '/CLIENTE-' . $count . '.json', $this->json);
            $file = $this->dir . '/CLIENTE-' . $count . '.json';
        } else {

            foreach ($files as $key => $file) {
                $arquivoBanco = $this->dir . '/CLIENTE-' . $count . '.json';
                if ($this->filePermited($empresa, $file, $arquivoBanco)) {
                    $count++;
                }
            }

            Storage::put($this->dir . '/CLIENTE-' . $count . '.json', $this->json);
            $file = $this->dir . '/CLIENTE-' . $count . '.json';
        }

        //pega da storage local e exporta para ftp
        Storage::disk('ftp')->put($file, Storage::get($file));

        $empresa->arquivosExp()->create(['nome' => $file, 'processado' => 1]);

        $empresa->update(['ultima_sincronizacao' => now()]);
        $empresa->logs()->create(['log' => "[Cliente] Exportado Pasta/dir = {$file}"]);
    }

    private function filePermited($empresa, $file, $arquivoBanco)
    {
        return (str_contains($file, 'CLIENTE') and $empresa->arquivosExp()->where('nome', $arquivoBanco)->first());
    }
}
