<?php namespace DCarbone\PHPConsulAPITests\DefinitionTests;

/*
   Copyright 2016 Daniel Carbone (daniel.p.carbone@gmail.com)

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

/**
 * Class AbstractDefinitionTest
 * @package DCarbone\PHPConsulAPITests\DefinitionTests
 */
trait ModelDefinitionSanityTestsTrait
{
    /** @var \ReflectionClass */
    protected $reflectionClass;

    /** @var \phpDocumentor\Reflection\DocBlockFactory */
    protected $docBlockFactory;

    /** @var object */
    protected $emptyInstance;

    /**
     * @return \ReflectionClass
     */
    abstract protected function getReflectionClass();

    /**
     * @return object
     */
    abstract protected function getEmptyInstance();

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $this->docBlockFactory = DocBlockFactory::createInstance();
    }

    public function testClassConstructorImplementation()
    {
        /** @var \phpDocumentor\Reflection\DocBlock\Tags\Param $paramTag */

        $reflectionClass = $this->getReflectionClass();

        if ($reflectionClass->hasMethod('__construct'))
        {
            // Ensure constructor has at least one argument
            $constructorReflection = $reflectionClass->getMethod('__construct');
            $this->assertGreaterThanOrEqual(1, $constructorReflection->getNumberOfParameters(), sprintf(
                'Class "%s" must have at least one argument in it\'s constructor',
                $reflectionClass->getName()
            ));

            // Get parameter reflections
            $constructorParameters = $constructorReflection->getParameters();

            // Get docblock
            $constructorDocBlock = $this->docBlockFactory->create($constructorReflection->getDocComment());

            // Get "@param" tags
            $paramTags = $constructorDocBlock->getTagsByName('param');

            // Ensure we have one param tag per constructor argument
            $this->assertEquals(count($paramTags), count($constructorParameters), sprintf(
                'Constructor for class "%s" must have one "@param" doc block tag for each property.',
                $reflectionClass->getName()
            ));

            foreach($constructorParameters as $i => $parameter)
            {
                $paramTag = $paramTags[$i];
                $paramType = $paramTag->getType();

                // First argument must always be an optional value of type array
                if (0 === $i)
                {
                    $this->assertTrue($parameter->isArray(), sprintf(
                        'Constructor for class "%s" must have an array parameter as it\'s first argument',
                        $reflectionClass->getName()
                    ));
                    $this->assertTrue($parameter->isOptional(), sprintf(
                        'Constructor for class "%s" must be optional (you must set a default value of "array()")',
                        $reflectionClass->getName()
                    ));
                }


            }
        }
    }

    public function testClassPropertyDefinitionAndImplementation()
    {
        $reflectionClass = $this->getReflectionClass();

        // For now, only interested in Public properties, may expand later.
        foreach($reflectionClass->getProperties(\ReflectionProperty::IS_PUBLIC) as $reflectionProperty)
        {
            $this->assertPropertyPHPDocExists($reflectionClass, $reflectionProperty);
            $this->assertCorrectPropertyZeroValue($reflectionClass,$reflectionProperty);
            $this->assertCorrectGetterImplementation($reflectionClass, $reflectionProperty);
            $this->assertCorrectSetterImplementation($reflectionClass, $reflectionProperty);
        }
    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param \ReflectionProperty $reflectionProperty
     */
    protected function assertPropertyPHPDocExists(\ReflectionClass $reflectionClass, \ReflectionProperty $reflectionProperty)
    {
        $className = $reflectionClass->getName();
        $propertyName = $reflectionProperty->getName();

        $this->assertRegExp('/^[A-Z]/S', $propertyName, sprintf(
            'The %s property "%s" is public but does not start with a capital letter.',
            $className,
            $propertyName
        ));

        $propertyDocBlock = $this->docBlockFactory->create($reflectionProperty->getDocComment());

        $this->assertTrue($propertyDocBlock->hasTag('var'), sprintf(
            'The %s property "%s" is missing a @var tag',
            $className,
            $propertyName
        ));

        $this->assertCount(1, $propertyDocBlock->getTagsByName('var'), sprintf(
            'Property "%s" in class "%s" has multiple @var tags defined.',
            $propertyName,
            $className
        ));

    }

    /**
     * @param \ReflectionClass $reflectionClass
     * @param \ReflectionProperty $reflectionProperty
     */
    protected function assertCorrectPropertyZeroValue(\ReflectionClass $reflectionClass, \ReflectionProperty $reflectionProperty)
    {
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

        switch($propertyVarType)
        {
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
                $failMessage = sprintf($failMessageTemplate, sprintf('an instance of %s', $fqsn));

                $isInterface = interface_exists($fqsn, true);
                $isClass = class_exists($fqsn);

                $this->assertTrue($isInterface || $isClass, sprintf(
                    'The property "%s" in class "%s" specifies it\'s type as "%s", but that resource is not auto-loadable.  Is the @var tag a FQSN?',
                    $propertyName,
                    $className,
                    $fqsn
                ));

                $refl = new \ReflectionClass($fqsn);
                if ($refl->isInterface())
                {
                    if (is_object($defaultValue))
                        $this->assertInstanceOf($fqsn, $defaultValue, $failMessage);
                    else
                        $this->assertNull($defaultValue, sprintf('%s or null', $failMessage));
                }
                else
                {
                    $this->assertInstanceOf($fqsn, $defaultValue, $failMessage);
                }
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
    protected function assertCorrectGetterImplementation(\ReflectionClass $reflectionClass, \ReflectionProperty $reflectionProperty)
    {
        /** @var \phpDocumentor\Reflection\Type $propertyVarType */
        /** @var \phpDocumentor\Reflection\DocBlock\Tags\Return_ $methodReturnTag */

        $className = $reflectionClass->getName();
        $propertyName = $reflectionProperty->getName();

        $propertyDocBlock = $this->docBlockFactory->create($reflectionProperty->getDocComment());

        $propertyVarType = $propertyDocBlock->getTagsByName('var')[0]->getType();

        if ($propertyVarType instanceof Boolean)
            $expectedGetterName = sprintf('is%s', ucfirst($propertyName));
        else
            $expectedGetterName = sprintf('get%s', ucfirst($propertyName));

        $this->assertTrue($reflectionClass->hasMethod($expectedGetterName), sprintf(
            'The "%s" property "%s" requires a getter named "%s" be implemented.',
            $className,
            $propertyName,
            $expectedGetterName
        ));

        $methodReflection = $reflectionClass->getMethod($expectedGetterName);

        $this->assertTrue($methodReflection->isPublic(), sprintf(
            'The getter "%s" for property "%s" in class "%s" must be publicly visible',
            $expectedGetterName,
            $propertyName,
            $className
        ));

        $methodDocBlock = $this->docBlockFactory->create($methodReflection->getDocComment());

        $methodReturnTags = $methodDocBlock->getTagsByName('return');
        $this->assertCount(1, $methodReturnTags, sprintf(
            'The getter method "%s" for property "%s in class "%s" must have only 1 @return tag defined.',
            $expectedGetterName,
            $propertyName,
            $className
        ));

        $methodReturnTag = $methodReturnTags[0];
        $methodReturnType = $methodReturnTag->getType();

        $this->assertEquals((string)$propertyVarType, (string)$methodReturnType, sprintf(
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
    protected function assertCorrectSetterImplementation(\ReflectionClass $reflectionClass, \ReflectionProperty $reflectionProperty)
    {
        /** @var \phpDocumentor\Reflection\Type $propertyVarType */
        /** @var \phpDocumentor\Reflection\DocBlock\Tags\Param $methodParamTag */
        /** @var \phpDocumentor\Reflection\DocBlock\Tags\Return_ $methodReturnTag */

        $className = $reflectionClass->getName();
        $propertyName = $reflectionProperty->getName();
        $expectedSetterName = sprintf('set%s', ucfirst($propertyName));

        // TODO: This will only validate methods that match the expected setter name.  Not sure I like that.
        if ($reflectionClass->hasMethod($expectedSetterName))
        {
            // Grab doc block definition from class property declaration
            $propertyDocBlock = $this->docBlockFactory->create($reflectionProperty->getDocComment());
            $propertyVarType = $propertyDocBlock->getTagsByName('var')[0]->getType();

            // Get method reflection
            $reflectionMethod = $reflectionClass->getMethod($expectedSetterName);

            // Validate we have one parameter
            // TODO: Support additional params, maybe?
            $this->assertEquals(1, $reflectionMethod->getNumberOfParameters(), sprintf(
                'Setter "%s" for property "%s" in class "%s" must have at one parameter',
                $expectedSetterName,
                $propertyName,
                $className
            ));
            $this->assertEquals(1, $reflectionMethod->getNumberOfParameters(), sprintf(
                'Setter "%s" for property "%s" in class "%s" must have one required parameter',
                $expectedSetterName,
                $propertyName,
                $className
            ));

            // Get parameter reflection
            $reflectionParameter = $reflectionMethod->getParameters()[0];

            // Validate name
            $parameterName = $reflectionParameter->getName();
            $this->assertEquals($propertyName, $parameterName, sprintf(
                'Setter "%s" for property "%s" in class "%s" must have a parameter with the same name as the property',
                $expectedSetterName,
                $propertyName,
                $className
            ));

            // Get method doc block
            $methodDockBlock = $this->docBlockFactory->create($reflectionMethod->getDocComment());

            // Ensure we have one @param attribute
            $methodParamTags = $methodDockBlock->getTagsByName('param');
            $this->assertCount(1, $methodParamTags, sprintf(
                'Parameter "%s" for setter "%s" for property "%s" in class "%s" must have a "@param" doc block attribute',
                $parameterName,
                $expectedSetterName,
                $propertyName,
                $className
            ));

            // Pull out the @param attribute from the doc block comment
            $methodParamTag = $methodParamTags[0];
            $methodParamType = $methodParamTag->getType();
            $parameterClass = $reflectionParameter->getClass();

            // Finally, attempt to validate setter declaration sanity
            switch(true)
            {
                // If the setter is designed to expect more than 1 type of variable, ensure that
                // there is no type-hinting going on.
                case $methodParamType instanceof Compound:
                    $this->assertNull($parameterClass, sprintf(
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
                    $this->assertNull($parameterClass, sprintf(
                        'Parameter "%s" in setter "%s" for property "%s" in class "%s" must not use type-hinting to maintain php 5 compatibility',
                        $parameterName,
                        $expectedSetterName,
                        $propertyName,
                        $className
                    ));
                    break;

                case $propertyVarType instanceof Array_:
                    //                    var_dump('array', $parameterClass, $propertyVarType);
                    //                    echo "\n";
                    break;

                case $propertyVarType instanceof Object_:
                    //                    var_dump('object', $parameterClass, $propertyVarType);
                    //                    echo "\n";
                    break;

                default:

            }
        }
    }
}