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
    }

    public function index(Request $request, Response $response, $args)
    {
        $ORM = $this->container->ORM;

        $data = $ORM->table('tb_frameworks')->where('id_frameworks','=',$args['id_frameworks'])->get();

        return $response->withJson(["code"=>200,
                                    "data"=>  $data,
                                    "mensage"=>"sucesso!",
                                    "args" => $args
                                   ]);
    }
}