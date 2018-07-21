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
$app->post('/api/auth', 'App\Controllers\AuthController:auth');

// Lista de liguagens
$app->get('/api/linguagens/list/[{id_liguagem}]','App\Controllers\LinguagensController:list');
