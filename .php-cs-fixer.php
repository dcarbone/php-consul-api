<?php

declare(strict_types=1);

require_once __DIR__ . '/tools/php-cs-fixer/vendor/autoload.php';

use AdamWojs\PhpCsFixerPhpdocForceFQCN\Fixer\Phpdoc\ForceFQCNFixer;
use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$config = new Config('php-consul-api');

$config
    ->setUsingCache(false)
//    ->setRiskyAllowed(true)
    ->setLineEnding("\n")
    ->setIndent('    ')
    ->registerCustomFixers(
        fixers: [
            new ForceFQCNFixer(),
        ]
    )
    ->setFinder(
        Finder::create()
            ->in(__DIR__ . '/src')
            ->name('*.php')
    )
    ->setRules(
        [
            '@PSR12' => true,
//            'PSR12Risky',

            // custom rules

            'AdamWojs/phpdoc_force_fqcn_fixer' => true,

            // php-cs-fixer builtins:
            'self_static_accessor' => true,
        ]
    );

return $config;
