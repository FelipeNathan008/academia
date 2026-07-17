<?php
// app/Traits/ProcessaFotoTrait.php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Laravel\Facades\Image;

trait ProcessaFotoTrait
{
    /**
     * Redimensiona e recorta a imagem em formato quadrado (padrão do sistema),
     * salva como .jpg otimizado e retorna o nome do arquivo gerado.
     *
     * @param UploadedFile $file
     * @param string $pasta Ex: 'alunos'
     * @param int $tamanho Lado do quadrado final (px)
     * @return string Nome do arquivo salvo
     */
    private function processarFoto(UploadedFile $file, string $pasta, int $tamanho = 300): string
    {
        $destino = public_path("images/{$pasta}");

        if (!is_dir($destino)) {
            mkdir($destino, 0755, true);
        }

        $filename = uniqid() . '_' . time() . '.jpg';

        // Abre a imagem, recorta em quadrado (centralizado) e redimensiona
        $img = Image::read($file)
            ->cover($tamanho, $tamanho) // corta e redimensiona mantendo proporção, sem distorcer
            ->toJpeg(80); // qualidade 80% = bom equilíbrio entre nitidez e peso

        $img->save($destino . '/' . $filename);

        return $filename;
    }

    /**
     * Remove o arquivo de foto antigo, se existir.
     */
    private function removerFoto(string $pasta, ?string $filename): void
    {
        if ($filename) {
            $caminho = public_path("images/{$pasta}/{$filename}");
            if (file_exists($caminho)) {
                unlink($caminho);
            }
        }
    }
}