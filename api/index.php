<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';
spl_autoload_register(function ($classname) {
    require ("classes/" . $classname . ".php");
});

$config['displayErrorDetails'] = true;

$config['db']['host']   = "localhost:8889";
$config['db']['user']   = "root";
$config['db']['pass']   = "root";
$config['db']['dbname'] = "tradesy_app";

$app = new \Slim\App(["settings" => $config]);

$container = $app->getContainer();
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

$app->get('/users', function(Request $request, Response $response) {
    $mapper = new UserMapper($this->db);
    $users = $mapper->getUsers();

    $response->getBody()->write(json_encode($users));
    return $response;
});

$app->get('/users/{id}', function (Request $request, Response $response, $args) {
    $user_id = (int)$args['id'];
    $mapper = new UserMapper($this->db);
    $user = $mapper->getUserById($user_id);

    $response->getBody()->write(json_encode($user));
    return $response;
});

$app->post('/users', function (Request $request, Response $response) {
    $mapper = new UserMapper($this->db);

    $data = $request->getParsedBody();
    $user_data = [];
    $user_data['first_name'] = filter_var($data['user']['first_name'], FILTER_SANITIZE_STRING);
    $user_data['last_name'] = filter_var($data['user']['last_name'], FILTER_SANITIZE_STRING);
    $user_data['email'] = filter_var($data['user']['email'], FILTER_SANITIZE_STRING);

    $res = $mapper->addUser($user_data);

    $response->getBody()->write(json_encode($res));
    return $response;
});

$app->delete('/users/{id}', function(Request $request, Response $response, $args) {
    $user_id = (int)$args['id'];
    $mapper = new UserMapper($this->db);
    $mapper->deleteUser($user_id);

    $response->withStatus(204);
    return $response;    
});

$app->get('/products', function(Request $request, Response $response) {
    $mapper = new ProductMapper($this->db);
    $products = $mapper->getProducts();

    $response->getBody()->write(json_encode($products));
    return $response;
});

$app->get('/products/{id}', function (Request $request, Response $response, $args) {
    $product_id = (int)$args['id'];
    $mapper = new ProductMapper($this->db);
    $product = $mapper->getProductById($product_id);

    $response->getBody()->write(json_encode($product));
    return $response;
});

$app->post('/products', function (Request $request, Response $response) {
    $mapper = new ProductMapper($this->db);

    $data = $request->getParsedBody();
    $product_data = [];
    $product_data['user_id'] = filter_var($data['product']['user_id'], FILTER_SANITIZE_STRING);
    $product_data['name'] = filter_var($data['product']['name'], FILTER_SANITIZE_STRING);
    $product_data['photo'] = filter_var($data['product']['photo'], FILTER_SANITIZE_STRING);
    $product_data['category'] = filter_var($data['product']['category'], FILTER_SANITIZE_STRING);
    $product_data['brand'] = filter_var($data['product']['brand'], FILTER_SANITIZE_STRING);
    $product_data['original_price'] = filter_var($data['product']['original_price'], FILTER_SANITIZE_STRING);
    $product_data['asking_price'] = filter_var($data['product']['asking_price'], FILTER_SANITIZE_STRING);

    $res = $mapper->addProduct($product_data);

    $response->getBody()->write(json_encode($res));
    return $response;
});

$app->run();