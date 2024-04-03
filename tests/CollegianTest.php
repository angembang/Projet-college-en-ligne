<?php

// Declaration of strict types mode
declare(strict_types=1);

// Importing the base class for PHPUnit unit tests
use PHPUnit\Framework\TestCase;


/**
 * Test class for the Collegian entity.
 */
class CollegianTest extends TestCase
{
    /**
     * Tests the creation of a Collegian instance with valid attributes.
     */
    public function testCanBeCreatedFromValidAttribute(): void
    {
        // Testing the creation of a collegian instance with valid values
        $firstName = "valid firstName";
        $lastName = "valid lastName";
        $email = "example@gmail.com";
        $password = "MamanRosa1.&";
        $idClass = 2;
        $idLanguage = 3;
        $idRole = 5;
      
        // Ensuring the creation of a valid collegian instance
        $collegian = Collegian::ensureIsValidCollegian( $firstName, $lastName, $email, $password, $idClass, $idLanguage, $idRole);
        
        // Assertions to ensure the correctness of the collegian instance
        $this->assertInstanceOf(Collegian::class, $collegian);
        $this->assertSame($firstName, $collegian->getFirstName());
        $this->assertSame($lastName, $collegian->getLastName());
        $this->assertSame($email, $collegian->getEmail());
        $this->assertSame($password, $collegian->getPassword());
        $this->assertSame($idClass, $collegian->getIdClass());
        $this->assertSame($idLanguage, $collegian->getIdLanguage());
        $this->assertSame($idRole, $collegian->getIdRole());
    }
    
    
    /**
     * Tests the creation of a Collegian instance with an empty first name.
     */
    public function testCanNotBeCreatedFromEmptyFirstName(): void
    {
        // Expecting an InvalidArgumentException when creating a collegian with an empty first name
        $this->expectException(InvalidArgumentException::class);
        Collegian::ensureIsValidCollegian("", "lastName", "email", "password", 2, 3, 4);
    }
    
    
    /**
     * Tests the creation of a Collegian instance with an empty last name.
     */
    public function testCanNotBeCreatedFromEmptyLastName(): void
    {
        // Expecting an InvalidArgumentException when creating a collegian with an empty last name
        $this->expectException(InvalidArgumentException::class);
        Collegian::ensureIsValidCollegian("firstName", "", "email", "password", 2, 3, 5);
    }
    
    /**
     * Tests the creation of a Collegian instance with an invalid email.
     */
    public function testCanNotBeCreatedFromInvalidEmail(): void
    {
        // Expecting an InvalidArgumentException when creating a collegian with an invalid email
        $this->expectException(InvalidArgumentException::class);
        Collegian::ensureIsValidCollegian("firstName", "lastName", "love3333", "password", 2, 3, 5);
    }
    
    /**
     * Tests the creation of a Collegian instance with an invalid password.
     */
    public function testCanNotBeCreatedFromInvalidPassword(): void
    {
        // Expecting an InvalidArgumentException when creating a collegian with an invalid password
        $this->expectException(InvalidArgumentException::class);
        Collegian::ensureIsValidCollegian("firstName", "lastName", "email", "MamanRosa", 2, 3, 4);
    }
    
    /**
     * Tests the creation of a Collegian instance with an invalid role.
     */
    public function testCanNotBeCreatedFromInvalidRoles(): void
    {
        // Expecting an InvalidArgumentException when creating a collegian with an invalid role
        $this->expectException(InvalidArgumentException::class);
        Collegian::ensureIsValidCollegian("firstName", "lastName", "email", "password", 2, 3, 2);
    }
}