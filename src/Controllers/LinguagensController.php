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
        $jwt = $request->getHeaders();       
        try {
            $decoded = JWT::decode($jwt['HTTP_AUTHORIZATION'][0], $this->container->key, array('HS256'));
        } catch (UnexpectedValueException $e) {
            return $response->withJson(["code"=>500,"data"=>["error"=>$e->getMessage()],"mensage"=>"Erro token"]);
        }
        if (isset($decoded)) {
            try {
                $sql = "DELETE FROM tb_liguagens WHERE id_liguagem = :id_liguagem";
                $db = $this->container->db;
                $stmt = $db->prepare($sql);  
                $stmt->bindParam(":id_liguagem", $args['id_liguagem']);
                $data = $stmt->execute();
                $db = null;
                return $response->withJson(["code"=>200,
                                            "data"=>  $data,
                                            "mensage"=>"Registro excluido com sucesso!",
                                            "args" => $args
                                            ]);

            } catch (PDOException $e) {
                return $response->withJson(["code"=>500,"data"=>["error"=>$e->getMessage()],"mensage"=>"Erro de query"]);
            }
        }
    }

    public function save($request, $response, $args) {
        $jwt = $request->getHeaders();       
        try {
            $decoded = JWT::decode($jwt['HTTP_AUTHORIZATION'][0], $this->container->key, array('HS256'));
        } catch (UnexpectedValueException $e) {
            return $response->withJson(["code"=>500,"data"=>["error"=>$e->getMessage()],"mensage"=>"Erro token"]);
        }
        if (isset($decoded)) {
            try {
                $body = $request->getParsedBody();
                // Insert
                if($args['type'] == 'add'){
                    $fieldNames = implode(',', array_keys($body));
                    foreach ($body as $key => $value) {
                        $fieldValues .= ":$key,";
                    }                
                    $fieldValues = rtrim($fieldValues, ',');                
                    $sql = "INSERT INTO tb_liguagens ($fieldNames) VALUES ($fieldValues)";                
                    $db = $this->container->db;
                    $stmt = $db->prepare($sql);
                    foreach ($body as $key => $value) {
                        $stmt->bindValue(":$key", $value);
                    }
                    $data = $stmt->execute();
                    $db = null;
                }else{
                    //update               
                    ksort($body);
                    $fieldDetails = null;
                    foreach ($body as $key => $value) {
                        $fieldDetails .= "$key=:$key,";
                    }
                    $fieldDetails = rtrim($fieldDetails, ',');
                    $sql = "UPDATE tb_liguagens SET $fieldDetails WHERE id_liguagem=:id_liguagem";
                    $db = $this->container->db;
                    $stmt = $db->prepare($sql);
                    foreach ($body as $key => $value) {
                        $stmt->bindValue(":$key", $value);
                    }
                    $data = $stmt->execute();
                    $db = null;
                }
                return $response->withJson(["code"=>200,
                                            "data"=>   $sql,
                                            "mensage"=>"Registro salvo com sucesso!",
                                            "args" => $args
                                            ]);

            } catch (PDOException $e) {
                return $response->withJson(["code"=>500,"data"=>["error"=>$e->getMessage()],"mensage"=>"Erro de query"]);
            }
        }
    }

}