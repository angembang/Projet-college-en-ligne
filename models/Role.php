<?php

// Strict type declaration
declare(strict_types=1);

/**
 * Defines a role in the platform.
 */
class Role
{
    /**
     * @var int|null The unique identifier of the role. Null for a new role (not yet stored in the database).
     */
    private ?int $id;

    /**
     * @var string The name of the role.
     */
    private string $name;

    /**
     * Role constructor.
     *
     * @param int|null $id The unique identifier of the role.
     * @param string $name The name of the role.
     */
    public function __construct(?int $id, string $name)
    {
        // Check provided values
        $this->ensureIsEmptyName($name);
        // Initialization of properties
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Static method to create an instance of Role.
     *
     * @param string $name The name of the role.
     * @return self
     */
    public static function ensureIsValidRole(string $name): self
    {
        return new self(null, $name);
    }

    /**
     * Private method to check if the name is empty.
     *
     * @param string $name The name of the role.
     * @throws InvalidArgumentException
     */
    private function ensureIsEmptyName(string $name): void
    {
        if (empty($name)) {
            throw new InvalidArgumentException("Name can not be empty");
        }
    }

    /**
     * Get the unique identifier of the role.
     *
     * @return int|null The unique identifier of the role.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the unique identifier of the role.
     *
     * @param int|null $id The unique identifier of the role.
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the name of the role.
     *
     * @return string The name of the role.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the name of the role.
     *
     * @param string $name The name of the role.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}