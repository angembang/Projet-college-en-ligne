<?php

// Déclaration du mode strict des types
declare(strict_types=1);

// Importation de la classe de base pour les tests unitaires de PHPUnit
use PHPUnit\Framework\TestCase;

class CollegianTest extends TestCase
{
    public function testCanBeCreatedFromValidAttribute(): void
    {
        // test de création d'une instance collegien avec des valeurs valides
        $firstName = "valid firstName";
        $lastName = "valid lastName";
        $email = "example@gmail.com";
        $password = "MamanRosa1.&";
        $idClass = 2;
        $idLanguage = 3;
        $idRole = 4;
      
        $collegian = Collegian::ensureIsValidCollegian( $firstName, $lastName, $email, $password, $idClass, $idLanguage, $idRole);
        
        $this->assertInstanceOf(Collegian::class, $collegian);
        $this->assertSame($firstName, $collegian->getFirstName());
        $this->assertSame($lastName, $collegian->getLastName());
        $this->assertSame($email, $collegian->getEmail());
        $this->assertSame($password, $collegian->getPassword());
        $this->assertSame($idClass, $collegian->getIdClass());
        $this->assertSame($idLanguage, $collegian->getIdLanguage());
        $this->assertSame($idRole, $collegian->getIdRole());
    }
    
    
    // Teste la création d'une instance de collegian avec un firstName vide
    public function testCanNotBeCreatedFromEmptyFirstName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Collegian::ensureIsValidCollegian("", "lastName", "email", "password", 2, 3, 4);
    }
    
    
    // Teste la création d'une instance de user avec un lastName vide
    public function testCanNotBeCreatedFromEmptyLastName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Collegian::ensureIsValidCollegian("firstName", "", "email", "password", 2, 3, 4);
    }
    
    // Teste la création d'une instance de collegian avec un email invalide
    public function testCanNotBeCreatedFromInvalidEmail(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Collegian::ensureIsValidCollegian("firstName", "lastName", "love3333", "password", 2, 3, 4);
    }
    
    // Teste la création d'une instance de collegian avec un pasword invalide
    public function testCanNotBeCreatedFromInvalidPassword(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Collegian::ensureIsValidCollegian("firstName", "lastName", "email", "MamanRosa", 2, 3, 4);
    }
    
    // Teste la création d'une instance de collegian avec invalid role
    public function testCanNotBeCreatedFromInvalidRoles(): void
    {
        $this->expectException(InvalidArgumentException::class);
        Collegian::ensureIsValidCollegian("firstName", "lastName", "email", "password", 2, 3, 2);
    }
}