<?php

declare(strict_types=1);

use App\Controllers\UploadController;
use App\Middleware\ValidationMiddleware;
use App\Services\FileUploadServiceInterface;
use DI\Container;
use Dotenv\Dotenv;
use Monolog\Logger;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$container = new Container();
(require __DIR__ . '/../config/dependencies.php')($container);

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->post('/upload', function ($request, $response) use ($container) {
    $uploadController = new UploadController(
        $container->get(FileUploadServiceInterface::class),
        $container->get(Logger::class)
    );
    return $uploadController->upload($request, $response);
})->add(new ValidationMiddleware());

$app->any('/{routes:.+}', function ($request, $response) {
    $data = ['error' => 'not found'];
    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json')
        ->withStatus(404);
});

$app->run();
