<?php
require __DIR__ . '/../vendor/autoload.php';
// Load dotenv if available
if (class_exists(\Dotenv\Dotenv::class)) {
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
}
echo "ROUTING_SERVICE_URL=" . (getenv('ROUTING_SERVICE_URL') ?: '<null>') . PHP_EOL;
echo "NLP_SERVICE_URL=" . (getenv('NLP_SERVICE_URL') ?: '<null>') . PHP_EOL;
echo "CONFIG_SERVICES_AI=" . json_encode(require __DIR__ . '/../config/services.php') . PHP_EOL;
