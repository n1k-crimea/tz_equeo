<?php

declare(strict_types=1);

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Exception;

class GoogleDriveUploader implements FileUploadServiceInterface
{
    protected Drive $service;

    public function __construct(Client $client)
    {
        $this->service = new Drive($client);
    }

    /**
     * @param string[] $file
     * @return string
     * @throws Exception
     */
    public function uploadFile(array $file): string
    {
        $fileMetadata = new DriveFile([
            'name' => $file['name']
        ]);

        $content = file_get_contents($file['tmp_name']);
        $driveResponse = $this->service->files->create($fileMetadata, [
            'data' => $content,
            'mimeType' => 'application/pdf',
            'uploadType' => 'multipart',
            'fields' => 'id'
        ]);

        return 'https://drive.google.com/uc?id=' . $driveResponse->id;
    }
}
