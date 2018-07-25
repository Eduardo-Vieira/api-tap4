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

// rota liguagens
$app->group('/api/linguagens', function() {
    $this->get('/list[/{id_liguagem}]','App\Controllers\LinguagensController:list');
    $this->post('/save/{type}','App\Controllers\LinguagensController:save');
    $this->delete('/delete/{id_liguagem}','App\Controllers\LinguagensController:delete');
});

// rota frameworks
$app->group('/api/frameworks', function() {
    $this->get('/list[/{id_frameworks}]','App\Controllers\FrameworksController:list');
    $this->post('/save/{type}','App\Controllers\FrameworksController:save');
    $this->delete('/delete/{id_frameworks}','App\Controllers\FrameworksController:delete');
});

// rota Tipos
$app->group('/api/tipos', function() {
    $this->get('/list[/{id_tipos}]','App\Controllers\TiposController:list');
});

// rota frameworks com ORM  Eloquent
$app->group('/api/v2', function() {
    $this->get('/frameworksv2[/{id_frameworks}]','App\Controllers\Frameworksv2Controller:index');
});