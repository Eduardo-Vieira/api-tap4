<?php

use Slim\Http\Request;
use Slim\Http\Response;

use \Firebase\JWT\JWT;

// Routes

$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

// Authenticate route.
$app->post('/api/auth', function (Request $request, Response $response) {
    
    $data = $request->getParsedBody();
    // consulta usuário no banco
    $sql = "SELECT * FROM tb_users
            WHERE tx_login = :tx_login AND tx_password =:tx_password";
    $db = $this->db;
    $stmt = $db->prepare($sql);
    $stmt->bindParam("tx_login", $data['tx_login']);
    $stmt->bindParam("tx_password", $data['tx_password']);
    $stmt->execute();
    $current_user = $stmt->fetchObject();
    $db = null;

    if (!isset($current_user)) {
        echo json_encode("No user found");
    } else {
        // Find a corresponding token.
        $sql = "SELECT * FROM tb_tokens
            WHERE id_user = :id_user AND date_expiration >" . time();
        $token_from_db = false;
        try {
            $db = $this->db;
            $stmt = $db->prepare($sql);
            $stmt->bindParam("id_user", $current_user->id_user);
            $stmt->execute();
            $token_from_db = $stmt->fetchObject();
            $db = null;
            if ($token_from_db) {
                echo json_encode([
                    "token"      => $token_from_db->tx_values,
                    "tx_login" => $token_from_db->user_id
                ]);
            }
        } catch (PDOException $e) {
            return $response->withJson(["code"=>500,"data"=>["error"=>$e->getMessage()],"mensage"=>"Erro na pesquisa do token"]);
        }
        // Create a new token if a user is found but not a token corresponding to whom.
        if (count($current_user) != 0 && !$token_from_db) {
            $key = "testeTap4api";
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
                $jwt = JWT::encode($payload, $key);
            } catch (Exception $e) {
                return $response->withJson(["code"=>500,"data"=>["error"=>$e->getMessage()],"mensage"=>"Erro ao gerar o token"]);
            }
            $sql = "INSERT INTO tb_tokens (id_user, tx_values, date_created, date_expiration)
                        VALUES (:id_user, :tx_value, :date_created, :date_expiration)";
            try {
                $db = $this->db;
                $stmt = $db->prepare($sql);
                $stmt->bindParam("id_user", $current_user->id_user);
                $stmt->bindParam("tx_values", $jwt);
                $stmt->bindParam("date_created", $payload['iat']);
                $stmt->bindParam("date_expiration", $payload['exp']);
                $stmt->execute();
                $db = null;
                return $response->withJson(["code"=>200,
                                            "data"=>[
                                                "token"      => $jwt,
                                                "user_login" => $current_user->tx_login],
                                            "mensage"=>"sucesso!"
                                            ]);
            } catch (PDOException $e) {
                return $response->withJson(["code"=>500,"data"=>["error"=>$e->getMessage()],"mensage"=>"Erro ao Inserir o token"]);
            }
        }
    }
});

// The route to get a secured data.
$app->get('/api/restricted', function (Request $request, Response $response) {
    $jwt = $request->getHeaders();
    $key = "testeTap4api";
    try {
        $decoded = JWT::decode($jwt['HTTP_AUTHORIZATION'][0], $key, array('HS256'));
    } catch (UnexpectedValueException $e) {
        echo $e->getMessage();
    }
    if (isset($decoded)) {
        $sql = "SELECT * FROM tb_tokens WHERE id_user = :id_user";
        try {
            $db = $this->db;
            $stmt = $db->prepare($sql);
            $stmt->bindParam("id_user", $decoded->context->user->id_user);
            $stmt->execute();
            $user_from_db = $stmt->fetchObject();
            $db = null;
            if (isset($user_from_db->id_user)) {
                echo json_encode([
                    "response" => "This is your secure resource !"
                ]);
            }
        } catch (PDOException $e) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }
});