<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Tools\PHPCSFixer\Fixer\FQCN;

/*
  Based on https://github.com/adamwojs/php-cs-fixer-phpdoc-force-fqcn

  Copyright 2021 Daniel Carbone (daniel.p.carbone@gmail.com)

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
 */

use AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo;
use AdamWojs\PhpCsFixerPhpdocForceFQCN\FQCN\FQCNTypeNormalizer;

/**
 * Class Normalizer
 */
final class Normalizer extends FQCNTypeNormalizer
{
    public const BUILD_IN_TYPES = FQCNTypeNormalizer::BUILD_IN_TYPES + [17 => 'static', 18 => '$this'];

    private const DOLLAR       = '$';
    private const SLASH        = '\\';
    private const DUMB_DOLLAR  = self::SLASH . self::DOLLAR;
    private const MIXED_FORMAT = 'mixed %s';
    private const ARR          = '[]';

    /**
     * @param \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo $namespaceInfo
     * @param string $type
     * @return string
     */
    public function normalizeType(NamespaceInfo $namespaceInfo, string $type): string
    {
        if (self::ARR === \substr($type, -2)) {
            return $this->normalizeType($namespaceInfo, \substr($type, 0, -2)) . self::ARR;
        }

        // if this is a built-in type, already has a namespace prefix, or is merely empty, move on.
        if (\in_array($type, self::BUILD_IN_TYPES, true) ||
            0 === \strpos($type, self::SLASH) ||
            '' === \trim($type)) {
            return $type;
        }

        // if this is a variable...
        if (0 === \strpos($type, self::DOLLAR)) {
            // ...otherwise set to 'mixed' and assume i'll fix it later
            return \sprintf(self::MIXED_FORMAT, $type);
        }

        // if this is a dumb thing, clean it up
        if (0 === \strpos($type, self::DUMB_DOLLAR)) {
            return \sprintf(self::MIXED_FORMAT, \ltrim($type, self::SLASH));
        }

        // in all other cases, try to find an ns prefix
        return (new Resolver($namespaceInfo))->resolveFQCN($type);
    }
}
