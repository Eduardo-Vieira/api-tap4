<?php

namespace App\Controllers;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

use \Firebase\JWT\JWT;

class FrameworksController {
    
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
                   $sql = "SELECT id_frameworks, id_liguagem, tx_nome, tx_site, tx_ano, tx_criador, 
                                  tx_versao, id_tipos, tx_opniao, tx_pros, tx_contra, tx_urlimg 
                            FROM tb_frameworks WHERE 1=1";

                   ($args['id_frameworks'])? $sql .= " AND id_frameworks = :id_frameworks":false;
                   $db = $this->container->db;
                   $stmt = $db->prepare($sql);                       
                   if(count($args) > 0){
                       ($args['id_frameworks'])? $stmt->bindParam(":id_frameworks", $args['id_frameworks']):false;
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
                $sql = "DELETE FROM tb_frameworks WHERE id_frameworks = :id_frameworks";
                $db = $this->container->db;
                $stmt = $db->prepare($sql);  
                $stmt->bindParam(":id_frameworks", $args['id_frameworks']);
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
                    $sql = "INSERT INTO tb_frameworks ($fieldNames) VALUES ($fieldValues)";                
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
                    $sql = "UPDATE tb_frameworks SET $fieldDetails WHERE id_frameworks=:id_frameworks";
                    $db = $this->container->db;
                    $stmt = $db->prepare($sql);
                    foreach ($body as $key => $value) {
                        $stmt->bindValue(":$key", $value);
                    }
                    $data = $stmt->execute();
                    $db = null;
                }
                return $response->withJson(["code"=>200,
                                            "data"=>  'OK',
                                            "mensage"=>"Registro salvo com sucesso!",
                                            "args" => $args
                                            ]);

            } catch (PDOException $e) {
                return $response->withJson(["code"=>500,"data"=>["error"=>$e->getMessage()],"mensage"=>"Erro de query"]);
            }
        }
    }

}