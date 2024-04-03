<?php

/**
 * Defines a course of a lesson in the platform.
 */
class Course
{
    /** @var int|null The unique identifier of the course. 
    */
    private ?int $id;

    /** @var int The unique identifier of the lesson associated with the course. 
    */
    private int $idLesson;

    /** @var string|null The date when the course will be unlocked. 
    */
    private string $unlockDate;

    /** @var string The subject of the course. 
    */
    private string $subject;

    /** @var string The content of the course. 
    */
    private string $content;


    /**
     * Constructor for the Course class.
     *
     * @param int|null $id The unique identifier of the course.
     * @param int $idLesson The unique identifier of the lesson associated with the course.
     * @param string|null $unlockDate The date when the course is unlocked.
     * @param string $subject The subject of the course.
     * @param string $content The content of the course.
     */
    public function __construct(
        ?int $id,
        int $idLesson,
        string $unlockDate,
        string $subject,
        string $content
        ) {
        $this->id = $id;
        $this->idLesson = $idLesson;
        $this->unlockDate = $unlockDate;
        $this->subject = $subject;
        $this->content = $content;
    }

    /**
     * Get the unique identifier of the course.
     *
     * @return int|null The unique identifier of the course.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the unique identifier of the course.
     *
     * @param int|null $id The unique identifier of the course.
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the unique identifier of the lesson associated with the course.
     *
     * @return int The unique identifier of the lesson associated with the course.
     */
    public function getIdLesson(): int
    {
        return $this->idLesson;
    }

    /**
     * Set the unique identifier of the lesson associated with the course.
     *
     * @param int $idLesson The unique identifier of the lesson associated with the course.
     */
    public function setIdLesson(int $idLesson): void
    {
        $this->idLesson = $idLesson;
    }

    /**
     * Get the date when the course wille be unlocked.
     *
     * @return string The date when the course will be unlocked.
     */
    public function getUnlockDate(): ?string
    {
        return $this->unlockDate;
    }

    /**
     * Set the date when the course is unlocked.
     *
     * @param string $unlockDate The date when the course will be unlocked.
     */
    public function setUnlockDate(?string $unlockDate): void
    {
        $this->unlockDate = $unlockDate;
    }

    /**
     * Get the subject of the course.
     *
     * @return string The subject of the course.
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * Set the subject of the course.
     *
     * @param string $subject The subject of the course.
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * Get the content of the course.
     *
     * @return string The content of the course.
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set the content of the course.
     *
     * @param string $content The content of the course.
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    
}