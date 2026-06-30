<?php

namespace App\Foundation\Bootstrap;

use Dotenv\Dotenv;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables as BaseLoadEnvironmentVariables;
use Illuminate\Support\Env;

class LoadEnvironmentVariables extends BaseLoadEnvironmentVariables
{
    /**
     * @return Dotenv
     */
    protected function createDotenv($app)
    {
        $path = $app->environmentPath();
        $primary = $app->environmentFile();

        $names = [$primary];
        $local = $this->localCompanionFile($primary);
        if ($local !== null && is_file($path.DIRECTORY_SEPARATOR.$local)) {
            $names[] = $local;
        }

        $shortCircuit = count($names) === 1;

        return Dotenv::create(
            Env::getRepository(),
            $path,
            $names,
            $shortCircuit
        );
    }

    /**
     * Nombre del fichero de overrides locales (p. ej. .env + .env.local).
     */
    private function localCompanionFile(string $primary): ?string
    {
        if ($primary === '.env') {
            return '.env.local';
        }

        if (str_ends_with($primary, '.env')) {
            return $primary.'.local';
        }

        return null;
    }
}
