# Everywhere within this repository:
* **Always** use `null|` in favor of `?` for nullable types (e.g. `?string` instead of `string|null`).
* **Always** use variadic parameters instead of `array` for lists of items (e.g. `string ...$items` instead
   of `array $items`).
* **Always** use `self` instead of the class name in method signatures (e.g. 
   `public function setFoo(string $foo): self` instead of `public function setFoo(string $foo): MyClass`).
* **Always** always match casing of class properties when defining parameters and methods (e.g. 
   `public function setFooBar(string $FooBar)` if the class property is `$FooBar`).
* **Always** define a phpdoc for `array` types that specifies the item type (e.g. 
   `/** @param string[] $items */` for `array $items`).
* **Never** edit files using sed, awk, or other shell utilities.
* **Always** limit yourself to POSIX shell syntax when executing shell scripts.
* **Always** run phpstan with 512MB of memory when analyzing generated code 
   (e.g. `phpstan analyze -c phpstan.neon --memory-limit=512M src/`).
* **Always** specify `: void` for methods that do not return a value (e.g. `public function setFoo(string $foo): void`).
   * This includes unit test methods (e.g. `public function testFoo(): void`).
* **Always** use `declare(strict_types=1);` at the top of all PHP files.
   * Do not do this in PHPUnit test class files, as PHPUnit does not support strict types.
* **Always** ensure that constructor parameters for enum fields also accept the enum value type 
   (e.g. `public function __construct(string $foo, string|MyEnum $bar)`).
  * Write tests for both cases.
* **Never** leave imports unused in generated code.
* **Always** used named parameters wherever possible.
* **Never** bother with docblocks unless they are necessary to specify types that cannot be expressed in code
   (e.g. `/** @param string[] $items */` for `array $items`).
* **Never** call `jsonSerialize` directly in generated code.

# When generting code for concrete implementations of AbstractType:
* **Always** ensure that class fields have an associated getter and setter method.
* **Always** ensure the constructor has parameters for all class fields, and that the constructor parameters are
   assigned to the class fields.
* **Always** write unit tests that use both the constructor and the setter methods to set class fields,
   and that use the getter methods and fields directly to verify that the fields were set correctly.