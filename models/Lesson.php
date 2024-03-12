<?php

/**
 * Defines a lesson in the platform.
 */
class Lesson
{
    /** @var int|null The unique identifier of the lesson. 
    */
    private ?int $id;

    /** @var string The name of the lesson. 
    */
    private string $name;

    /** @var int The unique identifier of the class associated with the lesson. 
    */
    private int $idClass;

    /** @var int The unique identifier of the teacher assigned to the lesson. 
    */
    private int $idTeacher;

    /** @var int The unique identifier of the timetable entry for the lesson. 
    */
    private int $idTimeTable;

    /**
     * Constructor for the Lesson class.
     *
     * @param int|null $id The unique identifier of the lesson.
     * @param string $name The name of the lesson.
     * @param int $idClass The unique identifier of the class associated with the lesson.
     * @param int $idTeacher The unique identifier of the teacher assigned to the lesson.
     * @param int $idTimeTable The unique identifier of the timetable entry for the lesson.
     */
    public function __construct(?int $id, string $name, int $idClass, int $idTeacher, int $idTimeTable)
    {
        $this->id = $id;
        $this->name = $name;
        $this->idClass = $idClass;
        $this->idTeacher = $idTeacher;
        $this->idTimeTable = $idTimeTable;
    }

    /**
     * Get the unique identifier of the lesson.
     *
     * @return int|null The unique identifier of the lesson.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the unique identifier of the lesson.
     *
     * @param int|null $id The unique identifier of the lesson.
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the name of the lesson.
     *
     * @return string The name of the lesson.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the name of the lesson.
     *
     * @param string $name The name of the lesson.
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the unique identifier of the class associated with the lesson.
     *
     * @return int The unique identifier of the class associated with the lesson.
     */
    public function getIdClass(): int
    {
        return $this->idClass;
    }

    /**
     * Set the unique identifier of the class associated with the lesson.
     *
     * @param int $idClass The unique identifier of the class associated with the lesson.
     */
    public function setIdClass(int $idClass): void
    {
        $this->idClass = $idClass;
    }

    /**
     * Get the unique identifier of the teacher assigned to the lesson.
     *
     * @return int The unique identifier of the teacher assigned to the lesson.
     */
    public function getIdTeacher(): int
    {
        return $this->idTeacher;
    }

    /**
     * Set the unique identifier of the teacher assigned to the lesson.
     *
     * @param int $idTeacher The unique identifier of the teacher assigned to the lesson.
     */
    public function setIdTeacher(int $idTeacher): void
    {
        $this->idTeacher = $idTeacher;
    }

    /**
     * Get the unique identifier of the timetable entry for the lesson.
     *
     * @return int The unique identifier of the timetable entry for the lesson.
     */
    public function getIdTimeTable(): int
    {
        return $this->idTimeTable;
    }

    /**
     * Set the unique identifier of the timetable entry for the lesson.
     *
     * @param int $idTimeTable The unique identifier of the timetable entry for the lesson.
     */
    public function setIdTimeTable(int $idTimeTable): void
    {
        $this->idTimeTable = $idTimeTable;
    }
}