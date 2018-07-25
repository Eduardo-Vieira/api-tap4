<?php

namespace App\Controllers;

use Slim\Container;
use Slim\Http\Request;
use Slim\Http\Response;
use \App\Models\Frameworks;

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
        //Verificar se a url tem agumentos
        if($args['id_frameworks']){
            $data = Frameworks::where('id_frameworks', $args['id_frameworks'])->firstOrFail();
        } else{
            $data = Frameworks::all();
        }
        return $response->withJson(["code"=>200,
                                    "data"=>  $data,
                                    "mensage"=>"sucesso!",
                                    "args" => $args
                                   ]);
    }
}