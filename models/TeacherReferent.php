<?php

/**
 * Defines a teacherReferent in the platform.
 */
class TeacherReferent
{
    /**
     * @var int|null The unique identifier of the teacherReferent. Null for a new teacherReferent (not yet stored in the database).
     */
    private ?int $id;

    /**
     * @var string The first name of the teacherReferent.
     */
    private string $firstName;

    /**
     * @var string The last name of the teacherReferent.
     */
    private string $lastName;

    /**
     * @var string The email of the teacherReferent.
     */
    private string $email;

    /**
     * @var string The password of the teacherReferent.
     */
    private string $password;

    /**
     * @var int The unique identifier of the class.
     */
    private int $idClass;

    /**
     * @var int The role ID of the teacherReferent.
     */
    private int $idRole;

    /**
     * TeacherReferent constructor.
     *
     * @param int|null $id The unique identifier of the teacherReferent. 
     * @param string $firstName The first name of the teacherReferent.
     * @param string $lastName The last name of the teacherReferent.
     * @param string $email The email of the teacherReferent.
     * @param string $password The password of the teacherReferent.
     * @param int $idClass The unique identifier of the class.
     * @param int $idRole The role ID of the teacherReferent.
     */
    public function __construct(?int $id, string $firstName, string $lastName, string $email, string $password, int $idClass, int $idRole)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->password = $password;
        $this->idClass = $idClass;
        $this->idRole = $idRole;
    }

    /**
     * Get the unique identifier of the teacherReferent.
     *
     * @return int|null The unique identifier of the teacherReferent.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the unique identifier of the teacherReferent.
     *
     * @param int|null $id The unique identifier of the teacherReferent.
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the first name of the teacherReferent.
     *
     * @return string The first name of the teacherReferent.
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * Set the first name of the teacherReferent.
     *
     * @param string $firstName The first name of the teacherReferent.
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * Get the last name of the teacherReferent.
     *
     * @return string The last name of the teacherReferent.
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * Set the last name of the teacherReferent.
     *
     * @param string $lastName The last name of teacherReferent.
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * Get the email of the teacherReferent.
     *
     * @return string The email of the teacherReferent.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the email of the teacherReferent.
     *
     * @param string $email The email of the teacherReferent.
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Get the password of the teacherReferent.
     *
     * @return string The password of the teacherReferent.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the password of the teacherReferent.
     *
     * @param string $password The password of the teacherReferent.
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Get the unique identifier of the class.
     *
     * @return int The unique identifier of the class.
     */
    public function getIdClass(): int
    {
        return $this->idClass;
    }

    /**
     * Set the unique identifier of the class.
     *
     * @param int $idClass The unique identifier of the class.
     */
    public function setIdClass(int $idClass): void
    {
        $this->idClass = $idClass;
    }

    /**
     * Get the role ID of the teacherReferent.
     *
     * @return int The role ID of the teacherReferent.
     */
    public function getIdRole(): int
    {
        return $this->idRole;
    }

    /**
     * Set the role ID of the teacherReferent.
     *
     * @param int $idRole The role ID of the teacherReferent.
     */
    public function setIdRole(int $idRole): void
    {
        $this->idRole = $idRole;
    }
}