<?php

namespace App\Helpers;

use \Firebase\JWT\JWT;

class Auth
{
    public function auth($jwt){
        try {
            return JWT::decode($jwt['HTTP_AUTHORIZATION'][0], $this->container->key, array('HS256'));
        } catch (UnexpectedValueException $e) {
            return ["code"=>500,"data"=>["error"=>$e->getMessage()],"mensage"=>"Erro token"];
        }
    }    
}