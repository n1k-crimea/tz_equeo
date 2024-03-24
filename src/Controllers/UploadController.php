<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\FileUploadServiceInterface;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UploadController
{
    private FileUploadServiceInterface $fileUploadService;
    private Logger $logger;

    public function __construct(FileUploadServiceInterface $fileUploadService, Logger $logger)
    {
        $this->fileUploadService = $fileUploadService;
        $this->logger = $logger;
    }

    public function upload(Request $request, Response $response): Response
    {
        try {
            $uploadedFiles = $request->getUploadedFiles();
            $pdf = $uploadedFiles['pdf'];

            $url = $this->fileUploadService->uploadFile([
                'name' => $pdf->getClientFilename(),
                'tmp_name' => $pdf->getFilePath()
            ]);

            $response->getBody()->write(json_encode(['url' => $url]));

            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $this->logger->error('Upload failed: ' . $e->getMessage());
            $response->getBody()->write(json_encode(['error' => 'File upload failed.']));

            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}
