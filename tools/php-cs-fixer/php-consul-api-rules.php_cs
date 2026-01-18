<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use AdamWojs\PhpCsFixerPhpdocForceFQCN\Fixer\Phpdoc\ForceFQCNFixer;
use PhpCsFixer\Config;

$config = new Config('php-consul-api');

$config
    ->setUsingCache(false)
//    ->setRiskyAllowed(true)
    ->setLineEnding("\n")
    ->setIndent('    ')
    ->registerCustomFixers(
        [
            new ForceFQCNFixer(),
        ]
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
