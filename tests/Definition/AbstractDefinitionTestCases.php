<?php namespace DCarbone\PHPConsulAPITests\Definition;

/*
   Copyright 2016-2017 Daniel Carbone (daniel.p.carbone@gmail.com)

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
 * @package DCarbone\PHPConsulAPITests\Definition
 */
abstract class AbstractDefinitionTestCases extends TestCase {
    /** @var \ReflectionClass */
    protected $reflectionClass;

    /** @var \phpDocumentor\Reflection\DocBlockFactory */
    protected $docBlockFactory;

    /** @var object */
    protected $emptyInstance;

    /** @var bool */
    protected $requiresSetters = false;

    /**
     * @return string
     */
    abstract protected function getSubjectClassName();

    /**
     * @return \ReflectionClass
     */
    protected function getReflectionClass() {
        if (!isset($this->reflectionClass)) {
            $this->reflectionClass = new \ReflectionClass($this->getSubjectClassName());
        }

        return $this->reflectionClass;
    }

    /**
     * @return object
     */
    protected function getEmptyInstance() {
        if (!isset($this->emptyInstance)) {
            $class = $this->getSubjectClassName();
            $this->emptyInstance = new $class;
        }

        return $this->emptyInstance;
    }

    /**
     * @inheritdoc
     */
    protected function setUp() {
        $this->docBlockFactory = DocBlockFactory::createInstance();
    }

    public function testClassConstructorImplementation() {
        /** @var \phpDocumentor\Reflection\DocBlock\Tags\Param $parameterParamTag */

        $reflectionClass = $this->getReflectionClass();

        if ($reflectionClass->hasMethod('__construct')) {
            $className = $reflectionClass->getName();

            // Ensure constructor has at least one argument
            $constructorReflection = $reflectionClass->getMethod('__construct');
            $this->assertGreaterThanOrEqual(1,
                $constructorReflection->getNumberOfParameters(),
                sprintf(
                    'Class "%s" must have at least one argument in it\'s constructor',
                    $reflectionClass->getName()
                ));

            // Get parameter reflections
            $constructorParameters = $constructorReflection->getParameters();

            // Get doc block
            $constructorDocBlock = $this->docBlockFactory->create($constructorReflection->getDocComment());

            // Get "@param" tags
            $paramTags = $constructorDocBlock->getTagsByName('param');

            // Ensure we have one param tag per constructor argument
            $this->assertEquals(count($paramTags),
                count($constructorParameters),
                sprintf(
                    'Constructor for class "%s" must have one "@param" doc block tag for each parameter.',
                    $reflectionClass->getName()
                ));

            foreach ($constructorParameters as $i => $parameter) {
                // Just go ahead and get this stuff here...
                $parameterName = $parameter->getName();
                $parameterParamTag = $paramTags[$i];
                $parameterParamType = $parameterParamTag->getType();
                $parameterClass = $parameter->getClass();

                // Doc block tag must have same name as actual parameter and in the same ordinal position
                $this->assertEquals($parameterParamTag->getVariableName(),
                    $parameterName,
                    sprintf(
                        'Parameter "%s" in constructor method for class "%s" must specify a doc block tag of type @param with the same variable name',
                        $parameterName,
                        $className
                    ));

                // First argument must always be an optional value of type array
                if (0 === $i) {
                    // First argument must be array
                    $this->assertTrue($parameter->isArray(),
                        sprintf(
                            'The first parameter in constructor method for class "%s" must use "array" type hinting',
                            $className
                        ));
                    // First argument must be optional
                    $this->assertTrue($parameter->isOptional(),
                        sprintf(
                            'The first parameter in constructor method for class "%s" must be optional',
                            $className
                        ));
                    // First argument must have param doc block tag of simply "@param array $varname"
                    $this->assertInstanceOf(Array_::class,
                        $parameterParamType,
                        sprintf(
                            'The first parameter in constructor method for class "%s" must be simply "array", "%s" found',
                            $parameterName,
                            $className,
                            (string)$parameterParamType
                        ));
                } // Subsequent args, just make sure they are optional and have doc block tag.
                else {
                    $this->assertTrue($parameter->isOptional(),
                        sprintf(
                            'The %d parameter "%s" in constructor of class "%s" must be made optional.',
                            $i + 1,
                            $parameterName,
                            $className
                        ));

                    switch (true) {
                        case $parameterParamType instanceof Compound:
                            $this->assertNull($parameterClass,
                                sprintf(
                                    'The %d parameter "%s" in constructor for class "%s" specifies multiple allowable types of "%s" in it\'s @param doc block tag but type-hints "%s".  These must be made equal.',
                                    $i + 1,
                                    $parameterName,
                                    $className,
                                    (string)$parameterParamType,
                                    (string)$parameterClass
                                ));
                            break;

                        case $parameterParamType instanceof String_:
                        case $parameterParamType instanceof Integer:
                        case $parameterParamType instanceof Float_:
                        case $parameterParamType instanceof Boolean:
                            $this->assertNull($parameterClass,
                                sprintf(
                                    'The %d parameter "%s" in constructor for class "%s" is specified as a scalar type by it\'s doc block @param tag of "%s" yet type hints "%s".  Either the param tag must be corrected or the type-hinting must be removed to retain php 5.6 compatibility',
                                    $i + 1,
                                    $parameterName,
                                    $className,
                                    (string)$parameterParamType,
                                    (string)$parameterClass
                                ));
                            break;

                        case $parameter->isArray():
                            $this->assertInstanceOf(Array_::class,
                                $parameterParamType,
                                sprintf(
                                    'The %d parameter "%s" in constructor for class "%s" specifies an "array" param type yet specifies "%s" as it\'s type-hint.  This must be corrected.',
                                    $i + 1,
                                    $parameterName,
                                    $className,
                                    (string)$parameterClass
                                ));
                            break;

                        case $parameterParamType instanceof Object_:
                            $fqsn = ltrim((string)$parameterParamType->getFqsen(), "\\");
                            $this->assertEquals($fqsn,
                                $parameterClass,
                                sprintf(
                                    'The %d parameter "%s" in constructor for class "%s" specifies value of "%s" but type-hints "%s"',
                                    $i + 1,
                                    $parameterName,
                                    $className,
                                    (string)$parameterParamType->getFqsen(),
                                    $parameterClass->getName()
                                ));
                            break;

                        default:
                            $this->fail(sprintf(
                                'The parameter "%s" in constructor for class "%s" is of type "%s", and there is no constructor sanity check. Please add one to the test suite.',
                                $parameterName,
                                $className,
                                (string)$parameter->getClass()
                            ));
                    }
                }
            }
        }
    }

    public function testClassPropertyDefinitionAndImplementation() {
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
     * @param \ReflectionClass $reflectionClass
     * @param \ReflectionProperty $reflectionProperty
     */
    protected function _assertPropertyPHPDocExists(\ReflectionClass $reflectionClass,
                                                   \ReflectionProperty $reflectionProperty) {
        $className = $reflectionClass->getName();
        $propertyName = $reflectionProperty->getName();

        $this->assertRegExp('/^[A-Z]/S',
            $propertyName,
            sprintf(
                'The %s property "%s" is public but does not start with a capital letter.',
                $className,
                $propertyName
            ));

        $propertyDocBlock = $this->docBlockFactory->create($reflectionProperty->getDocComment());

        $this->assertTrue($propertyDocBlock->hasTag('var'),
            sprintf(
                'The %s property "%s" is missing a @var tag',
                $className,
                $propertyName
            ));

        $this->assertCount(1,
            $propertyDocBlock->getTagsByName('var'),
            sprintf(
                'Property "%s" in class "%s" has multiple @var tags defined.',
                $propertyName,
                $className
            ));

    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param \ReflectionProperty $reflectionProperty
     */
    protected function _assertCorrectPropertyZeroValue(\ReflectionClass $reflectionClass,
                                                       \ReflectionProperty $reflectionProperty) {
        /** @var \phpDocumentor\Reflection\Type $propertyVarType */

        $className = $reflectionClass->getName();
        $propertyName = $reflectionProperty->getName();
        $emptyInstance = $this->getEmptyInstance();

        $failMessageTemplate = sprintf(
            'The default value of property "%s" in class "%s" must be %%s',
            $propertyName,
            $className
        );

        $propertyDocBlock = $this->docBlockFactory->create($reflectionProperty->getDocComment());

        $propertyVarType = $propertyDocBlock->getTagsByName('var')[0]->getType();

        $defaultValue = $emptyInstance->{$propertyName};

        switch (true) {
            case $propertyVarType instanceof Compound:
                $compoundTypes = explode('|', (string)$propertyVarType);
                if (in_array('null', $compoundTypes)) {
                    $failMessage = sprintf($failMessageTemplate, 'null');
                    $this->assertNull($defaultValue, $failMessage);
                } else {
                    $this->fail(sprintf(
                        'The property "%s" in class "%s" is of type "%s", and there is no zero-val check in place for this type. Please add one to the test suite.',
                        $propertyName,
                        $className,
                        (string)$propertyVarType
                    ));
                }

                break;

            case $propertyVarType instanceof Array_:
                $failMessage = sprintf($failMessageTemplate, 'an array');
                $this->assertInternalType('array', $defaultValue, $failMessage);
                break;

            case $propertyVarType instanceof Boolean:
                $failMessage = sprintf($failMessageTemplate, 'the boolean value false');
                $this->assertInternalType('boolean', $defaultValue, $failMessage);
                $this->assertFalse($defaultValue, $failMessage);
                break;

            case $propertyVarType instanceof Float_:
                $failMessage = sprintf($failMessageTemplate, 'a float value of 0.0');
                $this->assertInternalType('float', $defaultValue, $failMessage);
                $this->assertEquals(0.0, $defaultValue, $failMessage);
                break;

            case $propertyVarType instanceof Integer:
                $failMessage = sprintf($failMessageTemplate, 'the integer 0');
                $this->assertInternalType('integer', $defaultValue, $failMessage);
                $this->assertEquals(0, $defaultValue, $failMessage);
                break;

            case $propertyVarType instanceof Object_:
                $fqsn = (string)$propertyVarType->getFqsen();
                $failMessage = sprintf($failMessageTemplate, 'null');

                $isInterface = interface_exists($fqsn, true);
                $isClass = class_exists($fqsn);

                $this->assertTrue($isInterface || $isClass,
                    sprintf(
                        'The property "%s" in class "%s" specifies it\'s type as "%s", but that resource is not auto-loadable.  Is the @var tag a FQSN?',
                        $propertyName,
                        $className,
                        $fqsn
                    ));

                $this->assertNull($defaultValue, $failMessage);

                break;

            case $propertyVarType instanceof String_:
                $failMessage = sprintf($failMessageTemplate, 'an empty string');
                $this->assertInternalType('string', $defaultValue, $failMessage);
                $this->assertEquals('', $defaultValue, $failMessage);
                break;

            default:
                $this->fail(sprintf(
                    'The property "%s" in class "%s" is of type "%s", and there is no zero-val check in place for this type. Please add one to the test suite.',
                    $propertyName,
                    $className,
                    (string)$propertyVarType
                ));
        }
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param \ReflectionProperty $reflectionProperty
     */
    protected function _assertCorrectGetterImplementation(\ReflectionClass $reflectionClass,
                                                          \ReflectionProperty $reflectionProperty) {
        /** @var \phpDocumentor\Reflection\Type $propertyVarType */
        /** @var \phpDocumentor\Reflection\DocBlock\Tags\Return_ $methodReturnTag */

        $className = $reflectionClass->getName();
        $propertyName = $reflectionProperty->getName();

        $propertyDocBlock = $this->docBlockFactory->create($reflectionProperty->getDocComment());

        $propertyVarType = $propertyDocBlock->getTagsByName('var')[0]->getType();

        if ($propertyVarType instanceof Boolean) {
            $expectedGetterName = sprintf('is%s', ucfirst($propertyName));
        } else {
            $expectedGetterName = sprintf('get%s', ucfirst($propertyName));
        }

        $this->assertTrue($reflectionClass->hasMethod($expectedGetterName),
            sprintf(
                'The "%s" property "%s" requires a getter named "%s" be implemented.',
                $className,
                $propertyName,
                $expectedGetterName
            ));

        $methodReflection = $reflectionClass->getMethod($expectedGetterName);

        $this->assertTrue($methodReflection->isPublic(),
            sprintf(
                'The getter "%s" for property "%s" in class "%s" must be publicly visible',
                $expectedGetterName,
                $propertyName,
                $className
            ));

        $methodDocBlock = $this->docBlockFactory->create($methodReflection->getDocComment());

        $methodReturnTags = $methodDocBlock->getTagsByName('return');
        $this->assertCount(1,
            $methodReturnTags,
            sprintf(
                'The getter method "%s" for property "%s in class "%s" must have only 1 @return tag defined.',
                $expectedGetterName,
                $propertyName,
                $className
            ));

        $methodReturnTag = $methodReturnTags[0];
        $methodReturnType = $methodReturnTag->getType();

        $this->assertEquals((string)$propertyVarType,
            (string)$methodReturnType,
            sprintf(
                'The getter "%s" for property "%s" in class "%s" specifies a return type "%s", which does not match the property type declaration of "%s".',
                $expectedGetterName,
                $propertyName,
                $className,
                (string)$methodReturnType,
                (string)$propertyVarType
            ));
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param \ReflectionProperty $reflectionProperty
     */
    protected function _assertCorrectSetterImplementation(\ReflectionClass $reflectionClass,
                                                          \ReflectionProperty $reflectionProperty) {
        /** @var \phpDocumentor\Reflection\Type $propertyVarType */
        /** @var \phpDocumentor\Reflection\DocBlock\Tags\Param $methodParamTag */
        /** @var \phpDocumentor\Reflection\DocBlock\Tags\Return_ $methodReturnTag */

        $className = $reflectionClass->getName();
        $propertyName = $reflectionProperty->getName();
        $expectedSetterName = sprintf('set%s', ucfirst($propertyName));

        if ($this->requiresSetters) {
            $this->assertTrue(method_exists($className, $expectedSetterName),
                sprintf(
                    'Class "%s" must have a setter named "%s" for property "%s"',
                    $className,
                    $expectedSetterName,
                    $propertyName
                ));
        } else if (!$reflectionClass->hasMethod($expectedSetterName)) {
            return;
        }

        // Get method reflection
        $reflectionMethod = $reflectionClass->getMethod($expectedSetterName);

        // Validate we have one parameter
        $this->assertEquals(1,
            $reflectionMethod->getNumberOfParameters(),
            sprintf(
                'Setter "%s" for property "%s" in class "%s" must have one parameter',
                $expectedSetterName,
                $propertyName,
                $className
            ));
        $this->assertEquals(1,
            $reflectionMethod->getNumberOfRequiredParameters(),
            sprintf(
                'Setter "%s" for property "%s" in class "%s" must have one required parameter',
                $expectedSetterName,
                $propertyName,
                $className
            ));

        // Get first parameter
        $reflectionParameter = $reflectionMethod->getParameters()[0];
        // Validate name
        $parameterName = $reflectionParameter->getName();
        $this->assertEquals($propertyName,
            $parameterName,
            sprintf(
                'Setter "%s" for property "%s" in class "%s" must have a parameter with the same name as the property',
                $expectedSetterName,
                $propertyName,
                $className
            ));

        // Get method doc block
        $methodDockBlock = $this->docBlockFactory->create($reflectionMethod->getDocComment());
        // Try to locate param doc block tag
        $methodParamTags = $methodDockBlock->getTagsByName('param');
        $this->assertCount(1,
            $methodParamTags,
            sprintf(
                'Parameter "%s" for setter "%s" for property "%s" in class "%s" must have a "@param" doc block attribute',
                $parameterName,
                $expectedSetterName,
                $propertyName,
                $className
            ));

        // Grab doc block definition from class property declaration
        $propertyDocBlock = $this->docBlockFactory->create($reflectionProperty->getDocComment());
        $propertyVarType = $propertyDocBlock->getTagsByName('var')[0]->getType();

        // Pull out the @param attribute from the doc block comment
        $methodParamTag = $methodParamTags[0];
        $methodParamType = $methodParamTag->getType();
        $parameterClass = $reflectionParameter->getClass();

        // Finally, attempt to validate setter declaration sanity
        switch (true) {
            // If the setter is designed to expect more than 1 type of variable, ensure that
            // there is no type-hinting going on.
            case $methodParamType instanceof Compound:
                $this->assertNull($parameterClass,
                    sprintf(
                        'The "@param" docblock for parameter "%s" in setter "%s" for property "%s" in class "%s" indicates that the value can be one of "%s", but the param TypeHint explicitly requires "%s".',
                        $parameterName,
                        $expectedSetterName,
                        $propertyName,
                        $className,
                        (string)$methodParamType,
                        // Dumb.
                        null !== $parameterClass ? $parameterClass->getName() : ''
                    ));
                break;

            // If we're dealing with a scalar types, don't allow type-hinting as we need to work with php5
            case $propertyVarType instanceof String_:
            case $propertyVarType instanceof Integer:
            case $propertyVarType instanceof Float_:
            case $propertyVarType instanceof Boolean:
                $this->assertNull($parameterClass,
                    sprintf(
                        'Parameter "%s" in setter "%s" for property "%s" in class "%s" must not use type-hinting to maintain php 5 compatibility',
                        $parameterName,
                        $expectedSetterName,
                        $propertyName,
                        $className
                    ));
                break;

            case $propertyVarType instanceof Array_:
                $this->assertTrue($reflectionParameter->isArray(),
                    sprintf(
                        'Parameter "%s" in setter "%s" for property "%s" in class "%s" must use type-hint of "array" or have it\'s doc block param tag value changed.',
                        $parameterName,
                        $expectedSetterName,
                        $propertyName,
                        $className
                    ));
                break;

            case $propertyVarType instanceof Object_:
                $fqsn = ltrim((string)$propertyVarType->getFqsen(), "\\");
                $this->assertEquals($fqsn,
                    $parameterClass->getName(),
                    sprintf(
                        'Parameter "%s" in setter "%s" for property "%s" in class "%s" must use type-hint equal to "%s".  Currently specifies "%s"',
                        $parameterName,
                        $expectedSetterName,
                        $propertyName,
                        $className,
                        (string)$propertyVarType->getFqsen(),
                        $parameterClass->getName()
                    ));
                break;

            default:
                $this->fail(sprintf(
                    'The parameter "%s" in setter "%s" for property "%s" in class "%s" is of type "%s", and there is no setter sanity check. Please add one to the test suite.',
                    $parameterName,
                    $expectedSetterName,
                    $propertyName,
                    $className,
                    (string)$propertyVarType
                ));
        }
    }
}