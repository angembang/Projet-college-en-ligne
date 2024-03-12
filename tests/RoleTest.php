<?php

// Strict type declaration
declare(strict_types=1);

// Import the base class for PHPUnit unit tests
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    /**
     * Test if Role can be created from valid attributes.
     */
    public function testCanBeCreatedFromValidAttribute(): void
    {
        // Test the creation of a Role instance with valid values
        $name = "valid name";

        $role = Role::ensureIsValidRole($name);

        $this->assertInstanceOf(Role::class, $role);
        $this->assertSame($name, $role->getName());
    }

    /**
     * Test if Role cannot be created from an empty name.
     */
    public function testCanNotBeCreatedFromEmptyName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Role::ensureIsValidRole("");
    }
}