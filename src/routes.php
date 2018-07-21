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

// Authenticate api.
$app->post('/api/auth', 'App\Controllers\AuthController:auth');

// Lista de liguagens
$app->group('/api/linguagens', function() {
    $this->get('/list[/{id_liguagem}]','App\Controllers\LinguagensController:list');
    $this->post('/save/{type}','App\Controllers\LinguagensController:save');
    $this->delete('/delete/{id_liguagem}','App\Controllers\LinguagensController:delete');
});