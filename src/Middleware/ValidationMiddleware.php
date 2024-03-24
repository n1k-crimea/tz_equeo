<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ValidationMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $uploadedFiles = $request->getUploadedFiles();
        $pdf = $uploadedFiles['pdf'] ?? null;

        if (!$pdf || $pdf->getClientMediaType() !== 'application/pdf') {
            $response = new Response();
            $response->getBody()->write(json_encode(['error' => 'Invalid file type. Only PDFs are allowed.']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        return $handler->handle($request);
    }
}
