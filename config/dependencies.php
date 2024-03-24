<?php

declare(strict_types=1);

use App\Services\{FileUploadServiceInterface, GoogleDriveUploader};
use Google\Client;
use Google\Service\Drive;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

return function (ContainerInterface $container) {
    $container->set('settings', function () {
        return [
            'logger' => [
                'name' => 'app',
                'path' => __DIR__ . '/../logs/app.log',
                'level' => Logger::WARNING,
            ],
        ];
    });

    $container->set(Logger::class, function (ContainerInterface $container) {
        $settings = $container->get('settings')['logger'];
        $logger = new Logger($settings['name']);
        $logger->pushHandler(new StreamHandler($settings['path'], $settings['level']));
        return $logger;
    });

    $container->set(Client::class, function () {
        $client = new Client();
        $client->setAuthConfig(__DIR__ . '/..' . $_ENV['GOOGLE_APPLICATION_CREDENTIALS']);
        $client->addScope(Drive::DRIVE_FILE);
        return $client;
    });

    $container->set(FileUploadServiceInterface::class, function (ContainerInterface $container) {
        return new GoogleDriveUploader($container->get(Client::class));
    });
};
