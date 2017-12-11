<?php

namespace lbs\control;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \lbs\model\Categories;

class Categoriescontroller {
    public $container = null;

    public function __construct($c){
        $this->container = $c;
    }
    /**
     * @param Request $req
     * @param Response $resp
     * @param [type] $args
     * @return void
     */
    public function getCategories(Request $req, Response $resp,$args){
        $categories = Categories::get();
        $resp = $resp->withHeader('Content-Type','application/json');
        $resp->getBody()->write(json_encode($categories));

        return $resp;
    }
    /**
     * @param Request $req
     * @param Response $resp
     * @param [type] $args
     * @return void
     */
    public function getCategorie(Request $req, Response $resp,$args){
        $id=$args['name'];
        $categorie = Categories::find($id);

        $resp = $resp->withHeader('Content-Type','application/json');

        $resp->getBody()->write(json_encode($categorie));
        return $resp;
    }
    /**
     * @param Request $req
     * @param Response $resp
     * @param array $args
     * @return void
     */
    public function createCategorie(Request $req, Response $resp, array $args){
        $postVar=$req->getParsedBody();
        $categorie = new Categories();
        $categorie->nom=filter_var($postVar['nom'],FILTER_SANITIZE_STRING);
        $categorie->description=filter_var($postVar['description'],FILTER_SANITIZE_STRING);
        $categorie->save();
        $resp=$resp->withHeader('Content-Type','application/json')
            ->withStatus(201)
            ->withHeader('Location', '/categories/nouvelle');
        $resp->getBody()->write('created');
        return $resp;
    }
    /**
     * @param Request $req
     * @param Response $rs
     * @param array $args
     * @return void
     */
    public function updateCategorie(Request $req, Response $rs, array $args){
    	$id=$args['id'];
    	$postVar=$req->getParsedBody();
    	$categorie = Categories::find($id);
    	if($categorie){
    		if (!is_null($postVar['nom']) && !is_null($postVar['description'])){
		    	$categorie->nom = filter_var($postVar['nom'],FILTER_SANITIZE_STRING);
		    	$categorie->description= filter_var($postVar['description'],FILTER_SANITIZE_STRING);
		    	$categorie->save();
		    	$rs=$rs->withHeader('Content-Type','application/json')
		    	->withStatus(200)
		    	->withHeader('Location', '/categories/update');
		    	$rs->getBody()->write($categorie);
    		}
    		else{
    			$rs=$rs->withStatus(400);
    			$rs->getBody()->write('Bad request');
    		}
    	}
    	else{
    		$rs=$rs->withStatus(404);
    		$rs->getBody()->write('not found');
    	}
    	return $rs;
    }
}