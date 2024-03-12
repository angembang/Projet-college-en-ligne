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
     * @var int The unique identifier of the teacher.
     */
    private int $idTeacher;

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
     * @param int $idTeacher The unique identifier of the teacher.
     * @param int $idClass The unique identifier of the class.
     * @param int $idRole The role ID of the teacherReferent.
     */
    public function __construct(?int $id, int $idTeacher, int $idClass, int $idRole)
    {
        $this->id = $id;
        $this->idTeacher = $idTeacher;
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
     * Get the unique identifier of the teacher.
     *
     * @return int The unique identifier of the teacher.
     */
    public function getIdTeacher(): int
    {
        return $this->idTeacher;
    }

    /**
     * Set the unique identifier of the teacher.
     *
     * @param int $idTeacher The unique identifier of the teacher.
     */
    public function setIdTeacher(int $idTeacher): void
    {
        $this->idTeacher = $idTeacher;
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