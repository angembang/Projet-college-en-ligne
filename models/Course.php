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

    /** @var string The summary of the course. 
    */
    private string $summary;

    /** @var string The content of the course. 
    */
    private string $content;

    /** @var string The images associated with the course. 
    */
    private string $images;

    /** @var string The audio file of the course. 
    */
    private string $audio;

    /** @var string The video associated with the course. 
    */
    private string $video;

    /** @var string The PDF file of the course. 
    */
    private string $fichierPDF;

    /** @var string The link associated with the course. 
    */
    private string $link;

    /**
     * Constructor for the Course class.
     *
     * @param int|null $id The unique identifier of the course.
     * @param int $idLesson The unique identifier of the lesson associated with the course.
     * @param string|null $unlockDate The date when the course is unlocked.
     * @param string $subject The subject of the course.
     * @param string $summary The summary of the course.
     * @param string $content The content of the course.
     * @param string $images The images associated with the course.
     * @param string $audio The audio file of the course.
     * @param string $video The video associated with the course.
     * @param string $fichierPDF The PDF file of the course.
     * @param string $link The link associated with the course.
     */
    public function __construct(
        ?int $id,
        int $idLesson,
        string $unlockDate,
        string $subject,
        string $summary,
        string $content,
        string $images,
        string $audio,
        string $video,
        string $fichierPDF,
        string $link
    ) {
        $this->id = $id;
        $this->idLesson = $idLesson;
        $this->unlockDate = $unlockDate;
        $this->subject = $subject;
        $this->summary = $summary;
        $this->content = $content;
        $this->images = $images;
        $this->audio = $audio;
        $this->video = $video;
        $this->fichierPDF = $fichierPDF;
        $this->link = $link;
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
     * Get the summary of the course.
     *
     * @return string The summary of the course.
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * Set the summary of the course.
     *
     * @param string $summary The summary of the course.
     */
    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
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

    /**
     * Get the images associated with the course.
     *
     * @return string The images associated with the course.
     */
    public function getImages(): string
    {
        return $this->images;
    }

    /**
     * Set the images associated with the course.
     *
     * @param string $images The images associated with the course.
     */
    public function setImages(string $images): void
    {
        $this->images = $images;
    }

    /**
     * Get the audio file of the course.
     *
     * @return string The audio file of the course.
     */
    public function getAudio(): string
    {
        return $this->audio;
    }

    /**
     * Set the audio file of the course.
     *
     * @param string $audio The audio file of the course.
     */
    public function setAudio(string $audio): void
    {
        $this->audio = $audio;
    }

    /**
     * Get the video associated with the course.
     *
     * @return string The video associated with the course.
     */
    public function getVideo(): string
    {
        return $this->video;
    }

    /**
     * Set the video associated with the course.
     *
     * @param string $video The video associated with the course.
     */
    public function setVideo(string $video): void
    {
        $this->video = $video;
    }

    /**
     * Get the PDF file of the course.
     *
     * @return string The PDF file of the course.
     */
    public function getFichierPDF(): string
    {
        return $this->fichierPDF;
    }

    /**
     * Set the PDF file of the course.
     *
     * @param string $fichierPDF The PDF file of the course.
     */
    public function setFichierPDF(string $fichierPDF): void
    {
        $this->fichierPDF = $fichierPDF;
    }

    /**
     * Get the link associated with the course.
     *
     * @return string The link associated with the course.
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * Set the link associated with the course.
     *
     * @param string $link The link associated with the course.
     */
    public function setLink(string $link): void
    {
        $this->link = $link;
    }
}