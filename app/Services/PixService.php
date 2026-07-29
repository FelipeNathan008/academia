<?php

namespace App\Services;

class PixService
{
    public static function gerar(
        string $chave,
        string $nome,
        string $cidade,
        float $valor,
        string $txid = '***'
    ): string {

        $nome = self::normalizar($nome);
        $cidade = self::normalizar($cidade);

        $payload =
            self::campo('00', '01') .
            self::campo('26',
                self::campo('00', 'BR.GOV.BCB.PIX') .
                self::campo('01', $chave)
            ) .
            self::campo('52', '0000') .
            self::campo('53', '986') .
            self::campo('54', number_format($valor, 2, '.', '')) .
            self::campo('58', 'BR') .
            self::campo('59', substr($nome, 0, 25)) .
            self::campo('60', substr($cidade, 0, 15)) .
            self::campo('62',
                self::campo('05', substr($txid, 0, 25))
            );

        $payload .= '6304';
        $payload .= self::crc16($payload);

        return $payload;
    }

    private static function campo(string $id, string $valor): string
    {
        return $id .
            str_pad(strlen($valor), 2, '0', STR_PAD_LEFT) .
            $valor;
    }

    private static function normalizar(string $texto): string
    {
        $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto);

        return strtoupper(
            preg_replace('/[^A-Za-z0-9 ]/', '', $texto)
        );
    }

    private static function crc16(string $payload): string
    {
        $polinomio = 0x1021;
        $resultado = 0xFFFF;

        foreach (str_split($payload) as $caractere) {

            $resultado ^= (ord($caractere) << 8);

            for ($i = 0; $i < 8; $i++) {

                if (($resultado << 0) & 0x8000) {
                    $resultado = (($resultado << 1) ^ $polinomio);
                } else {
                    $resultado <<= 1;
                }

                $resultado &= 0xFFFF;
            }
        }

        return strtoupper(str_pad(dechex($resultado), 4, '0', STR_PAD_LEFT));
    }
}