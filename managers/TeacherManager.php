<?php

/**
 * Manages the retrieval and persistence of Teacher object in the platform.
 */
class TeacherManager extends AbstractManager
{
    /**
     * Creates a teacher and persists him in the database.
     *
     * @param Teacher $teacher The teacher object to be created.
     *
     * @return teacher The created teacher object with the assigned identifier.
     */
    public function createTeacher(Teacher $teacher): Teacher
    {
        
        // Prepare the SQL query to insert a new teacher into the database
        $query = $this->db->prepare("INSERT INTO teachers 
        (firstName, lastName, email, password, idRole) 
        VALUES (:firstName, :lastName, :email, :password, :idRole)");
        
        // Bind the parameters
        $parameters = [
            ":firstName" => $teacher->getFirstName(),
            ":lastName" => $teacher->getLastName(),
            ":email" => $teacher->getEmail(),
            ":password" => $teacher->getPassword(),
            ":idRole" => $teacher->getIdRole()
            ];
            
        // Execute the query
        $query->execute($parameters);
        
        // Retrieve the last inserted identifier
        $teacherId = $this->db->lastInsertId();
        
        // Set the identifier for the created teacher
        $teacher->setId($teacherId);
        
        // Return the created teacher object
        return $teacher;
    }

    
    /**
     * Retrieves a teacher by his email.
     *
     * @param String $teacherEmail The email of the teacher.
     *
     * @return Teacher|null The retrieved teacher or null if not found.
     */
    public function findTeacherByEmail(string $teacherEmail): ?Teacher
    {
        $query = $this->db->prepare("SELECT * FROM teachers WHERE email = :email");
        
        // Bind parameters
        $parameters = [
            ":email" => $teacherEmail
            ];
        
        $query->execute($parameters);
        
        $teacherData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($teacherData)
        {
            $teacher = new Teacher(
                $teacherData["id"],
                $teacherData["firstName"],
                $teacherData["lastName"],
                $teacherData["email"],
                $teacherData["password"],
                $teacherData["idRole"],
                );
                
            return $teacher;
        }
        // teacher not found
        return null;
    }
    
    
    /**
     * Retrieves a teacher by his id.
     *
     * @param String $teacherId The id of the teacher.
     *
     * @return Teacher|null The retrieved teacher or null if not found.
     */
    public function findTeacherById(int $teacherId): ?Teacher
    {
        $query = $this->db->prepare("SELECT * FROM teachers WHERE id = :id");
        
        // Bind parameters
        $parameters = [
            ":id" => $teacherId
            ];
        
        $query->execute($parameters);
        
        $teacherData = $query->fetch(PDO::FETCH_ASSOC);
        
        if($teacherData)
        {
            $teacher = new Teacher(
                $teacherData["id"],
                $teacherData["firstName"],
                $teacherData["lastName"],
                $teacherData["email"],
                $teacherData["password"],
                $teacherData["idRole"],
                );
                
            return $teacher;
        }
        // teacher not found
        return null;
    }
    
    
    /**
     * Retrieves all teachers from the database
     * 
     * @return array|null An array of teacher object representing all teachers store into the database, or null if no teacher is found
     *
     */
    public function findAll(): ?array
    {
        // Prepare SQL query to select all teachers
        $query = $this->db->prepare("SELECT * FROM teachers");
        
        // Execute the query
        $query->execute();
        
        // Fetch teachers data from the database
        $teachersData = $query->fetchAll(PDO::FETCH_ASSOC);
        
        // Check if teachers data is not empty
        if($teachersData)
        {
            $teachers = [];
            
            // Loop through each teacher data
            foreach($teachersData as $teacherData)
            {
                // Create a teacher object for each teacher data
                $teacher = new Teacher(
                    $teacherData["id"],
                    $teacherData["firstName"],
                    $teacherData["lastName"],
                    $teacherData["email"],
                    $teacherData["password"],
                    $teacherData["idRole"]
                    );
                    
                    // Add the created teacher object to the teachers array
                    $teachers[] = $teacher;
            }
            // Return the array of teacher objects
            return $teachers;
        }
        // Return null if no teachers are found
        return null;
        
    }
    
    
    /**
     * Updates a teacher in the database.
     *
     * @param Teacher $teacher The teacher to be updated.
     *
     * @return Teacher The teacher updated.
     */
    public function updateTeacher(Teacher $teacher): Teacher
    {
        // Prepare the UPDATE query
        $query = $this->db->prepare("UPDATE teachers
            SET firstName = :firstName,
            lastName = :lastName,
            email = :email,
            password = :password,
            idRole = :idRole
            WHERE id = :id");

        // Bind parameters with their values
        $parameters = [
            ":id" => $teacher->getId(),
            ":firstName" => $teacher->getFirstName(),
            ":lastName" => $teacher->getLastName(),
            ":email" => $teacher->getEmail(),
            ":password" => $teacher->getPassword(),
            ":idRole" => $teacher->getIdRole(),
        ];

        // Execute the query with parameters
       $success = $query->execute($parameters);
       if($success)
       {
           return $teacher;
       }
       return null;
        
    }
    
    
    /**
     * Deletes a teacher from the database.
     *
     * @param int $teacherId The unique identifier of the teacher to be deleted.
     *
     * @return bool True if the operation is successful, false if not.
     */
    public function deleteTeacherById(int $teacherId): void
    {
        // Prepare the DELETE query
        $query = $this->db->prepare("DELETE FROM teachers WHERE id = :id");

        // Bind parameters with their values
        $parameters = [
            ":id" => $teacherId
            ];

        // Execute the query with parameters
        $success = $query->execute($parameters);

    }


}