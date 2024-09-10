<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Middleware\BodyParsingMiddleware;


require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

// This is for routing middleware
$app->addRoutingMiddleware();

// This is for parsing JSON & form data
$app->addBodyParsingMiddleware();

// This is for handling middleware optional but useful for debugging
$app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Welcome to money grow!");
    return $response;
});

$app->run();