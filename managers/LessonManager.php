<?php

/**
 * Manages the retrieval and persistence of Lesson object in the platform.
 */
class LessonManager extends AbstractManager
{
    /**
     * Creates a new lesson and persists its in the database.
     *
     * @param Lesson|null $lesson The lesson object to be created.
     *
     * @return Lesson The created lesson object with the assigned identifier.
     */
    public function createLesson(?Lesson $lesson): Lesson
    {
        
        // Prepare the SQL query to insert a new lesson into the database
        $query = $this->db->prepare("INSERT INTO lessons 
        (name, idClass, idTeacher, idTimeTable) 
        VALUES (:name, :idClass, :idTeacher, :idTimeTable)");
        
        // Bind the parameters
        $parameters = [
            ":name" => $lesson->getName(),
            ":idClass" => $lesson->getIdClass(),
            ":idTeacher" => $lesson->getIdTeacher(),
            ":idTimeTable" => $lesson->getIdTimeTable()
            ];
            
        // Execute the query
        $query->execute($parameters);
        
        // Retrieve the last inserted identifier
        $lessonId = $this->db->lastInsertId();
        
        // Set the identifier for the created lesson
        $lesson->setId($lessonId);
        
        // Return the created lesson object
        return $lesson;
    }

    
    /**
     * Retrieves a lesson by its name.
     *
     * @param String $lessonName The name of the lesson.
     *
     * @return Lesson|null The retrieved lesson or null if not found.
     */
    public function findLessonByName(string $lessonName): ?Lesson
    {
        $query = $this->db->prepare("SELECT * FROM lessons WHERE name = :name");
        
        // Bind parameters
        $parameters = [
            ":name" => $lessonName
            ];
        
        $query->execute($parameters);
        
        $lessonData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($lessonData)
        {
            $lesson = new Lesson(
                $lessonData["id"],
                $lessonData["name"],
                $lessonData["idClass"],
                $lessonData["idTeacher"],
                $lessonData["idTimeTable"],
                );
                
            return $lesson;
        }
        // lesson not found
        return null;
    }
    
    
    /**
     * Retrieves a lesson by its unique identifier.
     *
     * @param int $lessonId The unique identifier of the lesson.
     *
     * @return Lesson|null The retrieved lesson or null if not found.
     */
    public function findLessonById(int $lessonId): ?Lesson
    {
        $query = $this->db->prepare("SELECT * FROM lessons WHERE id = :id");
        
        // Bind parameters
        $parameters = [
            ":id" => $lessonId
            ];
        
        $query->execute($parameters);
        
        $lessonData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($lessonData)
        {
            $lesson = new Lesson(
                $lessonData["id"],
                $lessonData["name"],
                $lessonData["idClass"],
                $lessonData["idTeacher"],
                $lessonData["idTimeTable"],
                );
                
            return $lesson;
        }
        // lesson not found
        return null;
    }
    
    
    /**
     * Retrieves  lessons by their idClass.
     *
     * @param int $lessonIdClass The idClass of the lesson.
     *
     * @return Array|null The retrieved lessons or null if not found.
     */
    public function findLessonByIdClass(int $lessonIdClass): ?array
    {
        $query = $this->db->prepare("SELECT lessons.*, classes.* 
        FROM lessons
        JOIN idClass ON classes.id
        WHERE id = :id");
        
        // Bind parameters
        $parameters = [
            ":id" => $lessonIdClass
            ];
        
        $query->execute($parameters);
        
        $lessonsData = $query->fetchAll(PDO::FETCH_ASSOC);
        
        if($lessonsData)
        {
            $lessons = [];
            
            foreach($lessonsData as $lessonData)
            {
                $lesson = new Lesson(
                    $lessonData["id"],
                    $lessonData["name"],
                    $lessonData["idClass"],
                    $lessonData["idTeacher"],
                    $lessonData["idTimeTable"],
                );
                $lessons[] = $lesson;
                
            }
                
            return $lessons;
        }
        // lesson not found
        return null;
    }
    
    
    /**
     * Retrieves a lesson by its idTeacher.
     *
     * @param int $lessonIdTeacher The idTeacher of the lesson.
     *
     * @return Lesson|null The retrieved lesson or null if not found.
     */
    public function findLessonByIdTeacher(int $lessonIdTeacher): ?Lesson
    {
        $query = $this->db->prepare("SELECT lessons.*, teachers.* 
        FROM lessons
        JOIN teachers 
        ON idTeacher = teachers.id 
        WHERE id = :id");
        
        // Bind parameters
        $parameters = [
            ":id" => $lessonIdTeacher
            ];
        
        $query->execute($parameters);
        
        $lessonData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($lessonData)
        {
            $lesson = new Lesson(
                $lessonData["id"],
                $lessonData["name"],
                $lessonData["idClass"],
                $lessonData["idTeacher"],
                $lessonData["idTimeTable"],
                );
                
            return $lesson;
        }
        // lesson not found
        return null;
    }
    
    
    /**
     * Retrieves a lesson by its idTimeTable.
     *
     * @param int $lessonIdTimeTable The idTimeTable of the lesson.
     *
     * @return Lesson|null The retrieved lesson or null if not found.
     */
    public function findLessonByIdTimeTable(int $lessonIdTimeTable): ?Lesson
    {
        $query = $this->db->prepare("SELECT lessons.*, timesTables.* 
        FROM lessons
        JOIN timesTables 
        ON idTimeTable = timesTables.id
        WHERE id = :id");
        
        // Bind parameters
        $parameters = [
            ":id" => $lessonIdTimeTable
            ];
        
        $query->execute($parameters);
        
        $lessonData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($lessonData)
        {
            $lesson = new Lesson(
                $lessonData["id"],
                $lessonData["name"],
                $lessonData["idClass"],
                $lessonData["idTeacher"],
                $lessonData["idTimeTable"],
                );
                
            return $lesson;
        }
        // lesson not found
        return null;
    }
    
    
    /**
     * Retrieves lessons by their week day.
     *
     * @param string $weekDay The day of the week (e.g., "Monday", "Tuesday").
     *
     * @return array|null The retrieved lessons or null if not found.
     */
    public function findLessonsByWeekDay(string $weekDay): ?array
    {
        $query = $this->db->prepare("SELECT * FROM lessons 
        JOIN timeTables ON lessons.idTimeTable = timeTables.id 
        WHERE timeTables.weekDay = :weekDay");
    
        // Bind parameters
        $parameters = [
            ":weekDay" => $weekDay
        ];

        $query->execute($parameters);

        $lessonsData = $query->fetchAll(PDO::FETCH_ASSOC);

        if($lessonsData)
        {
            $lessons = [];

            foreach($lessonsData as $lessonData)
            {
                $lesson = new Lesson(
                    $lessonData["id"],
                    $lessonData["name"],
                    $lessonData["idClass"],
                    $lessonData["idTeacher"],
                    $lessonData["idTimeTable"]
                );
                $lessons[] = $lesson;
            }

            return $lessons;
        }
        // No lessons found for the given day
        return null;
    }
    
    
    /**
     * Updates a lesson in the database.
     *
     * @param Lesson $lesson The lesson to be updated.
     *
     * @return Lesson The lesson updated.
     */
    public function updateLesson(Lesson $lesson): Lesson
    {
        // Prepare the UPDATE query
        $query = $this->db->prepare("UPDATE lessons
            SET name = :name,
            idClass = :idClass,
            idTeacher = :idTeacher,
            idTimeTable = :idTimeTable
            WHERE id = :id");

        // Bind parameters with their values
        $parameters = [
            ":id" => $lesson->getId(),
            ":name" => $lesson->getName(),
            ":idClass" => $lesson->getIdClass(),
            ":idTeacher" => $lesson->getIdTeacher(),
            ":idTimeTable" => $lesson->getIdTimeTable(),
        ];

        // Execute the query with parameters
       $success = $query->execute($parameters);
       if($success)
       {
           return $lesson;
       }
       return null;
        
    }

    /**
     * Deletes a lesson from the database.
     *
     * @param int $lessonId The unique identifier of the lesson to be deleted.
     *
     * @return bool True if the operation is successful, false if not.
     */
    public function deleteLessonById(int $lessonId): bool
    {
        // Prepare the DELETE query
        $query = $this->db->prepare("DELETE FROM lessons WHERE id = :id");

        // Bind parameters with their values
        $parameters = [
            ":id" => $lessonId
            ];

        // Execute the query with parameters
        $success = $query->execute($parameters);

        // Return true if the deletion was successful, false if not
        return $success;
    }
}