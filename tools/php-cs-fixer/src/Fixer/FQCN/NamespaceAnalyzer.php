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

use AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\ImportInfo;
use AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo;
use AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\Range;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\Tokenizer\TokensAnalyzer;

/**
 * Class NamespaceAnalyzer
 */
class NamespaceAnalyzer
{
    /** @var \PhpCsFixer\Tokenizer\Tokens */
    private Tokens $tokens;

    /**
     * @param \PhpCsFixer\Tokenizer\Tokens $tokens
     */
    public function __construct(Tokens $tokens)
    {
        $this->tokens = $tokens;
    }

    /**
     * @return \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo[]
     */
    public function getNamespaces(): array
    {
        $namespaces = [];

        $ns = $this->getLocalNamespace();
        if (null !== $ns) {
            $namespaces[] = $ns;
        }

        $imports = $this->getImportsPerNamespace();

        if (empty($imports)) {
            if ([] === $namespaces) {
                // Global namespace without imports
                return [
                    new NamespaceInfo(
                        '',
                        new Range(0, $this->tokens->count()),
                        []
                    ),
                ];
            }
            return $namespaces;
        }

        \reset($this->tokens);
        foreach ($this->tokens as $index => $token) {
            if (!$token->isGivenKind(\T_NAMESPACE)) {
                continue;
            }

            $declarationStartIndex = $index;
            $declarationEndIndex   = $this->tokens->getNextTokenOfKind($index, [';', '{']);

            $namespaceName = \trim(
                $this->tokens->generatePartialCode(
                    $declarationStartIndex + 1,
                    $declarationEndIndex - 1
                )
            );

            $scope = $this->getNamespaceScope($declarationEndIndex);

            $namespaceImports = [];
            foreach ($imports as $shortName => $import) {
                if ($scope->inRange($import->getDeclaration()->getStartIndex())) {
                    $namespaceImports[$shortName] = $import;
                    unset($imports[$shortName]);
                }
            }

            $namespaces[] = new NamespaceInfo(
                $namespaceName,
                $scope,
                $namespaceImports
            );
        }

        if (!empty($imports)) {
            $namespaces[] = new NamespaceInfo(
                '',
                $this->getNamespaceScope(\reset($imports)->getDeclaration()->getStartIndex()),
                $imports
            );
        }

        return $namespaces;
    }

    /**
     * @return \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo|null
     */
    private function getLocalNamespace(): ?NamespaceInfo
    {
        // find namespace declaration
        /** @var \PhpCsFixer\Tokenizer\Token[] $tkns */
        $tkns = $this->tokens->findGivenKind(\T_NAMESPACE);
        if ([] === $tkns) {
            return null;
        }

        // find starting token
        $start = \key($tkns);

        // find next ';'
        $end = $this->tokens->getNextTokenOfKind($start, [';', [\T_CLOSE_TAG]]);
        if (null === $end) {
            return null;
        }

        // find all strings between $start and $end
        $nsTokens = $this->tokens->findGivenKind(\T_STRING, $start, $end);
        if ([] === $nsTokens) {
            return null;
        }

        // be lazy
        $keys    = \array_keys($nsTokens);
        $nsStart = $keys[0];
        $nsEnd   = $keys[\count($keys) - 1];

        // get get everything between and including $start and $end of string tokens
        $localNS = $this->tokens->generatePartialCode($nsStart, $nsEnd);

        // define range as this file
        // todo: support multi-namespace files
        $range = new Range(0, $this->tokens->count());

        // construct nsinfo object
        return new NamespaceInfo(
            $localNS,
            $range,
            [],
        );
    }

    /**
     * Based on \PhpCsFixer\Fixer\Import\NoUnusedImportsFixer::getNamespaceUseDeclarations
     *
     * @return \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\ImportInfo[]
     */
    private function getImportsPerNamespace(): array
    {
        $tokenAnalyzer = new TokensAnalyzer($this->tokens);

        $imports = [];
        foreach ($tokenAnalyzer->getImportUseIndexes() as $declarationStartIndex) {
            $declarationEndIndex = $this->tokens->getNextTokenOfKind($declarationStartIndex, [';', [\T_CLOSE_TAG]]);
            $declarationContent  = $this->tokens->generatePartialCode(
                $declarationStartIndex + 1,
                $declarationEndIndex - 1,
            );

            if (false !== \strpos($declarationContent, ',')) {
                // ignore multiple use statements that should be split into few separate statements
                // (for example: `use BarB, BarC as C;`)
                continue;
            }

            if (false !== \strpos($declarationContent, '{')) {
                // do not touch group use declarations until the logic of this is added
                // (for example: `use some\a\{ClassD};`)
                continue;
            }

            $declarationParts = \preg_split('/\s+as\s+/i', $declarationContent);

            if (1 === \count($declarationParts)) {
                $fullName         = $declarationContent;
                $declarationParts = \explode('\\', $fullName);
                $shortName        = \end($declarationParts);
                $isAliased        = false;
            } else {
                [$fullName, $shortName] = $declarationParts;
                $declarationParts       = \explode('\\', $fullName);
                $isAliased              = $shortName !== \end($declarationParts);
            }

            $fullName  = \trim($fullName);
            $shortName = \trim($shortName);

            $imports[$shortName] = new ImportInfo(
                $fullName,
                $shortName,
                $isAliased,
                new Range(
                    $declarationStartIndex,
                    $declarationEndIndex
                )
            );
        }

        return $imports;
    }

    /**
     * Returns scope of the namespace.
     *
     * @param int $startIndex Start index of the namespace declaration
     *
     * @return \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\Range
     */
    private function getNamespaceScope(int $startIndex): Range
    {
        $endIndex = null;
        if ($this->tokens[$startIndex]->isGivenKind('{')) {
            $endIndex = $this->tokens->findBlockEnd(Tokens::BLOCK_TYPE_CURLY_BRACE, $startIndex);
        } else {
            $nextNamespace = $this->tokens->getNextTokenOfKind($startIndex, [\T_NAMESPACE]);
            if (!empty($nextNamespace)) {
                $endIndex = $nextNamespace[0];
            }
        }

        return new Range($startIndex, $endIndex);
    }
}
