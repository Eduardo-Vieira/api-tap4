<?php

namespace App\Controllers;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;



use \App\Helpers\ImgBase64;

class FrameworksController extends \App\Helpers\Auth {
    
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function list($request, $response, $args) {

        $decoded = $this->auth($request->getHeaders());

        if (isset($decoded)) {
            try {            
                   $sql = "SELECT 
                                tb_frameworks.id_frameworks, 
                                tb_frameworks.id_liguagem,
                                tb_liguagens.tx_liguagem,
                                tb_frameworks.tx_nome, 
                                tb_frameworks.tx_site, 
                                tb_frameworks.tx_ano, 
                                tb_frameworks.tx_criador, 
                                tb_frameworks.tx_versao, 
                                tb_frameworks.id_tipos, 
                                tb_tipos.tx_tipos,
                                tb_frameworks.tx_opniao,
                                tb_frameworks.tx_pros, 
                                tb_frameworks.tx_contra, 
                                tb_frameworks.tx_urlimg
                            FROM tb_frameworks,tb_liguagens,tb_tipos
                            WHERE tb_frameworks.id_liguagem = tb_liguagens.id_liguagem
                            AND tb_frameworks.id_tipos= tb_tipos.id_tipos";

                   ($args['id_frameworks'])? $sql .= " AND tb_frameworks.id_frameworks = :id_frameworks":false;
                   $sql .= " order by tb_frameworks.id_frameworks;";
                   
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
        
        $decoded = $this->auth($request->getHeaders());

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
        
        $decoded = $this->auth($request->getHeaders());

        if (isset($decoded)) {
            try {
                $body = $request->getParsedBody();
                // Insert
                if($args['type'] == 'add'){
                    // upload de imagem
                    $imgResult = ImgBase64::saveBase64Image($body['tx_urlimg']['value'], $body['tx_urlimg']['nameFile'],$_SERVER['DOCUMENT_ROOT'].'/uploads/frameworks/');
                    
                    $body['tx_urlimg'] = '/uploads/frameworks/'.$imgResult;
                    
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