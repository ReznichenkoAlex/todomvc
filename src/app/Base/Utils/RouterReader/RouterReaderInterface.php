<?php

namespace App\Base\Utils\RouterReader;

interface RouterReaderInterface
{
    public function parseRoutes(string $path): array;
}