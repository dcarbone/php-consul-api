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
