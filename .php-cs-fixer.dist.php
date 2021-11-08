<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src');

$config = new PhpCsFixer\Config();
return $config->setRules([
    '@PHP74Migration' => true,
    '@PSR12' => true,
])->setFinder($finder);
