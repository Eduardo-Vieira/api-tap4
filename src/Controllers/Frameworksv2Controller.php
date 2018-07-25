<?php

namespace App\Controllers;
use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;

class Frameworksv2Controller
{
   
    protected $container;

    public function __construct(      
        Container $container
    ) {
        $this->container = $container;
        //ORM open instance db
        $container->ORM;
    }

    public function index(Request $request, Response $response, $args)
    {

        $data = \App\Models\Frameworks::where('id_frameworks', $args['id_frameworks'])->firstOrFail();

        return $response->withJson(["code"=>200,
                                    "data"=>  $data,
                                    "mensage"=>"sucesso!",
                                    "args" => $args
                                   ]);
    }
}