<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
return [
    'notFoundHandler'=> function($c){
        return function($req,$resp){
            $tab_err = [
                "type"=>"error",
                "error"=>400,
                "message"=>"URI non traitée"
            ];
            $resp = $resp->withHeader('Content-Type', 'application/json');
            $resp->withStatus(400)
                ->getBody()
                ->write(json_encode($tab_err));
            return $resp; 
        };
    },
    'notAllowedHandler'=>function($c){
        return function($req, $resp,$methods){
            $tab_err = [
                "type"=>"error",
                "error"=>405,
                "message"=>"méthode permises :".implode(',', $methods)
            ];
            $resp = $resp->withHeader('Content-Type', 'application/json');
            $resp->withStatus(405)
                ->withHeader('Allow', implode(',', $methods))
                ->getBody()
                ->write(json_encode($tab_err));
            return $resp; 
        };
    },
    /*'phpErrorHandler'=>function($c){
        return function ($req, $resp, $error){
            $tab_err = [
                "type"=>"error",
                "error"=>500,
                "message"=>"Something went wrong! phperror"
            ];
            $resp = $resp->withHeader('Content-Type', 'application/json');
            $resp->withStatus(500)
                ->getBody()
                ->write(json_encode($tab_err));
            return $resp; 
        };
    }*/
];