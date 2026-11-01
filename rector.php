<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector;
use Rector\CodingStyle\Rector\ClassLike\NewlineBetweenClassLikeStmtsRector;
use Rector\CodingStyle\Rector\ClassMethod\NewlineBeforeNewAssignSetRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\TypeDeclaration\Rector\StmtsAwareInterface\SafeDeclareStrictTypesRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withBootstrapFiles([
        __DIR__ . '/vendor/autoload.php',
    ])
    ->withPhpSets(php82: true)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
    )
    ->withSkip([
        // keep explicit class property declarations instead of constructor promotion
        ClassPropertyAssignToConstructorPromotionRector::class,
        // do not force blank lines between class constants / properties
        NewlineBetweenClassLikeStmtsRector::class,
        // do not force blank lines between statements
        NewlineAfterStatementRector::class,
        NewlineBeforeNewAssignSetRector::class,
        // prefer explicit null comparisons over fully-qualified instanceof checks
        ExplicitNullableObjectInstanceRector::class,
        // treats ArrayAccess reads (e.g. `$_ = $obj[0];`) as side-effect free
        RemoveUnusedVariableAssignRector::class,
        // PHPUnit test classes must not declare strict types
        SafeDeclareStrictTypesRector::class => [
            __DIR__ . '/tests',
        ],
    ]);
