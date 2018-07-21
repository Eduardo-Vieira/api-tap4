<?php

namespace App\Controllers;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

use \Firebase\JWT\JWT;

class LinguagensController {
    
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function list($request, $response, $args) {
        $jwt = $request->getHeaders();
       
        try {
            $decoded = JWT::decode($jwt['HTTP_AUTHORIZATION'][0], $this->container->key, array('HS256'));
        } catch (UnexpectedValueException $e) {
            return $response->withJson(["code"=>500,"data"=>["error"=>$e->getMessage()],"mensage"=>"Erro token"]);
        }
        if (isset($decoded)) {
            try {            
                   $sql = "SELECT id_liguagem, tx_liguagem FROM tb_liguagens WHERE 1=1";                       
                   ($args['id_liguagem'])? $sql .= " AND id_liguagem = :id_liguagem":false;
                   $db = $this->container->db;
                   $stmt = $db->prepare($sql);                       
                   if(count($args) > 0){
                       ($args['id_liguagem'])? $stmt->bindParam(":id_liguagem", $args['id_liguagem']):false;
                   }                       
                   $stmt->execute();
                   $data = $stmt->fetchAll();
                   $db = null;
                   return $response->withJson(["code"=>200,
                                               "data"=>  $data,
                                               "mensage"=>"sucesso!",
                                               "args" => $args
                                               ]);
            } catch (PDOException $e) {
                return $response->withJson(["code"=>500,"data"=>["error"=>$e->getMessage()],"mensage"=>"Erro de query"]);
            }
        }
    }
    
    public function delete($request, $response, $args) {

    }

    public function save($request, $response, $args) {
        $jwt = $request->getHeaders();
       
        try {
            $decoded = JWT::decode($jwt['HTTP_AUTHORIZATION'][0], $this->container->key, array('HS256'));
        } catch (UnexpectedValueException $e) {
            return $response->withJson(["code"=>500,"data"=>["error"=>$e->getMessage()],"mensage"=>"Erro token"]);
        }
        if (isset($decoded)) {
            
        }
    }

    

}