<?php declare(strict_types=1);

$autoload = getenv('EXTENSION_TEST_AUTOLOAD') ?: dirname(__DIR__) . '/vendor/autoload.php';

if (!is_file($autoload)) {
    throw new RuntimeException(sprintf('Composer autoload not found: %s', $autoload));
}

$loader = require $autoload;
$loader->addPsr4('Tests\\', __DIR__);
