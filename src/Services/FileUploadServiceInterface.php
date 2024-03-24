<?php

declare(strict_types=1);

namespace App\Services;

interface FileUploadServiceInterface
{
    public function uploadFile(array $file): string;
}
