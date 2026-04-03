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