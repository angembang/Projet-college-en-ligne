<?php

/**
 * Manages the retrieval and persistence of Course object in the platform.
 */
class CourseManager extends AbstractManager
{
    /**
     * Creates a new course of a lesson and persists its in the database.
     *
     * @param Course $course The course object to be created.
     *
     * @return Course The created course object with the assigned identifier.
     */
    public function createCourse(Course $course): Course
{
    // Prepare the SQL query to insert a new course into the database
    $query = $this->db->prepare("INSERT INTO courses 
    (idLesson, unlockDate, subject, summary, content, image, audio, video, fichierpdf, link, created_at) 
    VALUES (:idLesson, :unlockDate, :subject, :summary, :content, :image, :audio, :video, :fichierpdf, :link, :created_at)");

    // Bind the parameters
    $parameters = [
        ":idLesson" => $course->getIdLesson(),
        ":unlockDate" => $course->getUnlockDate(),
        ":subject" => $course->getSubject(),
        ":summary" => $course->getSummary(),
        ":content" => $course->getContent(), 
        ":image" => $course->getImage(),
        ":audio" => $course->getAudio(),
        ":video" => $course->getVideo(),
        ":fichierpdf" => $course->getFichierpdf(),
        ":link" => $course->getLink(),
        ":created_at" => $course->getCreatedAt() 
    ];
    
    // Execute the query
    $query->execute($parameters);
    
    // Retrieve the last inserted identifier
    $courseId = $this->db->lastInsertId();
    
    // Set the identifier for the created course
    $course->setId($courseId);
    
    // Return the created lesson object
    return $course;
}
    
    
    /**
     * Retrieves Courses by their lessonName.
     *
     * @param string $courseLessonName The lesson name of the course.
     *
     * @return Array|null Retrieved courses or null if not found.
     */
    public function findCoursesByLessonId(int $courseLessonId): ?array
    {
        $query = $this->db->prepare("SELECT * FROM courses WHERE idLesson = :idLesson ORDER BY created_at DESC");
        
        // Bind parameters
        $parameters = [
            ":idLesson" => $courseLessonId
            ];
        
        $query->execute($parameters);
        
        $coursesData = $query->fetchAll(PDO::FETCH_ASSOC);
        
        if($coursesData)
        {
            $courses = [];
            
            foreach($coursesData as $courseData)
            {
                $course = new Course(
                $courseData["id"],
                $courseData["idLesson"],
                $courseData["unlockDate"],
                $courseData["subject"],
                $courseData["summary"],
                $courseData["content"],
                $courseData["image"],
                $courseData["audio"],
                $courseData["video"],
                $courseData["fichierpdf"],
                $courseData["link"],
                $courseData["created_at"]
                );
                $courses[] = $course;
            }
                
            return $courses;
        }
        // course not found
        return null;
    }

    
    /**
     * Retrieves a Course by its subject.
     *
     * @param String $course The subject of the course.
     *
     * @return Course|null The retrieved course or null if not found.
     */
    public function findCourseBySubject(string $courseSubject): ?Course
    {
        $query = $this->db->prepare("SELECT * FROM courses WHERE subject = :subject");
        
        // Bind parameters
        $parameters = [
            ":subject" => $courseSubject
            ];
        
        $query->execute($parameters);
        
        $courseData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($courseData)
        {
            $course = new Course(
                $courseData["id"],
                $courseData["IdLesson"],
                $courseData["unlockDate"],
                $courseData["subject"],
                $courseData["summary"],
                $courseData["content"],
                $courseData["image"],
                $courseData["audio"],
                $courseData["video"],
                $courseData["fichierpdf"],
                $courseData["link"],
                $courseData["created_at"]
                );
                
            return $course;
        }
        // course not found
        return null;
    }
    
    
    /**
     * Retrieves a course by its unique identifier.
     *
     * @param int $courseId The unique identifier of the course.
     *
     * @return Lourse|null The retrieved course or null if not found.
     */
    public function findCourseById(int $courseId): ?Course
    {
        $query = $this->db->prepare("SELECT * FROM courses WHERE id = :id");
        
        // Bind parameters
        $parameters = [
            ":id" => $courseId
            ];
        
        $query->execute($parameters);
        
        $courseData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($courseData)
        {
            $course = new Course(
                $courseData["id"],
                $courseData["idLesson"],
                $courseData["unlockDate"],
                $courseData["subject"],
                $courseData["summary"],
                $courseData["content"],
                $courseData["image"],
                $courseData["audio"],
                $courseData["video"],
                $courseData["fichierpdf"],
                $courseData["link"],
                $courseData["created_at"]
                );
                
            return $course;
        }
        // lesson not found
        return null;
    }
    
    
     /**
     * Retrieves a Course by its unlockDate.
     *
     * @param String $course The unlockDate of the course.
     *
     * @return Course|null The retrieved course or null if not found.
     */
    public function findCourseByUnlockDay(string $courseUnlockDate): ?Course
    {
        $query = $this->db->prepare("SELECT * FROM courses WHERE unlockDate = :unlockDate");
        
        // Bind parameters
        $parameters = [
            ":unlockDate" => $courseUnlockDate
            ];
        
        $query->execute($parameters);
        
        $courseData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($courseData)
        {
            $course = new Course(
                $courseData["id"],
                $courseData["IdLesson"],
                $courseData["unlockDate"],
                $courseData["subject"],
                $courseData["summary"],
                $courseData["content"],
                $courseData["image"],
                $courseData["audio"],
                $courseData["video"],
                $courseData["fichierpdf"],
                $courseData["link"],
                $courseData["created_at"]
                );
                
            return $course;
        }
        // course not found
        return null;
    }
    
    
    
    
    /**
     * Update a Course in the database.
     *
     * @param Course $course The course to be updated.
     *
     * @return Course The course updated.
     */
    public function updateCourse(Course $course): Course
    {
        // Prepare the UPDATE query
        $query = $this->db->prepare("UPDATE courses
            SET idLesson = :idLesson,
            unlockDate = :unlockDate,
            subject = :subject,
            summary = :summary,
            content = :content,
            image = :image,
            audio = :audio,
            video = :video,
            fichierpdf = :fichierpdf,
            link = :link,
            created_at = :created_at
            WHERE id = :id");

        // Bind parameters with their values
        $parameters = [
            ":id" => $course->getId(),
            ":idLesson" => $course->getIdLesson(),
            ":unlockDate" => $course->getUnlockDate(),
            ":subject" => $course->getSubject(),
            ":summary" => $course->getSummary(),
            ":content" => $course->getContent(),
            ":image" => $course->getImage(),
            ":audio" => $course->getAudio(),
            ":video" => $course->getVideo(),
            ":fichierpdf" => $course->getFichierpdf(),
            ":link" => $course->getLink(),
            ":created_at" => $course->getCreatedAt()
        ];

        // Execute the query with parameters
       $success = $query->execute($parameters);
       if($success)
       {
           return $course;
       }
       return null;
        
    }
    

    /**
     * Deletes a Course from the database.
     *
     * @param int $courseId The unique identifier of the course to be deleted.
     *
     * @return bool True if the operation is successful, false if not.
     */
    public function deleteCourseById(int $courseId): bool
    {
        // Prepare the DELETE query
        $query = $this->db->prepare("DELETE FROM courses WHERE id = :id");

        // Bind parameters with their values
        $parameters = [
            ":id" => $courseId
            ];

        // Execute the query with parameters
        $success = $query->execute($parameters);

        // Return true if the deletion was successful, false if not
        return $success;
    }
}