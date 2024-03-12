<?php
/**
 * Defines a collegian in the platform.
 */
class Collegian
{
    /** @var int|null The unique identifier of the collegian. 
    */
    private ?int $id;

    /** @var string First name of the collegian. 
    */
    private string $firstName;

    /** @var string Last name of the collegian. 
    */
    private string $lastName;

    /** @var string Email address of the collegian. 
    */
    private string $email;

    /** @var string Password for the collegian. 
    */
    private string $password;

    /** @var int The unique identifier of the class associated with the collegian. 
    */
    private int $idClass;

    /** @var int The unique identifier of the language associated with the collegian. 
    */
    private int $idLanguage;

    /** @var int The unique identifier of the role assigned to the collegian. 
    */
    private int $idRole;

    /**
     * Constructor for the Collegian class.
     *
     * @param int|null $id The unique identifier of the collegian.
     * @param string $firstName First name of the collegian.
     * @param string $lastName Last name of the collegian.
     * @param string $email Email address of the collegian.
     * @param string $password Password for the collegian.
     * @param int $idClass The unique identifier of the class associated with the collegian.
     * @param int $idLanguage The unique identifier of the language associated with the collegian.
     * @param int $idRole The unique identifier of the role assigned to the collegian.
     */
    public function __construct(
        ?int $id,
        string $firstName,
        string $lastName,
        string $email,
        string $password,
        int $idClass,
        int $idLanguage,
        int $idRole
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->idClass = $idClass;
        $this->idLanguage = $idLanguage;
        $this->idRole = $idRole;
    }

    /**
     * Get the unique identifier of the collegian.
     *
     * @return int|null The unique identifier of the collegian.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the unique identifier of the collegian.
     *
     * @param int|null $id The unique identifier of the collegian.
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the first name of the collegian.
     *
     * @return string First name of the collegian.
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Set the first name of the collegian.
     *
     * @param string $firstName First name of the collegian.
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * Get the last name of the collegian.
     *
     * @return string Last name of the collegian.
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Set the last name of the collegian.
     *
     * @param string $lastName Last name of the collegian.
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * Get the email address of the collegian.
     *
     * @return string Email address of the collegian.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the email address of the collegian.
     *
     * @param string $email Email address of the collegian.
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Get the password for the collegian.
     *
     * @return string Password for the collegian.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the password for the collegian.
     *
     * @param string $password Password for the collegian.
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Get the unique identifier of the class associated with the collegian.
     *
     * @return int The unique identifier of the class associated with the collegian.
     */
    public function getIdClass(): int
    {
        return $this->idClass;
    }

    /**
     * Set the unique identifier of the class associated with the collegian.
     *
     * @param int $idClass The unique identifier of the class associated with the collegian.
     */
    public function setIdClass(int $idClass): void
    {
        $this->idClass = $idClass;
    }

    /**
     * Get the unique identifier of the language associated with the collegian.
     *
     * @return int The unique identifier of the language associated with the collegian.
     */
    public function getIdLanguage(): int
    {
        return $this->idLanguage;
    }

    /**
     * Set the unique identifier of the language associated with the collegian.
     *
     * @param int $idLanguage The unique identifier of the language associated with the collegian.
     */
    public function setIdLanguage(int $idLanguage): void
    {
        $this->idLanguage = $idLanguage;
    }

    /**
     * Get the unique identifier of the role assigned to the collegian.
     *
     * @return int The unique identifier of the role assigned to the collegian.
     */
    public function getIdRole(): int
    {
        return $this->idRole;
    }

    /**
     * Set the unique identifier of the role assigned to the collegian.
     *
     * @param int $idRole The unique identifier of the role assigned to the collegian.
     */
    public function setIdRole(int $idRole): void
    {
        $this->idRole = $idRole;
    }
}