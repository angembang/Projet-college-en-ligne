<?php

/**
 * Defines a teacher in the platform.
 */
class Teacher
{
    /**
     * @var int|null The unique identifier of the teacher. Null for a new teacher (not yet stored in the database).
     */
    private ?int $id;

    /**
     * @var string The first name of the teacher.
     */
    private string $firstName;

    /**
     * @var string The last name of the teacher.
     */
    private string $lastName;

    /**
     * @var string The email of the teacher.
     */
    private string $email;

    /**
     * @var string The password of the teacher.
     */
    private string $password;

    /**
     * @var int The role ID of the teacher.
     */
    private int $idRole;

    /**
     * Teacher constructor.
     *
     * @param int|null $id The unique identifier of the teacher. Null for a new teacher (not yet stored in the database).
     * @param string $firstName The first name of the teacher.
     * @param string $lastName The last name of the teacher.
     * @param string $email The email of the teacher.
     * @param string $password The password of the teacher.
     * @param int $idRole The role ID of the teacher.
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
     * Get the unique identifier of the teacher.
     *
     * @return int|null The unique identifier of the teacher. Null for a new teacher.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the unique identifier of the teacher.
     *
     * @param int|null $id The unique identifier of the teacher.
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the first name of the teacher.
     *
     * @return string The first name of the teacher.
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Set the first name of the teacher.
     *
     * @param string $firstName The first name of the teacher.
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * Get the last name of the teacher.
     *
     * @return string The last name of the teacher.
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Set the last name of the teacher.
     *
     * @param string $lastName The last name of teacher.
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * Get the email of the teacher.
     *
     * @return string The email of the teacher.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the email of the teacher.
     *
     * @param string $email The email of the teacher.
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Get the password of the teacher.
     *
     * @return string The password of the teacher.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the password of the teacher.
     *
     * @param string $password The password of the teacher.
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Get the role ID of the teacher.
     *
     * @return int The role ID of the teacher.
     */
    public function getIdRole(): int
    {
        return $this->idRole;
    }

    /**
     * Set the role ID of the teacher.
     *
     * @param int $idRole The role ID of the teacher.
     */
    public function setIdRole(int $idRole): void
    {
        $this->idRole = $idRole;
    }
    
}