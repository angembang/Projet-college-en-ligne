<?php

/**
 * Defines a language in the platform.
 */
class Language
{
    /** @var int The unique identifier of the language. 
     */
    private int $id;

    /** @var string The name of the language. 
    */
    private string $name;

    /**
     * Constructor for the Language class.
     *
     * @param int $id The unique identifier of the language.
     * @param string $name The name of the language.
     */
    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * Get the unique identifier of the language.
     *
     * @return int The unique identifier of the language.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the unique identifier of the language.
     *
     * @param int $id The unique identifier of the language.
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the name of the language.
     *
     * @return string The name of the language.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the name of the language.
     *
     * @param string $name The name of the language.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }
}