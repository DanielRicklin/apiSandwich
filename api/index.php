<?php
require_once  __DIR__ . '/../src/vendor/autoload.php';
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \lbs\control\Categoriescontroller;
use \lbs\control\Sandwichcontroller;

// initialisation connection
$config = parse_ini_file('../src/conf/lbs.db.conf.ini');
$db = new Illuminate\Database\Capsule\Manager();
$db->addConnection( $config );
$db->setAsGlobal();
$db->bootEloquent();

$config = [
    'settings'=>[
        'displayErrorDetails'=>true
    ]
];

$errors = require_once '../src/error/errors.php';

$configs = array_merge($config, $errors);
$c = new \Slim\Container($configs);
$app = new \Slim\App($c);
$c = $app->getContainer();


$app->get('/categories[/]', Categoriescontroller::class . ':getCategories')->setName("categories");
$app->get('/categorie/{name}[/]', Categoriescontroller::class . ':getCategorie')->setName("categorie");
$app->put('/categorie/{id}[/]', Categoriescontroller::class . ':updateCategorie')->setName("categorieUpdate");
$app->post('/categories[/]', Categoriescontroller::class . ':createCategorie')->setName("createCategorie");
$app->get('/sandwichs[/]', Sandwichcontroller::class . ':getSandwichs')->setName("sandwichsListe");
//$app->get('/sandwich/{id}', Sandwichcontroller::class . ':getSandwich')->setName("sandwichsLink");

$app->get('/sandwich/{id}', function(Request $req, Response $resp, $args){
    $ctrl=new Sandwichcontroller($this);
    return $ctrl->getSandwich($args,$resp);
})->setName('sandwichsLink');

$app->run();