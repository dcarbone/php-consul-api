<?php namespace DCarbone\PHPConsulAPITests\Usage;

/*
   Copyright 2016-2021 Daniel Carbone (daniel.p.carbone@gmail.com)

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

use DCarbone\PHPConsulAPI\Values;
use PHPUnit\Framework\TestCase;

/**
 * Class ValuesUsageTest
 *
 * @internal
 */
final class ValuesUsageTest extends TestCase
{
    public function testCanConstruct(): void
    {
        $values = new Values();
        static::assertInstanceOf(Values::class, $values);
    }

    /**
     * @depends testCanConstruct
     */
    public function testCanAddValue(): void
    {
        $v = new Values();
        $v->add('test', 'value');
    }

    /**
     * @depends testCanConstruct
     */
    public function testExceptionThrownWhenAddingInvalidKey(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $v = new Values();
        $v->add(1234, 'whatever');
    }

    /**
     * @depends testCanConstruct
     */
    public function testExceptionThrownWhenAddingInvalidValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $v = new Values();
        $v->add('test', new \stdClass());
    }

    /**
     * @depends testCanAddValue
     */
    public function testCanGetAll(): void
    {
        $values = new Values();
        $values->add('test', 'value1');
        $values->add('test', 'value2');
        $values->add('test', 'value3');

        $all = $values->getAll('test');
        static::assertIsArray($all);
        static::assertCount(3, $all);
    }
}
