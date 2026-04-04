<?php

namespace DCarbone\PHPConsulAPITests\Unit\ConfigEntry;

use DCarbone\PHPConsulAPI\ConfigEntry\LogSinkType;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class LogSinkTypeTest extends TestCase
{

    public function testDefaultCase(): void
    {
        $e = LogSinkType::Default;
        self::assertSame('', $e->value);
    }

    public function testDefaultFromString(): void
    {
        $e = LogSinkType::from('');
        self::assertSame(LogSinkType::Default, $e);
    }

    public function testFileCase(): void
    {
        $e = LogSinkType::File;
        self::assertSame('file', $e->value);
    }

    public function testFileFromString(): void
    {
        $e = LogSinkType::from('file');
        self::assertSame(LogSinkType::File, $e);
    }

    public function testStdErrCase(): void
    {
        $e = LogSinkType::StdErr;
        self::assertSame('stderr', $e->value);
    }

    public function testStdErrFromString(): void
    {
        $e = LogSinkType::from('stderr');
        self::assertSame(LogSinkType::StdErr, $e);
    }

    public function testStdOutCase(): void
    {
        $e = LogSinkType::StdOut;
        self::assertSame('stdout', $e->value);
    }

    public function testStdOutFromString(): void
    {
        $e = LogSinkType::from('stdout');
        self::assertSame(LogSinkType::StdOut, $e);
    }
}
