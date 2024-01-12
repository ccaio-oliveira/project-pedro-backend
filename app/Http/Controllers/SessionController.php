<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
    //

    public function create($data){
        foreach($data as $key => $value){
            session()->put($key, $value);
        }
    }

    public function readAll(){
        return session()->all();
    }

    public function readItem($item){
        return session()->get($item);
    }

    public function verify($item){
        if(session()->has($item)){
            return session()->get($item);
        } else {
            return false;
        }
    }

    public function remove($item){
        session()->remove($item);
    }

    public function clearSession(){
        session()->flush();
    }
}
