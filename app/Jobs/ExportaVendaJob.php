<?php

namespace App\Jobs;

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

    private $file;
    private $dir;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file, $dir)
    {
        $this->file = $file;
        $this->dir = $dir;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
         //monta diretorio da empresa no ftp caso não tenha 
         Storage::disk('ftp')->makeDirectory($this->dir);

         //pega da storage local e exporta para ftp
         Storage::disk('ftp')->put($this->file, Storage::get($this->file));
    }
}
