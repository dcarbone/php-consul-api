<?php declare(strict_types=1);

namespace DCarbone\PHPConsulAPI\Tools\PHPCSFixer\Fixer\FQCN;

use AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo;
use AdamWojs\PhpCsFixerPhpdocForceFQCN\Fixer\Phpdoc\ForceFQCNFixer as BaseForceFQCNFixer;
use PhpCsFixer\DocBlock\Annotation;
use PhpCsFixer\DocBlock\DocBlock;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;

/**
 * Class ForceFQCNFixer
 */
class ForceFQCNFixer extends BaseForceFQCNFixer
{
    public const NAME = 'PHPConsulAPI/fqcn_fixer';
    /** @var \DCarbone\PHPConsulAPI\Tools\PHPCSFixer\Fixer\FQCN\Normalizer */
    private Normalizer $nz;

    /**
     * ForceFQCNFixer constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->nz = new Normalizer();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
    }

    public function fix(SplFileInfo $file, Tokens $tokens): void
    {
        $namespaces = (new NamespaceAnalyzer($tokens))->getNamespaces();

        \reset($tokens);

        foreach ($tokens as $index => $token) {
            if ($token->isGivenKind(\T_DOC_COMMENT)) {
                $currentNamespace = null;
                foreach ($namespaces as $namespace) {
                    if ($namespace->getScope()->inRange($index)) {
                        $currentNamespace = $namespace;
                        break;
                    }
                }

                if (null === $currentNamespace) {
                    continue;
                }

                $docBlock = new DocBlock($token->getContent());

                $annotations = $docBlock->getAnnotationsOfType(Annotation::getTagsWithTypes());
                if (empty($annotations)) {
                    continue;
                }

                foreach ($annotations as $annotation) {
                    $this->fixTheThing($currentNamespace, $annotation);
                }

                $tokens[$index] = new Token([\T_DOC_COMMENT, $docBlock->getContent()]);
            }
        }
    }

    /**
     * @param \AdamWojs\PhpCsFixerPhpdocForceFQCN\Analyzer\NamespaceInfo $currentNamespace
     * @param \PhpCsFixer\DocBlock\Annotation $annotation
     */
    private function fixTheThing(NamespaceInfo $currentNamespace, Annotation $annotation): void
    {
        $types = $annotation->getTypes();
        foreach ($types as $i => $type) {
            $types[$i] = $this->nz->normalizeType($currentNamespace, $type);
        }

        if ($types !== $annotation->getTypes()) {
            $annotation->setTypes($types);
        }
    }
}
