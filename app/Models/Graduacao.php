<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Graduacao extends Model
{
    protected $table = 'graduacao';
    protected $primaryKey = 'id_graduacao';

    protected $fillable = [
        'gradu_nome_cor',
        'gradu_grau'
    ];
}
