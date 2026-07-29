<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DetalhesMensalidade;
use Illuminate\Support\Facades\Storage;

class LimparComprovantesMensalidade extends Command
{

    protected $signature = 'app:limpar-comprovantes-mensalidade';

    protected $description = 'Command description';

    public function handle()
    {
        $limite = now()->subDays(30);

        $detalhes = DetalhesMensalidade::whereNotNull('det_mensa_comprovante_path')
            ->where('det_mensa_comprovante_enviado_em', '<=', $limite)
            ->get();

        foreach ($detalhes as $detalhe) {
            if (Storage::disk('local')->exists($detalhe->det_mensa_comprovante_path)) {
                Storage::disk('local')->delete($detalhe->det_mensa_comprovante_path);
            }

            $detalhe->update([
                'det_mensa_comprovante_path' => null,
                'det_mensa_comprovante_enviado_em' => null,
                'det_mensa_status' => 'Em aberto',
            ]);
        }

        $this->info('Comprovantes antigos removidos.');
    }
}
