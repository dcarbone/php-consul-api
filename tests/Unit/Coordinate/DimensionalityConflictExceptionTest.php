<?php
namespace DCarbone\PHPConsulAPITests\Unit\Coordinate;
use DCarbone\PHPConsulAPI\Coordinate\DimensionalityConflictException;
use PHPUnit\Framework\TestCase;
/**
 * @internal
 */
final class DimensionalityConflictExceptionTest extends TestCase
{
    public function testDefaultMessage(): void
    {
        $e = new DimensionalityConflictException();
        self::assertSame(DimensionalityConflictException::DefaultMessage, $e->getMessage());
        self::assertSame(0, $e->getCode());
        self::assertNull($e->getPrevious());
    }
    public function testCustomMessage(): void
    {
        $e = new DimensionalityConflictException(message: 'custom error');
        self::assertSame('custom error', $e->getMessage());
    }
    public function testCustomCode(): void
    {
        $e = new DimensionalityConflictException(code: 42);
        self::assertSame(42, $e->getCode());
    }
    public function testWithPrevious(): void
    {
        $prev = new \RuntimeException('prev');
        $e = new DimensionalityConflictException(previous: $prev);
        self::assertSame($prev, $e->getPrevious());
    }
    public function testIsDomainException(): void
    {
        $e = new DimensionalityConflictException();
        self::assertInstanceOf(\DomainException::class, $e);
    }
}
