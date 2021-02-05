<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Tools\PHPCSFixer\Fixer\FQCN;

use AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo;
use AdamWojs\PhpCsFixerPhpdocForceFQCN\FQCN\FQCNResolver;

/**
 * Class Resolver
 */
final class Resolver extends FQCNResolver
{
    /** @var \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo */
    protected NamespaceInfo $nsi;

    /**
     * Resolver constructor.
     * @param \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo $namespaceInfo
     */
    public function __construct(NamespaceInfo $namespaceInfo)
    {
        parent::__construct($namespaceInfo);
        $this->nsi = $namespaceInfo;
    }

    /**
     * @param string $className
     * @return string
     */
    public function resolveFQCN(string $className): string
    {
        $out = parent::resolveFQCN($className);
        if (0 === \strpos($out, '\\')) {
            return $out;
        }

        return \sprintf('\\%s\\%s', \trim($this->nsi->getName(), '\\'), \trim($className, '\\'));
    }
}
