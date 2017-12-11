<?php

namespace lbs\control;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \lbs\model\Sandwich;

class Sandwichcontroller {
    public $container = null;

    public function __construct($c){
        $this->container = $c;
    }
    /**
     * @param Request $req
     * @param Response $resp
     * @param array $args
     * @return void
     */
    public function getSandwichs(Request $req,Response $resp,array $args){
        $type_pain = $req->getQueryParam('t',null);
        $img = $req->getQueryParam('img',null);
        $size = $req->getQueryParam('s',10);
        $page = $req->getQueryParam('p',1);
        $date = date("d-m-Y");

        $skip = $size*($page-1);

        $requeteSand = Sandwich::select('id','nom','type_pain');
        if(!is_null($type_pain)){
          $requeteSand=$requeteSand->where('type_pain','LIKE','%'.$type_pain.'%');
        }
        if(!is_null($img)){
          $requeteSand=$requeteSand->where('img','=',$img);
        }
        
        $totalPage = $requeteSand->get();
        $total = sizeof($totalPage);
        
        $Items = $size + $skip;
        
        if($Items>$total){
            if(is_float($total/$size)){
              $page=floor(($total/$size))+1;
            }else{
              $page=floor(($total/$size));
            }
          }
        
        if($page<0){
            $page=1;
        }
        
        $skip = $size*($page-1);
        $requeteSand=$requeteSand->skip($skip)->take($size);
        $listeSandwichs = $requeteSand->get();
        
        $resp=$resp->withHeader('Content-Type','application/json');
        $resp=$resp->withHeader('Count',$totalPage);
        $resp=$resp->withHeader('Size',$size);
        $resp=$resp->withHeader('Page',$page);
        $sandwichs['type']="collection";
        $sandwichs['meta']['count']=$total;
        $sandwichs['meta']['data']=$date;
        for($i=0;$i<sizeof($listeSandwichs);$i++){
          $sandwichs['sandwichs'][$i]["sandwich"]=$listeSandwichs[$i];
          $href["href"]=$this->container->get('router')->pathFor('sandwichsLink', ['id'=>$listeSandwichs[$i]['id']]);
          $tab["self"]=$href;
          $sandwichs['sandwichs'][$i]["links"]=$tab;
        }
        $resp->getBody()->write(json_encode($sandwichs));
        return $resp;
    }

  /**
   * @param array $args
   * @param Response $resp
   * @return void
   */
    public function getSandwich(array $args,Response $resp){
      $id=$args['id'];
      $sandwich = Sandwich::find($id);
      $resp = $resp->withHeader('Content-Type','application/json');
      $resp->getBody()->write(json_encode($sandwich));
      return $resp;
    }
}