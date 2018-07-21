<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

// A middleware for enabling CORS
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});

/**
 * jwt 
 */
$app->add(new Tuupola\Middleware\JwtAuthentication([
    "path" => ["/api", "/admin"],
    "header" => "Authorization",
    "regexp" => "/(.*)/",
    "ignore" => ["/api/auth", "/admin/ping"],
    "secret" => "testeTap4api",
    "error" => function ($response, $arguments) {
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response
            ->withHeader("Content-Type", "application/json")
            ->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
   
]));