<?php
namespace App\Controllers;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

use \Firebase\JWT\JWT;

class AuthController {
    
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function auth($request, $response, $args) {
        $data = $request->getParsedBody();
        // consulta usuário no banco
        $sql = "SELECT * FROM tb_users
                WHERE tx_login = :tx_login AND tx_password =:tx_password";
        $db = $this->container->db;
        $stmt = $db->prepare($sql);
        $stmt->bindParam("tx_login", $data['tx_login']);
        $stmt->bindParam("tx_password", $data['tx_password']);
        $stmt->execute();
        $current_user = $stmt->fetchObject();
        $db = null;
        if (!$current_user) {
            return $response->withJson(["code"=>401,"data"=>$current_user,"mensage"=>"Não autorizado: acesso negado devido a credenciais inválidas."]);die;
        } else {
            // Find a corresponding token.
            $sql = "SELECT * FROM tb_tokens
                WHERE id_user = :id_user AND date_expiration >" . time();
            $token_from_db = false;
            try {
                $db = $this->container->db;
                $stmt = $db->prepare($sql);
                $stmt->bindParam("id_user", $current_user->id_user);
                $stmt->execute();
                $token_from_db = $stmt->fetchObject();
                $db = null;
                if ($token_from_db) {
                    return $response->withJson(["code"=>200,
                                                "data"=>[
                                                    "token"      => $token_from_db->tx_values,
                                                    "user_login" => $current_user->tx_login,
                                                    "date_created" => $token_from_db->date_created,
                                                    "date_expiration" => $token_from_db->date_expiration,
                                                    "id_user" => $token_from_db->id_user,],
                                                "mensage"=>"sucesso!"
                                                ]);
                }
            } catch (PDOException $e) {
                return $response->withJson(["code"=>500,"data"=>["error"=>$e->getMessage()],"mensage"=>"Erro na pesquisa do token"]);
            }
            // Create a new token if a user is found but not a token corresponding to whom.
            if (count($current_user) != 0 && !$token_from_db) {
               
                $payload = array(
                    "iss"     => "http://tap4.com",
                    "iat"     => time(),
                    "exp"     => time() + (3600 * 24 * 15),
                    "context" => [
                        "user" => [
                            "user_login" => $current_user->tx_login,
                            "id_user"    => $current_user->id_user
                        ]
                    ]
                );
                try {
                    $jwt = JWT::encode($payload,  $this->container->key);
                } catch (Exception $e) {
                    return $response->withJson(["code"=>500,"data"=>["error"=>$e->getMessage()],"mensage"=>"Erro ao gerar o token"]);
                }
                $sql = "INSERT INTO tb_tokens (id_user, tx_values, date_created, date_expiration)
                            VALUES (:id_user, :tx_values, :date_created, :date_expiration)";
                try {
                    $db = $this->container->db;
                    $stmt = $db->prepare($sql);
                    $stmt->bindValue(":id_user", $current_user->id_user);
                    $stmt->bindValue(":tx_values", $jwt);
                    $stmt->bindValue(":date_created", $payload['iat']);
                    $stmt->bindValue(":date_expiration", $payload['exp']);
                    $stmt->execute();
                    $db = null;
                    return $response->withJson(["code"=>200,
                                                "data"=>[
                                                    "token"      => $jwt,
                                                    "user_login" => $current_user->tx_login,
                                                    "date_created" => $payload['iat'],
                                                    "date_expiration" => $payload['exp'],
                                                    "id_user" => $current_user->id_user],
                                                "mensage"=>"sucesso!"
                                                ]);
                } catch (PDOException $e) {
                    return $response->withJson(["code"=>500,"data"=>["error"=>$e->getMessage()],"mensage"=>"Erro ao Inserir o token"]);
                }
            }
        }
    }
}