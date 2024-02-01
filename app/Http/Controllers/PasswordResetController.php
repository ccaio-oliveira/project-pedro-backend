<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    protected $passwordReset;

    public function __construct(){
        $this->passwordReset = new PasswordReset();
    }

    public function insertToken($email, $token){
        $this->passwordReset->email = $email;
        $this->passwordReset->token = $token;
        $this->passwordReset->save();
    }

    public function getTokenByEmail($email){
        $token = $this->passwordReset->where('email', $email)->first();
        return $token;
    }

    public function deleteToken($email){
        $this->passwordReset->where('email', $email)->delete();
    }

    public function checkToken($token){
        $token = $this->passwordReset
        ->where('token', $token)
        ->where('created_at', '>', date('Y-m-d H:i:s', strtotime('-1 hour')))
        ->first();
        return $token;
    }
}
