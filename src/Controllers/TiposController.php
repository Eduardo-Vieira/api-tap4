<?php

namespace App\Controllers;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

use \Firebase\JWT\JWT;

class TiposController {
    
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
                   $sql = "SELECT id_tipos, tx_tipos FROM mydb.tb_tipos WHERE 1=1";                       
                   ($args['id_tipos'])? $sql .= " AND id_tipos = :id_tipos":false;
                   $db = $this->container->db;
                   $stmt = $db->prepare($sql);                       
                   if(count($args) > 0){
                       ($args['id_tipos'])? $stmt->bindParam(":id_tipos", $args['id_tipos']):false;
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
}