<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        'no_unused_imports' => true,
        'ordered_imports' => ['sortAlgorithm' => 'length'],
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setUsingCache(false)
    ->setFinder($finder);