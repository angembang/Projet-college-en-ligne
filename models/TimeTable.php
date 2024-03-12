<?php

/**
 * Defines a timetable of a lesson in the platform.
 */
class TimeTable
{
    /** @var int|null the unique identifier of the time table.
     */
    private ?int $id;

    /** @var string Day of the week.
     */
    private string $weekDay;

    /** @var string Start time in TIME format from the database. 
    */
    private string $startTime;

    /** @var string End time in TIME format from the database. 
    */
    private string $endTime;

    /**
     * Constructor for the TimeTable class.
     *
     * @param int|null $id the unique identifier of the time table.
     * @param string $weekDay Day of the week.
     * @param string $startTime the Start time of a lesson.
     * @param string $endTime the End time of a lesson.
     */
    public function __construct(?int $id, string $weekDay, string $startTime, string $endTime)
    {
        $this->id = $id;
        $this->weekDay = $weekDay;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }

    /**
     * Get the unique identifier of the time table. Null for a new time table
     *
     * @return int|null the unique identifier of the time table.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the unique identifier of the time table.
     *
     * @param int|null $id the unique identifier of the time table.
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the day of the week.
     *
     * @return string Day of the week.
     */
    public function getWeekDay(): string
    {
        return $this->weekDay;
    }

    /**
     * Set the day of the week.
     *
     * @param string $weekDay Day of the week.
     */
    public function setWeekDay(string $weekDay): void
    {
        $this->weekDay = $weekDay;
    }

    /**
     * Get the start time.
     *
     * @return string Start time.
     */
    public function getStartTime(): string
    {
        return $this->startTime;
    }

    /**
     * Set the start time.
     *
     * @param string $startTime Start time.
     */
    public function setStartTime(string $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * Get the end time.
     *
     * @return string End time.
     */
    public function getEndTime(): string
    {
        return $this->endTime;
    }

    /**
     * Set the end time.
     *
     * @param string $endTime End time.
     */
    public function setEndTime(string $endTime): void
    {
        $this->endTime = $endTime;
    }
}