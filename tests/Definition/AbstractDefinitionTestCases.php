<?php namespace DCarbone\PHPConsulAPITests\Definition;

/*
   Copyright 2016-2018 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\Go\Time;
use function DCarbone\PHPConsulAPITests\determine_param_hint;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Boolean;
use phpDocumentor\Reflection\Types\Compound;
use phpDocumentor\Reflection\Types\Float_;
use phpDocumentor\Reflection\Types\Integer;
use phpDocumentor\Reflection\Types\Object_;
use phpDocumentor\Reflection\Types\String_;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractDefinitionTestCases
 */
abstract class AbstractDefinitionTestCases extends TestCase
{
    /** @var \ReflectionClass */
    protected \ReflectionClass $reflectionClass;

    /** @var \phpDocumentor\Reflection\DocBlockFactory */
    protected DocBlockFactory $docBlockFactory;

    /** @var object */
    protected object $emptyInstance;

    /** @var bool */
    protected bool $requiresSetters = false;

    /** @var bool */
    protected bool $variableFirstConstructorArgType = false;

    /**
     * @throws \ReflectionException
     */
    public function testClassConstructorImplementation(): void
    {
        /** @var \phpDocumentor\Reflection\DocBlock\Tags\Param $parameterParamTag */
        $reflectionClass = $this->getReflectionClass();

        if ($reflectionClass->hasMethod('__construct')) {
            $className = $reflectionClass->getName();

            // Ensure constructor has at least one argument
            $constructorReflection = $reflectionClass->getMethod('__construct');
            static::assertGreaterThanOrEqual(
                1,
                $constructorReflection->getNumberOfParameters(),
                \sprintf(
                    'Class "%s" must have at least one argument in it\'s constructor',
                    $reflectionClass->getName()
                )
            );

            // Get parameter reflections
            $constructorParameters = $constructorReflection->getParameters();

            // Get doc block
            $constructorDocBlock = $this->docBlockFactory->create($constructorReflection->getDocComment());

            // Get "@param" tags
            $paramTags = $constructorDocBlock->getTagsByName('param');

            // Ensure we have one param tag per constructor argument
            static::assertSame(
                \count($paramTags),
                \count($constructorParameters),
                \sprintf(
                    'Constructor for class "%s" must have one "@param" doc block tag for each parameter.',
                    $reflectionClass->getName()
                )
            );

            foreach ($constructorParameters as $i => $parameter) {
                // Just go ahead and get this stuff here...
                $parameterName      = $parameter->getName();
                $parameterParamTag  = $paramTags[$i];
                $parameterParamType = $parameterParamTag->getType();
                $parameterType      = $parameter->getType();

                // Doc block tag must have same name as actual parameter and in the same ordinal position
                static::assertSame(
                    $parameterParamTag->getVariableName(),
                    $parameterName,
                    \sprintf(
                        'Parameter "%s" in constructor method for class "%s" must specify a doc block tag of type @param with the same variable name',
                        $parameterName,
                        $className
                    )
                );

                // First argument must always be an optional value of type array
                if (!$this->variableFirstConstructorArgType && 0 === $i) {
                    // First argument must be array
                    $ptype = $parameter->getType();
                    static::assertTrue(
                        null !== $ptype && 'array' === $ptype->getName(),
                        \sprintf(
                            'The first parameter in constructor method for class "%s" must use "array" type hinting',
                            $className
                        )
                    );
                    // First argument must be optional
                    static::assertTrue(
                        $parameter->isOptional(),
                        \sprintf(
                            'The first parameter in constructor method for class "%s" must be optional',
                            $className
                        )
                    );
                    // First argument must have param doc block tag of simply "@param array $varname"
                    static::assertInstanceOf(
                        Array_::class,
                        $parameterParamType,
                        \sprintf(
                            'The first parameter (%s) in constructor method for class "%s" must be simply "array", "%s" found',
                            $parameterName,
                            $className,
                            (string)$parameterParamType
                        )
                    );
                } // Subsequent args, just make sure they are optional and have doc block tag.
                else {
                    static::assertTrue(
                        $parameter->isOptional(),
                        \sprintf(
                            'The %d parameter "%s" in constructor of class "%s" must be made optional.',
                            $i + 1,
                            $parameterName,
                            $className
                        )
                    );

                    switch (true) {
                        case $parameterParamType instanceof Compound:
                            [$nullable, $expectedHint] = determine_param_hint($parameterParamType);
                            if ($nullable) {
                                if (null === $expectedHint) {
                                    // if can be nullable or multiple concrete types, must not use typehint
                                    static::assertNull(
                                        $parameterType,
                                        \sprintf(
                                            'The %d parameter "%s" in constructor for class "%s" specifies multiple allowable types of "%s" in it\'s @param doc block tag but typehints "%s".  This parameter must have no typehint.',
                                            $i + 1,
                                            $parameterName,
                                            $className,
                                            (string)$parameterParamType,
                                            (string)$parameterType
                                        )
                                    );
                                } else {
                                    // if can be nullable or singular concrete type, must hint concrete type
                                    static::assertNotNull(
                                        $parameterType,
                                        \sprintf(
                                            'The %d parameter "%s" in constructor for class "%s" specifies multiple allowable types of "%s" in it\'s @param doc block tag but typehints "%s".  This parameter must use typehint of %s',
                                            $i + 1,
                                            $parameterName,
                                            $className,
                                            (string)$parameterParamType,
                                            (string)$parameterType,
                                            (string)$expectedHint
                                        )
                                    );
                                }
                            } else {
                                // if can be one of multiple concrete types, must not specify typehint
                                static::assertNull(
                                    $parameterType,
                                    \sprintf(
                                        'The %d parameter "%s" in constructor for class "%s" specifies multiple allowable types of "%s" in it\'s @param doc block tag but typehints "%s".  This parameter must have no typehint.',
                                        $i + 1,
                                        $parameterName,
                                        $className,
                                        (string)$parameterParamType,
                                        (string)$parameterType
                                    )
                                );
                            }
                            break;

                        case $parameterParamType instanceof String_:
                        case $parameterParamType instanceof Integer:
                        case $parameterParamType instanceof Float_:
                        case $parameterParamType instanceof Boolean:
                            static::assertSame(
                                (string)$parameterParamType,
                                (string)$parameterType,
                                \sprintf(
                                    'The %d parameter "%s" in constructor for class "%s" is specified as a scalar type by it\'s doc block @param tag of "%s" yet type hints "%s".  These must match.',
                                    $i + 1,
                                    $parameterName,
                                    $className,
                                    (string)$parameterParamType,
                                    (string)$parameterType
                                )
                            );
                            break;

                        case $parameter->isArray():
                            static::assertInstanceOf(
                                Array_::class,
                                $parameterParamType,
                                \sprintf(
                                    'The %d parameter "%s" in constructor for class "%s" specifies an "array" param type yet specifies "%s" as it\'s typehint.  This must be corrected.',
                                    $i + 1,
                                    $parameterName,
                                    $className,
                                    (string)$parameterType
                                )
                            );
                            break;

                        case $parameterParamType instanceof Object_:
                            $fqsn = \ltrim((string)$parameterParamType->getFqsen(), '\\');
                            static::assertSame(
                                $fqsn,
                                $parameterType,
                                \sprintf(
                                    'The %d parameter "%s" in constructor for class "%s" specifies value of "%s" but typehints "%s"',
                                    $i + 1,
                                    $parameterName,
                                    $className,
                                    (string)$parameterParamType->getFqsen(),
                                    $parameterType->getName()
                                )
                            );
                            break;

                        default:
                            static::fail(
                                \sprintf(
                                    'The parameter "%s" in constructor for class "%s" is of type "%s", and there is no constructor sanity check. Please add one to the test suite.',
                                    $parameterName,
                                    $className,
                                    $parameter->getType()->getName()
                                )
                            );
                    }
                }
            }
        }
    }

    /**
     * @throws \ReflectionException
     */
    public function testClassPropertyDefinitionAndImplementation(): void
    {
        $reflectionClass = $this->getReflectionClass();

        // For now, only interested in Public properties, may expand later.
        foreach ($reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC) as $reflectionProperty) {
            $this->_assertPropertyPHPDocExists($reflectionClass, $reflectionProperty);
            $this->_assertCorrectPropertyZeroValue($reflectionClass, $reflectionProperty);
            $this->_assertCorrectGetterImplementation($reflectionClass, $reflectionProperty);
            $this->_assertCorrectSetterImplementation($reflectionClass, $reflectionProperty);
        }
    }

    /**
     * @return string
     */
    abstract protected function getSubjectClassName(): string;

    /**
     * @throws \ReflectionException
     * @return \ReflectionClass
     */
    protected function getReflectionClass(): \ReflectionClass
    {
        if (!isset($this->reflectionClass)) {
            $this->reflectionClass = new \ReflectionClass($this->getSubjectClassName());
        }

        return $this->reflectionClass;
    }

    /**
     * @return object
     */
    protected function getEmptyInstance(): object
    {
        if (!isset($this->emptyInstance)) {
            $class               = $this->getSubjectClassName();
            $this->emptyInstance = new $class();
        }

        return $this->emptyInstance;
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param \ReflectionProperty $reflectionProperty
     */
    protected function _assertPropertyPHPDocExists(
        \ReflectionClass $reflectionClass,
        \ReflectionProperty $reflectionProperty
    ): void {
        $className    = $reflectionClass->getName();
        $propertyName = $reflectionProperty->getName();

        static::assertMatchesRegularExpression(
            '/^[A-Z]/S',
            $propertyName,
            \sprintf(
                'The %s property "%s" is public but does not start with a capital letter.',
                $className,
                $propertyName
            )
        );

        $propertyDocBlock = $this->docBlockFactory->create($reflectionProperty->getDocComment());

        static::assertTrue(
            $propertyDocBlock->hasTag('var'),
            \sprintf(
                'The %s property "%s" is missing a @var tag',
                $className,
                $propertyName
            )
        );

        static::assertCount(
            1,
            $propertyDocBlock->getTagsByName('var'),
            \sprintf(
                'Property "%s" in class "%s" has multiple @var tags defined.',
                $propertyName,
                $className
            )
        );
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param \ReflectionProperty $reflectionProperty
     */
    protected function _assertCorrectPropertyZeroValue(
        \ReflectionClass $reflectionClass,
        \ReflectionProperty $reflectionProperty
    ): void {
        /** @var \phpDocumentor\Reflection\Type $propertyVarType */
        $className     = $reflectionClass->getName();
        $propertyName  = $reflectionProperty->getName();
        $emptyInstance = $this->getEmptyInstance();

        $failMessageTemplate = \sprintf(
            'The default value of property "%s" in class "%s" must be %%s',
            $propertyName,
            $className
        );

        $propertyDocBlock = $this->docBlockFactory->create($reflectionProperty->getDocComment());

        $propertyVarType = $propertyDocBlock->getTagsByName('var')[0]->getType();

        $defaultValue = $emptyInstance->{$propertyName};

        switch (true) {
            case $propertyVarType instanceof Compound:
                $compoundTypes = \explode('|', (string)$propertyVarType);
                if (\in_array('null', $compoundTypes, true)) {
                    $failMessage = \sprintf($failMessageTemplate, 'null');
                    static::assertNull($defaultValue, $failMessage);
                } else {
                    static::fail(
                        \sprintf(
                            'The property "%s" in class "%s" is of type "%s", and there is no zero-val check in place for this type. Please add one to the test suite.',
                            $propertyName,
                            $className,
                            (string)$propertyVarType
                        )
                    );
                }

                break;

            case $propertyVarType instanceof Array_:
                $failMessage = \sprintf($failMessageTemplate, 'an array');
                static::assertIsArray($defaultValue, $failMessage);
                break;

            case $propertyVarType instanceof Boolean:
                $failMessage = \sprintf($failMessageTemplate, 'the boolean value false');
                static::assertIsBool($defaultValue, $failMessage);
                static::assertFalse($defaultValue, $failMessage);
                break;

            case $propertyVarType instanceof Float_:
                $failMessage = \sprintf($failMessageTemplate, 'a float value of 0.0');
                static::assertIsFloat($defaultValue, $failMessage);
                static::assertSame(0.0, $defaultValue, $failMessage);
                break;

            case $propertyVarType instanceof Integer:
                $failMessage = \sprintf($failMessageTemplate, 'the integer 0');
                static::assertIsInt($defaultValue, $failMessage);
                static::assertSame(0, $defaultValue, $failMessage);
                break;

            case $propertyVarType instanceof Object_:
                $fqsn = \ltrim((string)$propertyVarType->getFqsen(), '\\');

                $isInterface = \interface_exists($fqsn, true);
                $isClass     = \class_exists($fqsn);

                static::assertTrue(
                    $isInterface || $isClass,
                    \sprintf(
                        'The property "%s" in class "%s" specifies it\'s type as "%s", but that resource is not auto-loadable.  Is the @var tag a FQSN?',
                        $propertyName,
                        $className,
                        $fqsn
                    )
                );

                $implements = \class_implements($fqsn);

                if (\in_array('Iterator', $implements, true)) {
                    // test for "slice" mimicking...
                    $failMessage = \sprintf($failMessageTemplate, $fqsn);
                    static::assertInstanceOf($fqsn, $defaultValue, $failMessage);
                } elseif (\in_array('DCarbone\\PHPConsulAPI\\ScalarType', $implements, true)) {
                    // test for "typed scalars" mimicking...
                    $failMessage = \sprintf($failMessageTemplate, $defaultValue, $fqsn);
                    static::assertInstanceOf($fqsn, $defaultValue, $failMessage);
                } elseif (Time\Time::class === $fqsn) {
                    $failMessage = \sprintf($failMessageTemplate, Time\Time::class);
                    static::assertInstanceOf(Time\Time::class, $defaultValue, $failMessage);
                } elseif (Time\Duration::class === $fqsn) {
                    \var_dump($emptyInstance);
                    $failMessage = \sprintf($failMessageTemplate, Time\Duration::class);
                    static::assertInstanceOf(Time\Duration::class, $defaultValue, $failMessage);
                } else {
                    $failMessage = \sprintf($failMessageTemplate, 'null');
                    static::assertNull($defaultValue, $failMessage);
                }

                break;

            case $propertyVarType instanceof String_:
                $failMessage = \sprintf($failMessageTemplate, 'an empty string');
                static::assertIsString($defaultValue, $failMessage);
                static::assertSame('', $defaultValue, $failMessage);
                break;

            default:
                static::fail(
                    \sprintf(
                        'The property "%s" in class "%s" is of type "%s", and there is no zero-val check in place for this type. Please add one to the test suite.',
                        $propertyName,
                        $className,
                        (string)$propertyVarType
                    )
                );
        }
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param \ReflectionProperty $reflectionProperty
     * @throws \ReflectionException
     */
    protected function _assertCorrectGetterImplementation(
        \ReflectionClass $reflectionClass,
        \ReflectionProperty $reflectionProperty
    ): void {
        /** @var \phpDocumentor\Reflection\Type $propertyVarType */
        /** @var \phpDocumentor\Reflection\DocBlock\Tags\Return_ $methodReturnTag */
        $className    = $reflectionClass->getName();
        $propertyName = $reflectionProperty->getName();

        $propertyDocBlock = $this->docBlockFactory->create($reflectionProperty->getDocComment());

        $propertyVarType = $propertyDocBlock->getTagsByName('var')[0]->getType();

        if ($propertyVarType instanceof Boolean) {
            $expectedGetterName = \sprintf('is%s', \ucfirst($propertyName));
        } else {
            $expectedGetterName = \sprintf('get%s', \ucfirst($propertyName));
        }

        static::assertTrue(
            $reflectionClass->hasMethod($expectedGetterName),
            \sprintf(
                'The "%s" property "%s" requires a getter named "%s" be implemented.',
                $className,
                $propertyName,
                $expectedGetterName
            )
        );

        $methodReflection = $reflectionClass->getMethod($expectedGetterName);

        static::assertTrue(
            $methodReflection->isPublic(),
            \sprintf(
                'The getter "%s" for property "%s" in class "%s" must be publicly visible',
                $expectedGetterName,
                $propertyName,
                $className
            )
        );

        $methodDocBlock = $this->docBlockFactory->create($methodReflection->getDocComment());

        $methodReturnTags = $methodDocBlock->getTagsByName('return');
        static::assertCount(
            1,
            $methodReturnTags,
            \sprintf(
                'The getter method "%s" for property "%s in class "%s" must have only 1 @return tag defined.',
                $expectedGetterName,
                $propertyName,
                $className
            )
        );

        $methodReturnTag  = $methodReturnTags[0];
        $methodReturnType = $methodReturnTag->getType();

        static::assertSame(
            (string)$propertyVarType,
            (string)$methodReturnType,
            \sprintf(
                'The getter "%s" for property "%s" in class "%s" specifies a return type "%s", which does not match the property type declaration of "%s".',
                $expectedGetterName,
                $propertyName,
                $className,
                (string)$methodReturnType,
                (string)$propertyVarType
            )
        );
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param \ReflectionProperty $reflectionProperty
     * @throws \ReflectionException
     */
    protected function _assertCorrectSetterImplementation(
        \ReflectionClass $reflectionClass,
        \ReflectionProperty $reflectionProperty
    ): void {
        /** @var \phpDocumentor\Reflection\Type $propertyVarType */
        /** @var \phpDocumentor\Reflection\DocBlock\Tags\Param $methodParamTag */
        /** @var \phpDocumentor\Reflection\DocBlock\Tags\Return_ $methodReturnTag */
        $className    = $reflectionClass->getName();
        $propertyName = $reflectionProperty->getName();
        switch ($propertyName) {
            case 'ID':
            case 'TTL':
            case 'HTTP':
            case 'HTTPS':
            case 'TCP':
            case 'UDP':
            case 'WAN':
            case 'LAN':
            case 'DNS':
                $expectedParamName = \strtolower($propertyName);
                break;
            case 'TLSSkipVerify':
                $expectedParamName = 'tlsSkipVerify';
                break;
            case 'CAFile':
                $expectedParamName = 'caFile';
                break;
            case 'CACert':
                $expectedParamName = 'caCert';
                break;
            case 'JSONEncodeOpts':
                $expectedParamName = 'jsonEncodeOpts';
                break;
            default:
                $expectedParamName = \lcfirst($propertyName);
        }
        $expectedSetterName = \sprintf('set%s', \ucfirst($propertyName));

        $setterParamErrMsg = \sprintf(
            'Setter "%s" for property "%s" in class "%s" must have one required parameter named %s',
            $expectedSetterName,
            $propertyName,
            $className,
            $expectedParamName
        );

        $setterHintErrMsg = \sprintf(
            'Parameter "%s" in setter "%s" for property "%s" in class "%s" must have a type hint of "%%s"',
            $expectedParamName,
            $expectedSetterName,
            $propertyName,
            $className
        );

        if ($this->requiresSetters) {
            static::assertTrue(
                \method_exists($className, $expectedSetterName),
                \sprintf(
                    'Class "%s" must have a setter named "%s" for property "%s"',
                    $className,
                    $expectedSetterName,
                    $propertyName
                )
            );
        } elseif (!$reflectionClass->hasMethod($expectedSetterName)) {
            return;
        }

        // Get method reflection
        $reflectionMethod = $reflectionClass->getMethod($expectedSetterName);

        // Validate we have one parameter
        static::assertSame(1, $reflectionMethod->getNumberOfParameters(), $setterParamErrMsg);
        static::assertSame(1, $reflectionMethod->getNumberOfRequiredParameters(), $setterParamErrMsg);

        // Get first parameter
        $reflectionParameter = $reflectionMethod->getParameters()[0];
        // Validate name
        $parameterName = $reflectionParameter->getName();
        static::assertSame($expectedParamName, $parameterName, $setterParamErrMsg);

        // Get method doc block
        $methodDockBlock = $this->docBlockFactory->create($reflectionMethod->getDocComment());
        // Try to locate param doc block tag
        $methodParamTags = $methodDockBlock->getTagsByName('param');
        static::assertCount(
            1,
            $methodParamTags,
            \sprintf(
                'Parameter "%s" for setter "%s" for property "%s" in class "%s" must have a "@param" doc block attribute',
                $expectedParamName,
                $expectedSetterName,
                $propertyName,
                $className
            )
        );

        // Grab doc block definition from class property declaration
        $propertyDocBlock = $this->docBlockFactory->create($reflectionProperty->getDocComment());
        $propertyVarType  = $propertyDocBlock->getTagsByName('var')[0]->getType();

        // Pull out the @param attribute from the doc block comment
        $methodParamTag      = $methodParamTags[0];
        $methodParamType     = $methodParamTag->getType();
        $reflectionParamType = $reflectionParameter->getType();

        // Finally, attempt to validate setter declaration sanity
        switch (true) {
            // If the setter is designed to expect more than 1 type of variable, ensure that
            // there is no typehinting going on.
            case $methodParamType instanceof Compound:
                [$nullable, $expectedHint] = determine_param_hint($methodParamType);
                if ($nullable) {
                    if (null === $expectedHint) {
                        // if can be nullable or multiple concrete types, must not use typehint
                        static::assertNull(
                            $reflectionParamType,
                            \sprintf(
                                'The "@param" docblock for parameter "%s" in setter "%s" for property "%s" in class "%s" indicates that the value can be one of "%s", but the param typehint explicitly requires "%s".  This parameter must have no typehint.',
                                $expectedParamName,
                                $expectedSetterName,
                                $propertyName,
                                $className,
                                (string)$methodParamType,
                                // Dumb.
                                null !== $reflectionParamType ? $reflectionParamType->getName() : ''
                            )
                        );
                    } else {
                        // if can be null or singular concrete type, must hint concrete type
                        static::assertSame(
                            (string)$reflectionParamType,
                            (string)$expectedHint,
                            \sprintf(
                                'The "@param" docblock for parameter "%s" in setter "%s" for property "%s" in class "%s" indicates that the value can be one of "%s", but the param typehint explicitly requires "%s".  This parameter must use typehint "%s"',
                                $expectedParamName,
                                $expectedSetterName,
                                $propertyName,
                                $className,
                                (string)$methodParamType,
                                // Dumb.
                                null !== $reflectionParamType ? $reflectionParamType->getName() : '',
                                (string)$expectedHint
                            )
                        );
                    }
                } else {
                    static::assertNull(
                        $reflectionParamType,
                        \sprintf(
                            'The "@param" docblock for parameter "%s" in setter "%s" for property "%s" in class "%s" indicates that the value can be one of "%s", but the param typehint explicitly requires "%s".  This parameter must have no typehint',
                            $expectedParamName,
                            $expectedSetterName,
                            $propertyName,
                            $className,
                            (string)$methodParamType,
                            // Dumb.
                            null !== $reflectionParamType ? $reflectionParamType->getName() : ''
                        )
                    );
                }
                break;

            case $propertyVarType instanceof String_:
                static::assertTrue($reflectionParameter->hasType(), \sprintf($setterHintErrMsg, 'string'));
                static::assertSame(
                    'string',
                    $reflectionParamType->getName(),
                    \sprintf($setterHintErrMsg, 'string')
                );
                break;
            case $propertyVarType instanceof Integer:
                static::assertTrue($reflectionParameter->hasType(), \sprintf($setterHintErrMsg, 'int'));
                static::assertStringStartsWith(
                    'int',
                    $reflectionParamType->getName(),
                    \sprintf($setterHintErrMsg, 'int')
                );
                break;
            case $propertyVarType instanceof Float_:
                static::assertTrue($reflectionParameter->hasType(), \sprintf($setterHintErrMsg, 'float'));
                static::assertSame(
                    'float',
                    $reflectionParamType->getName(),
                    \sprintf($setterHintErrMsg, 'float')
                );
                break;
            case $propertyVarType instanceof Boolean:
                static::assertTrue($reflectionParameter->hasType(), \sprintf($setterHintErrMsg, 'bool'));
                static::assertSame(
                    'bool',
                    $reflectionParamType->getName(),
                    \sprintf($setterHintErrMsg, 'bool')
                );
                break;

            case $propertyVarType instanceof Array_:
                static::assertSame(
                    'array',
                    $reflectionParamType->getName(),
                    \sprintf(
                        'Parameter "%s" in setter "%s" for property "%s" in class "%s" must use typehint of "array" or have it\'s doc block param tag value changed.',
                        $parameterName,
                        $expectedSetterName,
                        $propertyName,
                        $className
                    )
                );
                break;

            case $propertyVarType instanceof Object_:
                $fqsn = \ltrim((string)$propertyVarType->getFqsen(), '\\');
                static::assertSame(
                    $fqsn,
                    $reflectionParamType,
                    \sprintf(
                        'Parameter "%s" in setter "%s" for property "%s" in class "%s" must use typehint equal to "%s".  Currently specifies "%s"',
                        $parameterName,
                        $expectedSetterName,
                        $propertyName,
                        $className,
                        (string)$propertyVarType->getFqsen(),
                        $reflectionParamType
                    )
                );
                break;

            default:
                static::fail(
                    \sprintf(
                        'The parameter "%s" in setter "%s" for property "%s" in class "%s" is of type "%s", and there is no setter sanity check. Please add one to the test suite.',
                        $parameterName,
                        $expectedSetterName,
                        $propertyName,
                        $className,
                        (string)$propertyVarType
                    )
                );
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->docBlockFactory = DocBlockFactory::createInstance();
    }
}
