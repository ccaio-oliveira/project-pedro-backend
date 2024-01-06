<?php

namespace App\Models\Utils\Usuario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Login extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $priarykey = 'id';

    protected $connection = 'mysql';
    protected $table = 'usuarios_login';

    public $timestamps = false;

    protected $fillable = [
        'usuario_login',
        'senha_login',
        'usuario_id',
        'ultimo_acesso',
        'remember_token'
    ];

    protected $hidden = [
        'senha',
        'remember_token',
    ];

    /**
     * Get the password for the user.
     *
     * @return string
    */

    public function getAuthPassword()
    {
        return 'senha_login';
    }

    /**
     * Retorna a hash de login
     *
    */
    public function getLoginByHash(string $hash){
        return $this->select()->where('hash_login', $hash)->get();
    }
}
