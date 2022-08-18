<?php

$finder = PhpCsFixer\Finder::create()
    ->in('src')
    ->append([__FILE__])
;

$config = new PhpCsFixer\Config();

return $config
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder)
;
