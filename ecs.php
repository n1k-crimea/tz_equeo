<?php

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths([__DIR__ . '/src', __DIR__ . '/public', __DIR__ . '/config'])
    ->withRules([
        ArraySyntaxFixer::class,
    ])
    ->withPreparedSets(psr12: true, strict: true);