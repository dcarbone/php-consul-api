<?php declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use AdamWojs\PhpCsFixerPhpdocForceFQCN\Fixer\Phpdoc\ForceFQCNFixer;
use PhpCsFixer\Config;
use PhpCsFixer\RuleSet\Sets\PSR12RiskySet;
use PhpCsFixer\RuleSet\Sets\PSR12Set;

$config = new Config('php-consul-api');

$config
    ->setUsingCache(false)
    ->setRiskyAllowed(true)
    ->setLineEnding("\n")
    ->setIndent('    ')
    ->registerCustomFixers(
        [
            new ForceFQCNFixer(),
        ]
    )
    ->setRules(
        (new PSR12Set())->getRules() +
        (new PSR12RiskySet())->getRules() +
        [
            // custom rules

            'AdamWojs/phpdoc_force_fqcn_fixer' => true,

            // php-cs-fixer builtins:
            'self_static_accessor' => true,
        ]
    );

return $config;
