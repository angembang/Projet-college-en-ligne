<?php

/**
 * Definess a Principal in the platform.
 */
class Principal
{
    /**
     * @var int|null The unique identifier of the principal. Null for a new principal (not yet stored in the database).
     */
    private ?int $id;

    /**
     * @var string The first name of the principal.
     */
    private string $firstName;

    /**
     * @var string The last name of the principal.
     */
    private string $lastName;

    /**
     * @var string The email of the principal.
     */
    private string $email;

    /**
     * @var string The password of the principal.
     */
    private string $password;

    /**
     * @var int The role ID of the principal.
     */
    private int $idRole;

    /**
     * Principal constructor.
     *
     * @param int|null $id The unique identifier of the principal. Null for a new principal (not yet stored in the database).
     * @param string $firstName The first name of the principal.
     * @param string $lastName The last name of the principal.
     * @param string $email The email of the principal.
     * @param string $password The password of the principal.
     * @param int $idRole The role ID of the principal.
     */
    public function __construct(?int $id, string $firstName, string $lastName, string $email, string $password, int $idRole)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->idRole = $idRole;
    }

    /**
     * Get the unique identifier of the principal.
     *
     * @return int|null The unique identifier of the principal. Null for a new principal.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the unique identifier of the principal.
     *
     * @param int|null $id The unique identifier of the principal.
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the first name of the principal.
     *
     * @return string The first name of the principal.
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Set the first name of the principal.
     *
     * @param string $firstName The first name of the principal.
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * Get the last name of the principal.
     *
     * @return string The last name of the principal.
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Set the last name of the principal.
     *
     * @param string $lastName The last name of the principal.
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * Get the email of the principal.
     *
     * @return string The email of the principal.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the email of the principal.
     *
     * @param string $email The email of the principal.
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Get the password of the principal.
     *
     * @return string The password of the principal.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the password of the principal.
     *
     * @param string $password The password of the principal.
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Get the role ID of the principal.
     *
     * @return int The role ID of the principal.
     */
    public function getIdRole(): int
    {
        return $this->idRole;
    }

    /**
     * Set the role ID of the principal.
     *
     * @param int $idRole The role ID of the principal.
     */
    public function setIdRole(int $idRole): void
    {
        $this->idRole = $idRole;
    }
    
}