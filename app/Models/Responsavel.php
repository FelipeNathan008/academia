<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Responsavel extends Model
{
    protected $table = 'responsavel';
    protected $primaryKey = 'id_responsavel';

    protected $fillable = [
        'resp_nome',
        'resp_parentesco',
        'resp_cpf',
        'resp_cep',
        'resp_telefone',
        'resp_email',
        'resp_logradouro',
        'resp_numero',
        'resp_complemento',
        'resp_bairro',
        'resp_cidade',
        'id_emp_id'
    ];


    // CPF: CRIPTOGRAFA AO SALVAR

    public function setRespCpfAttribute($value)
    {
        if (!$value) {
            $this->attributes['resp_cpf'] = null;
            return;
        }

        $cpfLimpo = preg_replace('/\D/', '', $value);

        $this->attributes['resp_cpf'] = Crypt::encryptString($cpfLimpo);
    }


    // CPF: DESCRIPTOGRAFA AO LER
    public function getRespCpfAttribute($value)
    {
        if (!$value) {
            return null;
        }

        try {
            return Crypt::decryptString($value);
        } catch (DecryptException $e) {
            // Fallback para dados antigos não criptografados (se existirem)
            return $value;
        }
    }

    // CPF MASCARADO (***23)
    public function getRespCpfMascaradoAttribute()
    {
        $cpf = $this->resp_cpf; // já descriptografado pelo accessor getRespCpfAttribute

        if (!$cpf || strlen($cpf) < 11) {
            return '-';
        }

        $final = substr($cpf, -2);

        return "***.***.***-{$final}";
    }


    public function alunos()
    {
        return $this->hasMany(Aluno::class, 'responsavel_id_responsavel', 'id_responsavel');
    }

    protected static function booted()
    {
        static::addGlobalScope('empresa', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('id_emp_id', Auth::user()->id_emp_id);
            }
        });
    }
}
