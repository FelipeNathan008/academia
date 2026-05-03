<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'id_emp_id',
        'id_filial_id',
        'role',
        'professor_id',
        'responsavel_id'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_emp_id');
    }

    public function filial()
    {
        return $this->belongsTo(Filial::class, 'id_filial_id');
    }
    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_id', 'id_professor');
    }

    public function responsavel()
    {
        return $this->belongsTo(Responsavel::class, 'esponsavel_id', 'id_responsavel');
    }
}
