<?php

declare(strict_types=1);

require __DIR__ . "/../../vendor/autoload.php";

use Framework\App;
use App\Config\Paths;
use Dotenv\Dotenv;
use function App\Config\{registerRoute, registerMiddleware};

$dotenv = Dotenv::createImmutable(Paths::ROOT)->load();

$app = new App(Paths::SOURCE . "App/container-definitions.php");

registerRoute($app);
registerMiddleware($app);

return $app;
