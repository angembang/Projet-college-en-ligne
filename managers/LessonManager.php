<?php

/**
 * Manages the retrieval and persistence of Lesson object in the platform.
 */
class LessonManager extends AbstractManager
{
    /**
     * Creates a new lesson and persists its in the database.
     *
     * @param Lesson $lesson The lesson object to be created.
     *
     * @return Lesson The created lesson object with the assigned identifier.
     */
    public function createLesson(Lesson $lesson): Lesson
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
        JOIN classes ON lessons.Idclass = classes.id
        WHERE idClass = :id");
        
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
    public function findLessonsByIdTeacher(int $lessonIdTeacher): ?array
    {
        $query = $this->db->prepare("SELECT 
        lessons.* 
        FROM lessons
        JOIN teachers 
        ON lessons.idTeacher = teachers.id 
        WHERE teachers.id = :teacherId");
        
        // Bind parameters
        $parameters = [
            ":teacherId" => $lessonIdTeacher
            ];
        
        $query->execute($parameters);
        
        $lessonsData = $query->fetchAll(PDO::FETCH_ASSOC);

        if($lessonsData) {
            $lessons = [];
            foreach($lessonsData as $lessonData) {
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
     * Retrieves lessons for a specific class and week day.
     *
     * Retrieves lessons scheduled for a particular class on a given week day.
     *
     * @param string $className The name of the class.
     * @param string $weekDay The week day for which lessons are to be retrieved.
     *
     * @return array|null An array of Lesson objects representing the lessons scheduled for the specified class and week day, or null if no lessons are found.
     */
    public function findLessonsByClassLevelAndWeekDay(string $classLevel, string $weekDay): ?array
    {
        $query = $this->db->prepare("SELECT lessons.*, classes.*, timesTables.* 
        FROM lessons 
        JOIN classes ON idClass = classes.id
        JOIN timesTables ON lessons.idTimeTable = timesTables.id 
        WHERE classes.level = :classLevel AND timesTables.weekDay = :weekDay");
    
        // Bind parameters
        $parameters = [
            "classLevel" => $classLevel,
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
    
    
    /*
     * Searches courses of lesson by lesson name
     *
     * @return array|null The array of retrieved courses, null if not found
     *
     * @throws PDOException if an error occurs during the database operation
     */
    public function searchLessonByName(string $lessonName): ?int
    {
        try {
            // Prepare the query to retrieve courses by lesson id
            $query = $this->db->prepare("Select * from lessons WHERE name = :name");
            
            // Bind Parameter
            $parameter = [
                ":name" => $lessonName
                ];
            // Execute the query
            $query->execute($parameter);
            
            $lessonData = $query->fetch(PDO::FETCH_ASSOC);
            
            if( $lessonData) {
                $lesson = new Lesson(
                    $lessonData["id"],
                    $lessonData["name"],
                    $lessonData["idClass"],
                    $lessonData["idTeacher"],
                    $lessonData["idTimeTable"]
                );
                $lessonId = $lesson->getId();
                return $lessonId;
            }
            return null;
           
            
        } catch(PDOException $e) {
            throw new PDOException("An error occurred during the database operation: " . $e->getMessage());
        }
    }
    
    
    /**
     * Retrieves all lessons from the database.
     *
     * @return array|null An array of lesson objects representing all lessons stored in the database, or null if no roles is found.
     */
    public function findAll(): ?array
    {
        // Prepare SQL query to select all lessons
        $query = $this->db->prepare("SELECT * FROM lessons");

        // Execute the query
        $query->execute();

        // Fetch lessons data from the database
        $lessonsData = $query->fetchAll(PDO::FETCH_ASSOC);

        // Initialize an empty array to store lesson objects
        $lessons = [];

        // Check if lessons data is not empty
        if ($lessonsData) {
            // Loop through each lesson data
            foreach ($lessonsData as $lessonData) {
                // Create a lesson object for each lesson data
                $lesson = new Lesson(
                    $lessonData["id"],
                    $lessonData["name"],
                    $lessonData["idClass"],
                    $lessonData["idTeacher"],
                    $lessonData["idTimeTable"]
                );

            // Add the created lesson object to the lessons array
            $lessons[] = $lesson;
        }
    }

    // Return the array of lesson objects
    return $lessons;
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