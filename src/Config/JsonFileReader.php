<?php

declare(strict_types=1);

namespace Carvago\Mrqe\Config;

class JsonFileReader
{
    private const FILE_LOCATION = './config.json';

    public function getContent(): string
    {
        return file_get_contents(self::FILE_LOCATION);
    }
}