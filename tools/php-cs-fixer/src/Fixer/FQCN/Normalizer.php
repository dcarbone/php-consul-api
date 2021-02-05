<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Tools\PHPCSFixer\Fixer\FQCN;

use AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo;
use AdamWojs\PhpCsFixerPhpdocForceFQCN\FQCN\FQCNTypeNormalizer;

/**
 * Class Normalizer
 */
final class Normalizer extends FQCNTypeNormalizer
{
    /**
     * @param \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo $namespaceInfo
     * @param string $type
     * @return string
     */
    public function normalizeType(NamespaceInfo $namespaceInfo, string $type): string
    {
        if ('[]' === \substr($type, -2)) {
            return $this->normalizeType($namespaceInfo, \substr($type, 0, -2)) . '[]';
        }

        if (\in_array($type, self::BUILD_IN_TYPES, true) || 0 === \strpos($type, '\\')) {
            return $type;
        }

        return (new Resolver($namespaceInfo))->resolveFQCN($type);
    }
}
