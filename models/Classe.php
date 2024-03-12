<?php

/**
 * Defines a class in the platform.
 */
class Classe
{
    /** @var int The unique identifier of the classe. 
    */
    private int $id;

    /** @var string The level of the classe. 
    */
    private string $level;

    /** @var int The unique identifier of the language associated with the classe. 
    */
    private int $idLanguage;

    /**
     * Constructor for the Class classe.
     *
     * @param int $id The unique identifier of the classe.
     * @param string $level The level of the class.
     * @param int $idLanguage The unique identifier of the language associated with the classe.
     */
    public function __construct(int $id, string $level, int $idLanguage)
    {
        $this->id = $id;
        $this->level = $level;
        $this->idLanguage = $idLanguage;
    }

    /**
     * Get the unique identifier of the classe.
     *
     * @return int The unique identifier of the classe.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the unique identifier of the classe.
     *
     * @param int $id The unique identifier of the classe.
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the level of the classe.
     *
     * @return string The level of the classe.
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * Set the level of the classe.
     *
     * @param string $level The level of the classe.
     */
    public function setLevel(string $level): void
    {
        $this->level = $level;
    }

    /**
     * Get the unique identifier of the language associated with the classe.
     *
     * @return int The unique identifier of the language associated with the classe.
     */
    public function getIdLanguage(): int
    {
        return $this->idLanguage;
    }

    /**
     * Set the unique identifier of the language associated with the classe.
     *
     * @param int $idLanguage The unique identifier of the language associated with the classe.
     */
    public function setIdLanguage(int $idLanguage): void
    {
        $this->idLanguage = $idLanguage;
    }
}