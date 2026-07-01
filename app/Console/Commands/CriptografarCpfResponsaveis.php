<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class CriptografarCpfResponsaveis extends Command
{
    protected $signature = 'cpf:criptografar';
    protected $description = 'Criptografa os CPFs de responsáveis que ainda estão em texto puro no banco';

    public function handle()
    {
        $registros = DB::table('responsavel')->get();

        $totalCriptografados = 0;
        $totalJaCriptografados = 0;
        $totalVazios = 0;

        foreach ($registros as $registro) {

            $cpfAtual = $registro->resp_cpf;

            if (empty($cpfAtual)) {
                $totalVazios++;
                continue;
            }

            // TENTA DESCRIPTOGRAFAR. SE CONSEGUIR, JÁ ESTÁ CRIPTOGRAFADO.
            try {
                Crypt::decryptString($cpfAtual);
                $totalJaCriptografados++;
                continue;
            } catch (DecryptException $e) {
                // Não está criptografado ainda — segue o fluxo abaixo
            }

            $cpfLimpo = preg_replace('/\D/', '', $cpfAtual);

            DB::table('responsavel')
                ->where('id_responsavel', $registro->id_responsavel)
                ->update([
                    'resp_cpf' => Crypt::encryptString($cpfLimpo)
                ]);

            $totalCriptografados++;
        }

        $this->info("Criptografados agora: {$totalCriptografados}");
        $this->info("Já estavam criptografados: {$totalJaCriptografados}");
        $this->info("Vazios/ignorados: {$totalVazios}");

        return Command::SUCCESS;
    }
}